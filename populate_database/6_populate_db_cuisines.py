import MySQLdb
import json
import random

# This script populates a MySQL database with review data from the reviews.json file
# The review data is from the Zomato API and outputted into a json file by populate_json_review.py

def dbconnect():
    try:
        db = MySQLdb.connect(
            host='cpanel3.engr.illinois.edu',
            user='gizmohihungry_admin',
            passwd='password123',
            db='gizmohihungry_test'
        )
        print("connected to db")
    except Exception as e:
        print(e)
    return db

db = dbconnect()
db.autocommit(True)

with open('cuisines.json', 'r') as json_file:
    cuisines = json.load(json_file)

insert_statement = (
    "INSERT INTO Cuisine (cuisine_id, cuisine_name)"
    "VALUES (%s, %s)"
)

for c in cuisines:
    cuisine_id = c['cuisine']['cuisine_id']
    cuisine_name = c['cuisine']['cuisine_name']
    data = (cuisine_id, cuisine_name)
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
