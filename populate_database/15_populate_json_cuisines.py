import requests
import json
from config import API_KEY

# 4/18 0 This script populates a json file with our cuisine ids

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
                if cuisines_in_cities.count(c['cuisine']['cuisine_id']) == 0:
                    cuisines_in_cities.append(c['cuisine']['cuisine_id'])
                    print(c['cuisine']['cuisine_id'])
        except Exception as e:
            print(e)
    return cuisines_in_cities

cuisines = queryFromCities()
with open('cuisines_id.json', 'w') as json_file:
    json.dump(cuisines, json_file)
json_file.close()
