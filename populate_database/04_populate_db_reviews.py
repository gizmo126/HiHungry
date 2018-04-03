import MySQLdb
import json
import random
import re
from config import DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE

# updated 4-2: fixed ratings and review text cutoff
# This script populates a MySQL database with review data from the reviews.json file
# The review data is from the Zomato API and outputted into a json file by populate_json_review.py

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

with open('reviews.json', 'r') as json_file:
    reviews = json.load(json_file)

with open('review_ratings.json', 'r') as json_file:
    ratings = json.load(json_file)

insert_statement = (
    "INSERT INTO Reviews (review_id, user_id, restaurant_id, review_text, rating)"
    "VALUES (%s, %s, %s, %s, %s)"
)

counter = 0
for r in reviews:
    rest_id = r['rest_id']
    user_id = random.randint(1, 26)

    # fix all the review, get rid of last sentence that gets cut off
    if(r['review']['review_text'] == None):
        review_text = 'This is an awesome place to go!'
    else:
        review_text = r['review']['review_text']

    # remove ellipses, doesn't fully work because ellipses are scattered throughout review
    temp_review_text = re.findall(r'(?:(?=[^.]|(?<=\w)\.(?=\w)\.(?=\w)).)+', review_text)
    fixed_array = []
    for idx in range(0, len(temp_review_text) - 1):
        fixed_array.append(temp_review_text[idx])
    fixed_array.append('.')
    fixed_review_text = ''.join(fixed_array)
    if len(fixed_review_text) == 0 or fixed_review_text == '.':
        fixed_review_text = temp_review_text[0]

    # print fixed_review_text

    # fixed existing ratings
    # https://docs.google.com/document/d/19aufFePSbhHyC7l9lDwgsoyQhWhZe7ksNkVBiUjUevk/edit
    threeHigher = [302, 303, 304, 305, 308, 310, 311, 313, 319, 322, 323, 325, 326, 329, 331, 332, 335, 337, 339, 341, 343, 345, 346, 347, 348, 350, 353, 354, 355, 356, 357, 360, 365, 367, 369, 381, 384, 388, 389, 390, 392, 394, 396, 399, 400, 440, 441, 442, 443, 445, 446, 447, 448, 449, 450, 451, 452, 456, 460, 463, 464, 466, 471, 472, 473, 475, 479, 480, 481, 485, 486, 488, 496, 499, 500, 501, 503, 504, 505, 506, 507, 508, 510, 511, 514, 515, 522, 524, 525, 526, 527, 529, 544, 546, 547, 548, 549, 550, 552, 554, 555, 558, 560, 562, 566, 569, 613, 616, 617, 620, 622, 623, 624, 625, 626, 635, 637, 640, 641, 643, 644, 647, 648, 649, 650, 651, 652, 653, 655, 656, 661, 662, 663, 666, 668, 670, 673, 675, 676, 685, 689, 695, 696, 698, 700, 701, 702, 703, 704, 705, 706, 713, 779, 780, 783, 784, 785, 788, 791, 792, 793, 794, 796, 797, 798, 801, 803, 804, 805, 806, 809, 810, 815, 821, 822, 823, 324, 325, 326, 327, 328, 329, 832, 834, 835, 836, 837, 838, 839, 840, 841, 842, 843, 844, 845, 846, 850, 851, 852, 854, 856, 857, 858, 866, 869, 872, 876, 877, 880, 881, 882, 883, 884, 943, 984, 985, 986, 987, 992, 994, 1072, 1074, 1075, 1076, 1077, 1078, 1079, 1080, 1081, 1082, 1083, 1085, 1087, 1088, 1089, 1090, 1092, 1094, 1095, 1096, 1097, 1100, 1103, 1104, 1105, 1106, 1107, 1109, 1110, 1111, 1112, 1115, 1116, 1117, 1118, 1120, 1123, 1124, 1125, 1126, 1127, 1128, 1129, 1131, 1133, 1134, 1135, 1136, 1137, 1138, 1139, 1140, 1141, 1142, 1143, 1145, 1147, 1150, 1152, 1153, 1154, 1155, 1156, 1161, 1162, 1163, 1164, 1165, 1166, 1167, 1171, 1172, 1174, 1176, 1177, 1180, 1181, 1183, 1184]
    threeLower = [312, 364, 382, 458, 523, 557, 632, 646, 659, 669, 710, 711, 712, 761, 807, 830, 1073, 1157]
    three = [397, 405, 532, 694, 715, 741, 750, 903, 904, 909, 910, 925, 941, 977, 982, 1003, 1013, 1020, 1063, 1068, 1193, 1205, 1210, 1211, 1218, 1219, 1223, 1224, 1225, 1247, 1256, 1273, 1274, 1275, 1276, 1279, 1286, 1294, 1295, 1311, 1329, 1342, 1350, 1359, 1363, 1365, 1375, 1376, 1380]
    lower = [15, 19, 20, 40, 41, 52, 57, 60, 65, 85, 98, 99, 100, 102, 149, 152, 158, 177, 197, 215, 216, 222, 259, 474, 530, 608, 690, 737, 1058, 1149, 1245, 1271, 1272, 1288, 1320, 1348, 1379]
    higher = [2, 5, 6, 9, 11, 12, 18, 22, 30, 31, 32, 35, 36, 43, 45, 48, 58, 67, 69, 72, 74, 75, 80, 83, 90, 91, 93, 266, 268, 270, 271, 272, 273, 274, 275, 276, 278, 280, 281, 282, 283, 284, 285, 286, 287, 288, 291, 401, 404, 406, 409, 410, 411, 412, 413, 414, 416, 417, 418, 419, 420, 421, 422, 423, 424, 426, 427, 428, 429, 430, 436, 539, 587, 588, 589, 590, 591, 592, 593, 594, 595, 596, 597, 598, 599, 770, 772, 773, 775, 776, 778, 893, 898, 899, 900, 901, 902, 905, 906, 907, 908, 911, 912, 913, 914, 915, 916, 917, 918, 919, 920, 922, 923, 924, 926, 927, 928, 929, 931, 932, 933, 934, 935, 936, 937, 938, 939, 940, 942, 1148, 1290, 1291, 1292, 1293, 1296, 1297, 1298, 1299, 1351, 1352, 1353, 1354, 1355, 1356, 1358, 1360, 1361, 1362, 1364, 1366, 1367, 1368, 1369, 1370, 1371, 1372, 1373, 1374, 1377, 1378]

    if(r['review']['rating'] == 0):
        if threeHigher.count(counter) > 0:
            print 'three higher'
            rating = random.randint(4, 5)
        elif threeLower.count(counter) > 0:
            print 'three lower'
            rating = random.randint(1, 2)
        elif three.count(counter) > 0:
            print 'three'
            rating = 3
        elif lower.count(counter) > 0:
            print 'lower'
            rating = random.randint(2, 3)
        elif higher.count(counter) > 0:
            print 'higher'
            rating = random.randint(3, 5)
        else:
            print 'existing'
            rating = ratings[counter]
    else:
        print 'from api'
        rating = int(r['review']['rating'])

    print counter

    data = (None, user_id, rest_id, fixed_review_text.encode('utf-8'), rating)
    cursor = db.cursor()
    try:
        cursor.execute(insert_statement, data)
    except Exception as e:
        print(e)
        cursor.close()
    finally:
        cursor.close()

    counter = counter + 1

# close the database connection
print("disconnecting from db")
db.close()
