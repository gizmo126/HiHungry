import requests
import json
import MySQLdb
from config import DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE

# created 4-2
# This script queries the current ratings that we have in order to fix them in the populate db_review

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
cursor = db.cursor()
query = ("SELECT rating FROM Reviews")

try:
    cursor.execute(query)
except Exception as e:
    print(e)
    cursor.close()

ratings = []
results = cursor.fetchall()
for row in results:
    print row[0]
    ratings.append(row[0])

with open('review_ratings.json', 'w') as json_file:
    json.dump(ratings, json_file)
json_file.close()
