# HiHungry
Spring 18 CS411 - Database Systems

Live Site: http://gizmohihungry.web.engr.illinois.edu/ (test user: *samhuang*, password: *password*)
Video Demo: https://www.youtube.com/watch?v=BPI_TsDOnHs&feature=youtu.be

- **Advanced Function 1** - Personalized Restaurant Recommendations ([/index.php](index.php))
- **Advanced Function 2** - Text and Sentiment Analysis on Restaurant Reviews ([/analysis/analysis_local.py](analysis/analysis_local.py))

## Run Locally
- Dependencies: PHP 3.1.0, Boostrap 3.3.7, jQuery 3.3.1, Python 3.6 (Python 2.7 for database scripts)
- Add database information in /app/connect.php
- Run `php -S localhost:8000`

## Folders
- /analysis/ - contains the Python scripts and data used to perform one of our advanced functions
- /populate_database/ - contains the Python scripts used to populate the MySQL database with data from Zomato API

## cPanel Server Information
- Hosting Package: ProjectShellPackage
- Server Name:	cpanel3
- cPanel Version:	68.0 (build 37)
- Apache Version:	2.4.29
- PHP Version:	5.6.32
- MySQL Version:	10.0.33-MariaDB
- Architecture:	x86_64
- Operating System:	linux
- Shared IP Address:	192.17.90.133
- Path to Sendmail:	/usr/sbin/sendmail
- Path to Perl:	/usr/bin/perl
- Perl Version:	5.16.3
- Kernel Version:	3.10.0-327.22.2.el7.x86_64

## Final Report
###Briefly describe what the project accomplished.
Picking a place to eat at is sometimes one of the most difficult choices that someone can make. Our group wanted to develop an application which helps people decide where to eat, essentially a restaurant social media application similar to Zomato/Yelp. Users can search restaurants by location or cuisine, review various restaurants they have ate at, and also favorite their most frequented restaurants. The application is also able to use this data to suggest recommended restaurants based on the users’ favorites, restaurant searches, and cuisine preferences. Users are able to find their friends and connect with other users, growing their food network and discovering other popular restaurants.

###Discuss the usefulness of your project, i.e. what real problem you solved.
Our project is useful for user’s to decide where to eat in a specific area and to see other users’ reviews on restaurants to determine whether or not that particular restaurant is suitable for them based on it’s reviews, ratings, cuisine type, and price range. The application has a social media functionality to allow users to friend/connect with other users, helping them find users with similar tastes and see which restaurants that other user has reviewed/favorited. Our algorithm recommends new restaurants to users based off personalized criteria, helping them explore new restaurants. We also provide an in depth look into reviews for each restaurant by using machine learning techniques, picking out what other users are saying about this restaurant and generating sentiment analysis. Using an intuitive user interface that isn’t cluttered and confusing like that of Yelp or Zomato, we provide users with a smooth experience to quickly keep track of their favorite dining spots, explore new restaurants, and discover friends with similar tastes.

###Discuss the data in your database
We have 8 different tables in our database. The restaurant, cuisine, restaurant_type, and review data in our database was populated using the Zomato API. Our group manually input various users for our application’s users, and also asked our friends to create test accounts to review restaurants they have eaten at. We also keep track of user restaurant search history to allow us to more accurately recommend personalized restaurants to users. The favorites table stores the user_id and restaraurant_id for each user’s favorite restaurants. The friends table was used for keeping track of each user’s friends. In our database, we have a total of: 259 restaurants, 1294 reviews, 37 users, and 153 cuisines.

###Include your ER Diagram and Schema
Restaurant(restID, name, price_range, address, city, zipcode, picture, votes, delivery)
User(Username, Password, userid, firstname, lastname, picture)
Cuisine(id, name)
Friends(user1id, user2id)
Favorite(userid, restID)
SearchHistory(userid, searchID, restID)
Review(restID, userid, rating, reviewid, text)
RestaurantType(restID, cuisineid)

###Briefly discuss from where you collected data and how you did it.
We retrieved the data from Zomato using their API. We used Python to query, organize, and store all of the data into different JSON files and inserted this data into our MySQL database. The scripts we used to populate our database are located under the [/populate_database/](/populate_database) folder in our repository.

