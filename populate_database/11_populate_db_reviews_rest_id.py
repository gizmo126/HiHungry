import requests
import json
import MySQLdb
import random

# 3/23 - because Reviews has restaurant_ids that are imaginary, going to reassign them from our list of existing restaurant_ids

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

with open('restaurant_ids.json', 'r') as json_file:
    rest_ids = json.load(json_file)

for idx in range(0, 1401):
    cursor = db.cursor()
    try:
        # have to leave hanging comma because it expects a duple
        cursor.execute("SELECT * FROM Reviews WHERE review_id=%s", (idx,))
    except Exception as e:
        print(e)
        cursor.close()
    finally:
        rows = cursor.fetchall()
        cursor.close
    for row in rows:
        cursor = db.cursor()
        if rest_ids.count(rows[0][2]) == 0:
            cursor = db.cursor()
            random_rest = rest_ids[random.randint(0, len(rest_ids) - 1)]
            try:
                print "updating review id:", idx
                cursor.execute("UPDATE Reviews SET restaurant_id=%s WHERE review_id=%s", (random_rest, idx))
            except Exception as e:
                print(e)
                cursor.close()
            finally:
                cursor.close()


# close the database connection
print("disconnecting from db")
db.close()
