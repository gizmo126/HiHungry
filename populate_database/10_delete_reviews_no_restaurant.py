import json
import MySQLdb
from config import DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE

# 4/2 - Some reviews are missing, we don't have boston restaurants for some reason

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

with open('restaurant_ids.json', 'r') as json_file:
    rest_ids = json.load(json_file)

reviews_deleted = []

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
            missing_id = rows[0][2]
            print 'Review ID: {} -- no restaurant associated for rest_id {}, deleting...'.format(idx, missing_id)
            reviews_deleted.append(missing_id)
            try:
                cursor.execute("DELETE FROM Reviews WHERE review_id=%s", (idx, ))
            except Exception as e:
                print(e)
                cursor.close()
            finally:
                cursor.close()

print 'Deleted {} reviews'.format(len(reviews_deleted))

with open('deleted_review_ids.json', 'w') as json_file:
    json.dump(reviews_deleted, json_file)
json_file.close()

# close the database connection
print("disconnecting from db")
db.close()
