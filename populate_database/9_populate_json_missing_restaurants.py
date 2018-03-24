import requests
import json

# 3/23 - this is a deadend these restaurant_ids aren't real lol, checked with Zomato API

def queryFromMissingID():
    with open('restaurant_id_missing.json', 'r') as json_file:
        rest_ids = json.load(json_file)
    json_file.close()
    restaurants_list = []

    for rest in rest_ids:
        try:
            params = {'res_id' : rest}
            url = "https://developers.zomato.com/api/v2.1/restaurant"
            response = requests.get(url, params=params, headers={"user-key": "f14492965aae25bb9351ce35aca9201a"})
            restaurant_api = json.loads(response.text)
            print(restaurant_api)
        except Exception as e:
            print(e)
    return restaurants_list


missing_restaurants = queryFromMissingID()
'''
with open('restaurant__missing.json', 'w') as json_file:
    json.dump(missing_restaurants, json_file)
json_file.close()
'''