###Clearly list the functionality of your application (feature specs)
- Login or create an account with username, password, name, and profile picture
- Allow users to browse through restaurants and search restaurants by location and cuisine type
- View the restaurant’s profile and view its average rating, reviews (including text and sentiment analysis results), price range, cuisine type, etc.
- Add a review to a restaurant with a numerical rating and text description
- Search for other users based on name
- Friend/unfriend users and see their favorite restaurants, their friends, and reviews they have written
- View your own profile page, including your favorite restaurants, your friends, and your reviews
- Personalized restaurant recommendation based on an algorithm

###Explain one basic function
One basic function we implemented is adding a review to a restaurant. Once the user gets to a restaurant’s profile page and then clicks the “Add Review+” button, the user can then enter in a numerical value for the rating and write a review text. We used an object oriented approach by defining a review object which looks to help save time.

###Explain your two advanced functions and why they are considered as advanced.
**Advanced Function 1** - Personalized Restaurant Recommendations ([/index.php](index.php))
Our first advanced function is making personalized restaurant recommendations for our users. We decided on this capability because of its usefulness and helpfulness in a user’s point of view. We realized that the reason most people would use this app would be to gather information about different restaurants in order to make a better decision of where to eat. One of the most common decisions people have to make day to day is where to eat. Using this functionality, we would be able to recommend calculated restaurants to go to based on people’s interests and other helpful information, and in a sense help the user make this decision.

Our recommending function takes into account all of the information stored in our database tables, and using specific algorithms, generates a list of restaurants that may interest the user. Some variables the algorithm analyzes are the User’s favorite restaurants, related cuisines based on user searches, and the most popular restaurants among all of the users.

Primarily, we based our recommendation on cuisine, since we figured people generally like the same food type. We were able to gather search data “on-click” by essentially tracking the cuisine searches our users perform. After a number of similar searches, the system is able to realize that the user searches are related, and can use this data to provide similar recommendations.

Using a list of top cuisine searches, we were able to create a list of popular restaurants matching the criteria. Then, existing data is able to tell if each matched restaurant has positive reactions among other users. This tells whether this restaurant would be worth a share to others. The test involves user votes and ratings, and whether they return to the restaurant. If the restaurant passes this test, it moves on to a list of potential recommendation restaurants.

Our recommendation functionality is heavily reliant on user input, as the app is a social food app. Results are better with more input by our users. Yet, we realize that new users will have no friends, favorites, or searches at first. We counter this edge case by recommending the top restaurants of all other users until the system is able to gather enough data from the user to make different recommendations.

**Advanced Function 2** - Text and Sentiment Analysis on Restaurant Reviews ([/analysis/analysis_local.py](analysis/analysis_local.py))
Our second advanced function is performing Sentiment and Text analysis on restaurant reviews. This is done using the NLTK and Scikit-learn toolkits in Python. First, we generate a word cloud, picking out the key adjectives to visualize what other users are saying about the restaurant, with the size of the words depending the frequency in which it appears. Then using machine learning techniques, we train a model with all of our reviews to be able to predict whether each reviews is positive or negative. We determine the overall sentiment of the restaurant by predicting each review, and display the results to the users. This is important for our application because it gives users the ability to gain additional insight into the reviews. It’s a complex way of analyzing the reviews but also provides the user with a short summary of the results in which they can quickly analyze this information. This is a unique feature that other restaurant platforms do not have, and can be developed even further to gather and learn valuable data from user reviews. The results from the sentiment and text analysis can be viewed by opening this functionality on a specific restaurant’s page.

For the independent and dependent variables, `X` represents the review text and `y` represents the rating out of 5. Then to process our plain-text reviews, we use a bag-of-words approach and store each unique word from the text as a number. We remove punctuation and stopwords, and get a list of tokens. Then the text collection is converted into a matrix of token counts and stored into `X`. `X` is then transformed. After processing the reviews text, we split our `X` and `y` into a training and a test set using train_test_split. We use a Multinomial Naive Bayes model to fit to the training set. Then we evaluate the predictions against the actual ratings to judge the accuracy and print out this classification report using `matplotlib` and saved as a png.

