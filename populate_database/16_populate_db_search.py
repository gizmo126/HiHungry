import urllib
import MySQLdb
import random
import json
from config import DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE

# 4/18 - Used to populate user's search history
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
    "INSERT INTO Search (user_id, cuisine_id)"
    "VALUES (%s, %s)"
)

cuisine_ids = [1, 3, 193, 227, 270, 182, 168, 30, 491, 158, 25, 161, 192, 100, 541, 959, 38, 40, 45, 501, 274, 134, 156, 143, 233, 148, 154, 55, 60, 67, 136, 66, 70, 73, 137, 82, 983, 320, 304, 83, 461, 471, 966, 89, 141, 177, 179, 163, 150, 95, 308, 99, 6, 152, 151, 175, 201, 955, 5, 132, 159, 133, 247, 956, 111, 121, 287, 928, 881, 153, 411, 958, 268, 651, 316, 149, 112, 298, 318, 521, 114, 140, 135, 207, 164, 69, 74, 147, 117, 996, 995, 50, 321, 139, 183, 162, 970, 219, 87, 361, 84, 998, 601, 691, 210, 119, 974, 611, 267, 972, 85, 921, 211, 997, 190, 964, 93, 142, 451, 641, 131, 381, 963, 86, 954, 22, 202, 229, 203, 205, 228, 218, 265, 178, 901, 75, 961, 128, 761, 264, 965, 962, 296, 401, 960, 989, 173, 936, 209, 971, 671, 110, 271]

for user_id in range(1, 33):
    searches = random.randint(100, 200)
    for idx in range(0, searches):
        cuisine_id = cuisine_ids[random.randint(0, 152)]
        data = (user_id, cuisine_id)
        print data
        try:
            cursor.execute(insert_statement, data)
        except Exception as e:
            print(e)
            cursor.close()

# close the database connection
print("disconnecting from db")
db.close()
