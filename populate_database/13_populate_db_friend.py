import urllib
import MySQLdb
import random
from config import DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE

# 4/12 - Used to populate friend relationships between users
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
cursor = db.cursor()

insert_statement = (
    "INSERT INTO Friend (user1_id, user2_id)"
    "VALUES (%s, %s)"
)

for idx in range(0, 100):
    user1 = random.randint(1, 33)
    user2 = random.randint(1, 33)
    while user1 == user2:
        user1 = random.randint(1, 33)

    data = (user1, user2)
    print data

    try:
        cursor.execute(insert_statement, data)
    except Exception as e:
        print(e)
        cursor.close()

# close the database connection
print("disconnecting from db")
db.close()