We generate another model after this, but instead of using the 1-5 rating as `y`, we use 'pos' or 'neg' with positive being a rating that is greater than 3. We go through the same process to train our model and then, for each review passed in by the application for the current restaurant, we use this second model to predict the overall sentiment of the reviews for this restaurant by either predicting each review as either 'positive' or 'negative'. This increases the accuracy of our prediction to about 70%. Our classification report for this method shows that our model is biased towards positive reviews.
To create a word cloud and show our users what other users are saying about this restaurant, after the tokenization is done on the plain-text reviews, we use part-of-speech tagging. We used tagged text extraction to select all of the adjectives from the reviews. Then for each adjective, the words are rendered to a `word cloud` instance. The size of the word depends on the frequency of the word observed from the reviews. This is done using a Counter on the tokens. Finally, the word cloud is drawn using `matplotlib` and saved as a png.

This advanced function also offers the ability to further increase the accuracy of our model by training it using 10,000 Yelp reviews but due to the massive tradeoff in performance, this feature is not implemented within the Python script run by our PHP application. Rather, it’s in a separate script in another folder in our Github. Although it is possible to save the trained model, to use updated Review data from our database, we chose not to do this. We expect that in time, as more users leave reviews, the prediction accuracy of our model will increase. This function is advanced because it uses various techniques to provide text and sentiment analysis. It also has the ability to incorporate 10,000 Yelp reviews on top of our 1,294 Zomato reviews and use machine learning to train a model. It took a lot of reading to understand these concepts. Then applying these learnings to our restaurant review data took significant effort. We ran into additional configuration issues when running a Python script using PHP which took a lot of debugging.

Sources:
- https://medium.com/tensorist/classifying-yelp-reviews-using-nltk-and-scikit-learn-c58e71e962d9
- https://stackoverflow.com/questions/28200786/how-to-plot-scikit-learn-classification-report
- http://www.nltk.org/howto/sentiment.html
- http://peekaboo-vision.blogspot.com/2012/11/a-wordcloud-in-python.html
- https://pythonspot.com/python-sentiment-analysis/

###Describe one technical challenge that the team encountered.
An issue we faced is that our sentiment and text analysis advanced function does not work on cPanel, only localhost. I can run the Python code through a terminal shell if I SSH into our cPanel account. However, for some reason on cPanel, when running it through a PHP `shell_exec()` command, it does not actually run. There may be a permission issue with the user that `shell_exec()` uses to run the script compared to the user permissions used when I run it through terminal through SSH. Using `test.php` and `test.py`, I am able to demonstrate that we can run a python script through PHP's `shell_exec()`. The data is successfully passed back and forth between the PHP and Python script even while using the application through the browser. However, if I add `import nltk` to the top of `test.py`, it does not run. This import statement causes an error. Other users have encountered this error on stackoverflow but we were not able to resolve it. I tried to change the permissions for the file access to allow the `shell_exec()` user to run it but changing the read write access gave us a 500 internal server error for our application. Engineering IT was not able to resolve the issue.

Sources:
- https://stackoverflow.com/questions/32491545/accessing-python-nltk-with-php-fails
- https://stackoverflow.com/questions/27730715/nltk-cannot-find-the-file

###State if everything went according to the initial development plan and proposed specifications.
Our project followed most of the initial development plan, however there were a few tweaks. At one point, we were looking at a Django application using postgreSQL for our database and Heroku for deploying our site, but due to configuration issues, we decided on a PHP application using cPanel and MySQL. Another tweak we made is that we didn’t keep track of the restaurants users ate at and couldn’t display the menus for every restaurant as we had originally hoped because the Zomato API did not contain this information. We followed the timeline pretty well as we met up numerous times to ensure that we would meet the proposed deadlines.

###Describe the final division of labor and how did you manage team work.
- Sam - setting up development environment, populating database, Github, logout, profile, reviews (add/delete), advanced function (sentiment and text analysis), front-end development (modals, bootstrap components/css, jquery)
- Sean - profile, user, and restaurants search pages, advanced sql queries, advanced function (restaurant recommendation)
- Kevin - Login page, user search and user-to-user functionalities (add/delete),
- Ian - populate restaurant photos, survey for user data/get users to test our application, demo video

Our group was able to work together well in developing this application. Each member was aware of his role and this helped make the whole process of creating HiHungry very efficient. Every member was held accountable for not only completing his own part, but also quality checking that his teammate’s work was up to par. We met up frequently throughout the week and worked hard over spring break to ensure that we would meet all deadlines that we set for ourselves. We strategized our development by keep up with our milestones and organized the process by using a Github repository.
