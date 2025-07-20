from flask import Flask, jsonify
import MySQLdb
import pandas as pd
import datetime
from prophet import Prophet

app = Flask(__name__)

# MySQL configuration
app.config['MYSQL_HOST'] = 'localhost'
app.config['MYSQL_USER'] = 'your_username'
app.config['MYSQL_PASSWORD'] = 'your_password'
app.config['MYSQL_DB'] = 'lms'

# Initialize MySQL connection
mysql = MySQLdb.connect(
    host=app.config['MYSQL_HOST'],
    user=app.config['MYSQL_USER'],
    password=app.config['MYSQL_PASSWORD'],
    db=app.config['MYSQL_DB']
)

@app.route('/visitor_forecast', methods=['GET'])
def visitor_forecast():
    cursor = mysql.cursor()

    # Query the historical visitor data from your MySQL database
    query = """
        SELECT Date, COUNT(*) as visitor_count
        FROM your_table_name
        GROUP BY Date
        ORDER BY Date
    """
    cursor.execute(query)
    result = cursor.fetchall()

    # Convert the query result to a pandas DataFrame
    df = pd.DataFrame(result, columns=['ds', 'y'])  # 'ds' for date, 'y' for visitor count (needed for Prophet)
    
    # Apply the forecasting model (e.g., Prophet)
    model = Prophet()
    model.fit(df)  # Train the model using historical data

    # Forecast for the next 6 months
    future = model.make_future_dataframe(periods=6, freq='M')
    forecast = model.predict(future)

    # Extract only the predicted values
    forecast = forecast[['ds', 'yhat']].tail(6)
    forecast['ds'] = forecast['ds'].dt.strftime('%B %Y')  # Convert dates to 'Month Year' format

    # Prepare the data to send to the frontend
    data = {
        'labels': forecast['ds'].tolist(),  # e.g., ['November 2024', 'December 2024', ...]
        'visitor_count': forecast['yhat'].round().tolist()  # Forecasted visitor counts, rounded
    }

    # Close the database cursor
    cursor.close()

    return jsonify(data)

if __name__ == '__main__':
    app.run(debug=True)
