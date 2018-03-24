import MySQLdb
import json
import random

# This script populates a MySQL database with Type relation
# reads cuisines.json and restaurants.json
# assigns table with restaurants and its cuisine types

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

with open('restaurants.json', 'r') as json_file:
    restaurants = json.load(json_file)

insert_statement = (
    "INSERT INTO Restaurant_Type (restaurant_id, cuisine_id)"
    "VALUES (%s, %s)"
)

for r in restaurants:
    restaurant_id = r['id']
    r_cuisines = r['cuisines']
    r_cuisines_fixed = [x.strip() for x in r_cuisines.split(',')]
    for r_cuisine in r_cuisines_fixed:
        for c in cuisines:
            cuisine_id = c['cuisine']['cuisine_id']
            cuisine_name = c['cuisine']['cuisine_name']
            if (cuisine_name == r_cuisine):
                found_id = cuisine_id
                data = (restaurant_id, found_id)
                cursor = db.cursor()
                try:
                    cursor.execute(insert_statement, data)
                except Exception as e:
                    print(e)
                    cursor.close()
                finally:
                    cursor.close()
                break

# close the database connection
print("disconnecting from db")
db.close()
