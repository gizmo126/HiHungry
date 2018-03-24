import requests
import json
import MySQLdb

# 3/23 - getting a list of restaurant_ids from Revews table because something is suspect

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
cursor = db.cursor()

query = ("SELECT restaurant_id FROM Reviews")
try:
    cursor.execute(query)
except Exception as e:
    print(e)
    cursor.close()

missing_restaurant_ids = []
results = cursor.fetchall()
for row in results:
    if(missing_restaurant_ids.count(row[0]) == 0):
        missing_restaurant_ids.append(row[0])

cursor.close

with open('restaurant_id_missing.json', 'w') as json_file:
    json.dump(missing_restaurant_ids, json_file)
json_file.close()

# close the database connection
print("disconnecting from db")
db.close()
