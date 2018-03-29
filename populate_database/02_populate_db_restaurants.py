import MySQLdb
import json
import random
from config import DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE

# This script populates a MySQL database with restaurant data from the restaurants.json file
# The restaurant data is from the Zomato API and outputted into a json file by populate_json_restaurants.py
# For some reason queried includde a few restaurants that were not in the US so manually removed from db

def dbconnect():
    try:
        db = MySQLdb.connect(
            host = DB_HOST,
            user = DB_USER,
            passwd = DB_PASSWORD,
            db = DB_DATABASE
        )
        print("connected to db")
    except Exception as e:
        print(e)
    return db

db = dbconnect()
db.autocommit(True)

with open('restaurants.json', 'r') as json_file:
    restaurants = json.load(json_file)

insert_statement = (
    "INSERT INTO Restaurant (restaurant_id, restaurant_name, address, city, zipcode, price_range, delivers, rating, votes)"
    "VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)"
)

for r in restaurants:
    rest_id = r['id']
    name = r['name']
    address = r['location']['address']
    city = r['location']['city']
    zipcode = r['location']['zipcode']
    price_range = r['price_range']
    delivers = bool(random.getrandbits(1))
    rating = r['user_rating']['aggregate_rating']
    votes = r['user_rating']['votes']
    data = (rest_id, name, address, city, zipcode, price_range, delivers, rating, votes)
    cursor = db.cursor()
    try:
        cursor.execute(insert_statement, data)
    except Exception as e:
        print(e)
        cursor.close()
    finally:
        cursor.close()

# close the database connection
print("disconnecting from db")
db.close()
