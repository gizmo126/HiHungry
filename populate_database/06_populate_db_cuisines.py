import MySQLdb
import json
import random
from config import DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE

# This script populates a MySQL database with possible cuisines data from the cuisines.json

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
