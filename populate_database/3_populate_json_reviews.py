import requests
import json

# This script queries the Zomato API with city ID's from the previously obtained restaurant.json file
# It filters out zip codes that are not located within the US
# Then for each restaurant, we get 5 reviews and also add the restaurant_id as a field
# Finally, we output the results into review.json for use by populate_db_reviews.py

with open('restaurants.json', 'r') as json_file:
    restaurants = json.load(json_file)

reviews_list = []
count = 0

for rest in restaurants:
    rest_id = rest['id']
    try:
        if(len(rest['location']['zipcode']) >= 5):
            params = {'res_id' : rest_id}
            url = "https://developers.zomato.com/api/v2.1/reviews"
            response = requests.get(url, params=params, headers={"user-key": "f14492965aae25bb9351ce35aca9201a"})
            reviews = json.loads(response.text)
            for r in reviews['user_reviews']:
                r['rest_id'] = rest_id
                reviews_list.append(r)
                count += 1
    except Exception as e:
        print(e, count)

with open('reviews.json', 'w') as json_file:
    json.dump(reviews_list, json_file)
json_file.close()
