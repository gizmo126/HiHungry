import urllib
import MySQLdb
from config import DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE

# 4/8 - loading images from external sources made it slow, downloaded local copy of user photos from db

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

query = ("SELECT profile_url, user_id FROM User")
try:
    cursor.execute(query)
except Exception as e:
    print(e)
    cursor.close()

results = cursor.fetchall()
for row in results:
    filename = row[1]
    img_url = row[0]
    # for those with user photo, this will just throw error and no photo
    if(img_url != None and img_url != 'asdfsdfa'):
        print(img_url)
        urllib.urlretrieve(img_url, "../img/%s.jpg" % (filename))
