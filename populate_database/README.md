# HiHungry Database
- These are the scripts used to populate our MySQL database. Restaurant data is retrieved from the Zomato API.
- Rename `config_example.py` to `config.py` and fill with your information to run these scripts.

## Dependencies
- Python 2.7.14
- MySQL-python
- mysqlclient

## Description of Python Scripts
- 01_populate_json_restaurants.py -- downloads a list of restaurant data from Zomato API according to selected major cities
- 02_populate_db_restaurants.py -- populates the database with the restaurant data
- 03_populate_json_reviews.py -- downloads the review data for our restaurants from the Zomato API
- 03.1_populate_json_ratings.py -- downloads the current ratings that we have on the database in order to fix them in the populate db_review
- 04_populate_db_reviews.py -- populates the database with of the review data
- 05_populate_json_cuisines.py -- downloads all of the cuisines from the Zomato API
- 06_populate_db_cuisines.py -- populates the database with cuisine data
- 07_populate_db_type.py -- populates the database with associatiation between our restaurants and their respective cuisines
- 08_populate_json_rest_id.py -- downloads the restaurant ids of all of our restaurants in our database
- 09_populate_db_reviews_rest_id.py -- adds restaurant ids to reviews that do not have an associated restaurant id in our databases
- 10_delete_reviews_no_restaurant.py -- deletes the reviews in our database that do not have an associated restaurant id
- 11_populate_rest_photos_local.py -- downloads all of our restaurants' photos from database to local copy to speed up page loading. photo is named `<restaurant_id>.jpg`
- 12_populate_user_photos_local.py -- downloads all of our users' photos from database to local copy to speed up page loading. photo is named `<user_id>.jpg`
- 13_populate_db_friend.py -- populates the database with all of our users' friends by assigning user ids
- 14_populate_db_favorites.py -- populates the database with all of our users' favorites by assigning restaurant ids
- 15_populate_json_cuisines.py -- populates the cuisines data in our database for search history
- 16_populate_db_search.py -- populates the database with search history for all of our users. searches are for specific cuisines

## Data Files
- gizmohihungry_test.sql -- backup of our database
- cuisines_id.json -- cuisine id's of all of the cuisines for our restaurants
- cuisines.json -- each cuisine including cuisine name associated with respective id
- deleted_review_ids.json -- list of reviews deleted because of no restaurant association
- restaurant_ids.json -- restaurant id's of all of our restaurants
- restaurants.json -- restaurant data for all of our restaurants queried from the Zomato API
- review_ratings.json -- the ratings of all of our reviews in order of review id
- reviews.json -- list of all our review data associated with respective restaurant id
