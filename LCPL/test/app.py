from flask import Flask, jsonify
import pymysql
import pandas as pd
from prophet import Prophet
from flask_cors import CORS  # Import CORS
from sklearn.metrics import mean_absolute_percentage_error  # Import MAPE calculation

app = Flask(__name__)
CORS(app)  # Enable CORS for all routes

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

@app.route('/visitor_forecast', methods=['GET'])
def visitor_forecast():
    mysql = get_mysql_connection()
    if not mysql:
        return jsonify({'error': 'MySQL connection failed.'}), 500
    
    cursor = mysql.cursor()

    try:
        # Query the historical visitor data from your MySQL database
        query = """
            SELECT Date, COUNT(*) as visitor_count
            FROM male_1  -- Replace with your actual table name
            GROUP BY Date
            ORDER BY Date
        """
        cursor.execute(query)
        result = cursor.fetchall()

        # Convert the query result to a pandas DataFrame
        df = pd.DataFrame(result, columns=['ds', 'y'])  # 'ds' for date, 'y' for visitor count (needed for Prophet)
        df['ds'] = pd.to_datetime(df['ds'], errors='coerce')

        # Split data into training and validation sets (e.g., last 30 days for validation)
        validation_days = 30
        train_df = df.iloc[:-validation_days]
        val_df = df.iloc[-validation_days:]

        # Apply the forecasting model (e.g., Prophet)
        model = Prophet()
        model.fit(train_df)  # Train the model using historical training data

        # Make predictions for the validation period
        future_val = model.make_future_dataframe(periods=validation_days, freq='D')
        forecast_val = model.predict(future_val)

        # Extract the predictions for the validation period and calculate MAPE
        val_predictions = forecast_val[['ds', 'yhat']].iloc[-validation_days:].set_index('ds')
        val_actual = val_df.set_index('ds')
        mape = mean_absolute_percentage_error(val_actual['y'], val_predictions['yhat']) * 100  # Calculate MAPE as a percentage

        # Forecast for the next 6 months (daily granularity)
        future = model.make_future_dataframe(periods=180)  # 180 days for daily forecasts
        forecast = model.predict(future)
        forecast['ds'] = pd.to_datetime(forecast['ds'], errors='coerce')

        # Filter the forecast to only include dates after October 10, 2024
        future_forecast = forecast[forecast['ds'] > '2024-10-10']

        # Select only the columns for the date ('ds') and the predicted value ('yhat')
        daily_future_forecast = future_forecast[['ds', 'yhat']]
        daily_future_forecast = daily_future_forecast.rename(columns={'yhat': 'predicted_count'})

        # Convert dates to 'Day Mon Year' format for display
        daily_future_forecast['ds'] = daily_future_forecast['ds'].dt.strftime('%d %b %Y')

        data = {
            'labels': daily_future_forecast['ds'].tolist(),  # Daily dates
            'visitor_count': daily_future_forecast['predicted_count'].round().tolist(),  # Daily predicted visitor counts
            'mape': round(mape, 2)  # Add MAPE as prediction accuracy, rounded to two decimal places
        }

        return jsonify(data)

    except Exception as e:
        return jsonify({'error': str(e)}), 500

    finally:
        cursor.close()
        mysql.close()
