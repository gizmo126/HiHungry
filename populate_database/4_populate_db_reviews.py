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

with open('reviews.json', 'r') as json_file:
    reviews = json.load(json_file)

insert_statement = (
    "INSERT INTO Reviews (review_id, user_id, restaurant_id, review_text, rating)"
    "VALUES (%s, %s, %s, %s, %s)"
)

for r in reviews:
    rest_id = r['review']['id']
    user_id = random.randint(0, 19)
    if(r['review']['review_text'] == None):
        review_text = 'this is an awesome place to go'
    else:
        review_text = r['review']['review_text']
    if(r['review']['rating'] == 0):
        rating = random.randint(2,4)
    else:
        ratings = r['review']['rating']
    data = (None, user_id, rest_id, review_text.encode('utf-8'), rating)
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
