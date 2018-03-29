import requests
import json
from config import API_KEY

# This script queries the Zomato API with our selected city ID's and obtains possible cuisines from each city
# Then results are ouputed into cuisines.json in order to populated our database with 6_populate_db_cuisines.py

def queryFromCities():
    cities_id = ['685', '292', '281', '280', '277', '291', '289', '288', '306', '279', '283', '302', '201', '304', '276']
    cuisines_in_cities = []
    for city in cities_id:
        try:
            params = {'city_id' : city}
            url = "https://developers.zomato.com/api/v2.1/cuisines?"
            response = requests.get(url, params=params, headers={"user-key": API_KEY})
            cuisines = json.loads(response.text)
            for c in (cuisines['cuisines']):
                if cuisines_in_cities.count(c) == 0:
                    cuisines_in_cities.append(c)
                    print(c)
        except Exception as e:
            print(e)
    return cuisines_in_cities

cuisines = queryFromCities()
with open('cuisines.json', 'w') as json_file:
    json.dump(cuisines, json_file)
json_file.close()
