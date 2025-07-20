from flask import Flask, jsonify, request
import pymysql
import pandas as pd
from prophet import Prophet
from flask_cors import CORS
import numpy as np
from sklearn.metrics import mean_absolute_error, mean_squared_error
from xgboost import XGBRegressor

app = Flask(__name__)
CORS(app)

# MySQL configuration
app.config['MYSQL_HOST'] = 'localhost'
app.config['MYSQL_USER'] = 'root'
app.config['MYSQL_PASSWORD'] = ''
app.config['MYSQL_DB'] = 'lms'

def get_mysql_connection():
    try:
        connection = pymysql.connect(
            host=app.config['MYSQL_HOST'],
            user=app.config['MYSQL_USER'],
            password=app.config['MYSQL_PASSWORD'],
            db=app.config['MYSQL_DB']
        )
        return connection
    except pymysql.MySQLError as e:
        print(f"Error connecting to MySQL: {e}")
        return None

def preprocess_data(df):
    all_dates = pd.date_range(start=df['ds'].min(), end=df['ds'].max())
    missing_dates = all_dates.difference(df['ds'].dropna())
    missing_df = pd.DataFrame({'ds': missing_dates, 'y': 0})
    df = pd.concat([df, missing_df]).sort_values(by='ds').reset_index(drop=True)

    min_value = 5
    df['y'] = df['y'].apply(lambda x: max(x, min_value))
    df['y'] = df['y'].fillna(df['y'].mean())
    df['y_smoothed'] = df['y'].ewm(span=7, adjust=False).mean()
    df['y_smoothed'] = np.log(df['y_smoothed'])

    # Additional features for XGBoost
    df['day_of_week'] = df['ds'].dt.dayofweek
    df['day_of_month'] = df['ds'].dt.day
    df['month'] = df['ds'].dt.month
    df['is_weekend'] = (df['day_of_week'] >= 5).astype(int)
    df['rolling_mean_7'] = df['y'].rolling(window=7, min_periods=1).mean()
    df['rolling_mean_14'] = df['y'].rolling(window=14, min_periods=1).mean()
    df['exp_smoothing'] = df['y'].ewm(span=15, adjust=False).mean()
    df['lag_1'] = df['y'].shift(1)
    df['lag_7'] = df['y'].shift(7)
    df['lag_14'] = df['y'].shift(14)
    df['lag_30'] = df['y'].shift(30)

    df = df.fillna(0)
    return df

def calculate_accuracy(df, train_ratio=0.9):
    df = preprocess_data(df)
    train_size = int(len(df) * train_ratio)
    train_df = df[['ds', 'y_smoothed']][:train_size].rename(columns={'y_smoothed': 'y'})
    validation_df = df[['ds', 'y_smoothed']][train_size:].rename(columns={'y_smoothed': 'y'})

    # Prophet model setup
    model = Prophet(
        changepoint_prior_scale=0.01,
        seasonality_prior_scale=5.0,
        seasonality_mode='multiplicative'
    )
    model.add_country_holidays(country_name='US')
    model.add_seasonality(name='weekly', period=7, fourier_order=10)
    model.add_seasonality(name='yearly', period=365.25, fourier_order=25)
    model.add_seasonality(name='quarterly', period=91.25, fourier_order=10)
    model.fit(train_df)
    
    future = model.make_future_dataframe(periods=len(validation_df), freq='D')
    forecast = model.predict(future)
    forecast['yhat'] = np.exp(forecast['yhat'])

    # XGBoost model setup
    xgb_model = XGBRegressor(
        objective='reg:squarederror',
        n_estimators=400,
        max_depth=12,
        learning_rate=0.02,
        subsample=0.9,
        colsample_bytree=0.85,
        gamma=0.05,
        alpha=0.01
    )
    xgb_train_features = df[['day_of_week', 'day_of_month', 'month', 'is_weekend', 'rolling_mean_7', 'rolling_mean_14', 'exp_smoothing', 'lag_1', 'lag_7', 'lag_14', 'lag_30']][:train_size]
    xgb_model.fit(xgb_train_features, np.exp(train_df['y']))
    xgb_validation_features = df[['day_of_week', 'day_of_month', 'month', 'is_weekend', 'rolling_mean_7', 'rolling_mean_14', 'exp_smoothing', 'lag_1', 'lag_7', 'lag_14', 'lag_30']][train_size:]
    xgb_forecast = xgb_model.predict(xgb_validation_features)

    # Blending predictions
    prophet_pred = forecast['yhat'][-len(validation_df):].values
    ensemble_pred = 0.52 * prophet_pred + 0.48 * xgb_forecast

    actuals = np.exp(validation_df['y'].values)
    mae = mean_absolute_error(actuals, ensemble_pred)
    mape = np.mean(np.abs((actuals - ensemble_pred) / actuals)) * 100
    smape = 100 * np.mean(2 * np.abs(ensemble_pred - actuals) / (np.abs(actuals) + np.abs(ensemble_pred)))
    rmse = np.sqrt(mean_squared_error(actuals, ensemble_pred))

    return {
        'Mean Absolute Error (MAE)': mae,
        'Mean Absolute Percentage Error (MAPE)': mape,
        'Symmetric Mean Absolute Percentage Error (SMAPE)': smape,
        'Root Mean Square Error (RMSE)': rmse
    }

