import requests
import json

# pip install requests
# This script queries the Zomato API with our selected city ID's and obtains 20 restaurants from each city
# Then results are ouputed into restaurants.json in order to populated our database with populate_db_restaurants.py

def queryFromCities():
    cities_id = ['685', '292', '281', '280', '277', '291', '289', '288', '306', '279', '283', '302', '201', '304', '276']
    cuisines_in_cities = []
    for city in cities_id:
        try:
            params = {'city_id' : city}
            url = "https://developers.zomato.com/api/v2.1/cuisines?"
            response = requests.get(url, params=params, headers={"user-key": "f14492965aae25bb9351ce35aca9201a"})
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
