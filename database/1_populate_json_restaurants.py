import requests
import json

# pip install requests
# This script queries the Zomato API with our selected city ID's and obtains 20 restaurants from each city
# Then results are ouputed into restaurants.json in order to populated our database with populate_db_restaurants.py

def queryFromCities():
    cities_id = ['685', '292', '281', '280', '277', '291', '289', '288', '306', '279', '283', '302', '201', '304', '276']
    restaurants_in_cities = []
    for city in cities_id:
        try:
            params = {'entity_id' : city, 'entity_type' : 'city', 'country_id': 216}
            url = "https://developers.zomato.com/api/v2.1/search?&entity_type=city"
            response = requests.get(url, params=params, headers={"user-key": "3933c5ae55c6f5937d3c5508d7e9ce78"})
            restaurants = json.loads(response.text)
            for r in (restaurants['restaurants']):
                restaurants_in_cities.append(r['restaurant'])
        except Exception as e:
            print(e)
    return restaurants_in_cities

restaurants = queryFromCities()
with open('restaurants.json', 'w') as json_file:
    json.dump(restaurants, json_file)
json_file.close()