@app.route('/monthly_visitor_forecast', methods=['GET'])
def monthly_visitor_forecast():
    mysql = get_mysql_connection()
    if not mysql:
        return jsonify({'error': 'MySQL connection failed.'}), 500
    
    cursor = mysql.cursor()

    try:
        query = """
            SELECT Date, COUNT(*) as visitor_count
            FROM male_1
            GROUP BY Date
            ORDER BY Date
        """
        cursor.execute(query)
        result = cursor.fetchall()

        df = pd.DataFrame(result, columns=['ds', 'y'])
        df['ds'] = pd.to_datetime(df['ds'], errors='coerce')
        
        latest_date = df['ds'].max()
        print("Latest date in training data:", latest_date)

        model = Prophet()
        model.fit(df)

        forecast_start_date = latest_date + pd.DateOffset(days=1)
        forecast_end_date = forecast_start_date + pd.DateOffset(months=6) - pd.DateOffset(days=1)

        future = model.make_future_dataframe(periods=180)
        forecast = model.predict(future)
        forecast['ds'] = pd.to_datetime(forecast['ds'], errors='coerce')

        future_forecast = forecast[(forecast['ds'] >= forecast_start_date) & (forecast['ds'] <= forecast_end_date)]
        monthly_future_forecast = future_forecast.resample('ME', on='ds').agg({'yhat': 'sum'}).reset_index()
        monthly_future_forecast = monthly_future_forecast.rename(columns={'yhat': 'predicted_count'})
        monthly_future_forecast['ds'] = monthly_future_forecast['ds'].dt.strftime('%b %Y')

        data = {
            'labels': monthly_future_forecast['ds'].tolist(),
            'visitor_count': monthly_future_forecast['predicted_count'].round().tolist()
        }

        return jsonify(data)

    except Exception as e:
        return jsonify({'error': str(e)}), 500

    finally:
        cursor.close()
        mysql.close()

@app.route('/forecast_accuracy', methods=['GET'])
def forecast_accuracy():
    mysql = get_mysql_connection()
    if not mysql:
        return jsonify({'error': 'MySQL connection failed.'}), 500

    cursor = mysql.cursor()
    try:
        query = """
            SELECT Date, COUNT(*) as visitor_count
            FROM male_1
            GROUP BY Date
            ORDER BY Date
        """
        cursor.execute(query)
        result = cursor.fetchall()
        df = pd.DataFrame(result, columns=['ds', 'y'])
        df['ds'] = pd.to_datetime(df['ds'], errors='coerce')

        train_ratio = float(request.args.get('train_ratio', 0.85))
        accuracy = calculate_accuracy(df, train_ratio=train_ratio)

        return jsonify(accuracy)

    except Exception as e:
        return jsonify({'error': str(e)}), 500

    finally:
        cursor.close()
        mysql.close()

@app.route('/visitor_forecast', methods=['GET'])
def visitor_forecast():
    mysql = get_mysql_connection()
    if not mysql:
        return jsonify({'error': 'MySQL connection failed.'}), 500
    
    cursor = mysql.cursor()
    try:
        query = """
            SELECT Date, COUNT(*) as visitor_count
            FROM male_1
            GROUP BY Date
            ORDER BY Date
        """
        cursor.execute(query)
        result = cursor.fetchall()
        df = pd.DataFrame(result, columns=['ds', 'y'])
        df['ds'] = pd.to_datetime(df['ds'], errors='coerce')
        df = preprocess_data(df)

        model = Prophet(seasonality_mode='additive')
        model.add_seasonality(name='weekly', period=7, fourier_order=10)
        model.add_seasonality(name='yearly', period=365.25, fourier_order=20)
        model.add_seasonality(name='quarterly', period=91.25, fourier_order=8)
        model.fit(df[['ds', 'y_smoothed']].rename(columns={'y_smoothed': 'y'}))

        future = model.make_future_dataframe(periods=180)
        forecast = model.predict(future)
        forecast['ds'] = pd.to_datetime(forecast['ds'], errors='coerce')
        forecast['yhat'] = np.exp(forecast['yhat'])
        future_forecast = forecast[forecast['ds'] > '2024-10-10']
        
        daily_forecast = future_forecast[['ds', 'yhat']]
        daily_forecast = daily_forecast.rename(columns={'yhat': 'predicted_count'})
        daily_forecast['ds'] = daily_forecast['ds'].dt.strftime('%d %b %Y')

        data = {
            'labels': daily_forecast['ds'].tolist(),
            'visitor_count': daily_forecast['predicted_count'].round().tolist()
        }

        return jsonify(data)

    except Exception as e:
        return jsonify({'error': str(e)}), 500

    finally:
        cursor.close()
        mysql.close()

if __name__ == '__main__':
    app.run(debug=True)
