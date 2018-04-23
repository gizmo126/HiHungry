# Advanced Function - Sentiment and Text Analysis
This is one of our advanced functions. In the HiHungry application, it is run from the `reviewdata.php` page using `shell_exec()` and it passes the reviews of the current restaurant to the python script to perform sentiment analysis, text analysis, and to generate a word cloud and classification report png to pass back to the application. This folder contains a local copy that can be run without the application, `analysis_local.py`, which contains a sample data set that simulates the reviews which the application would have sent and performs analysis on those reviews.

## Description
To analyze our restaurant reviews further, we use sentiment analysis and text analysis methods. For the independent and dependent variables, `X` represents the review text and `y` represents the rating out of 5. Then to process our plain-text reviews, we use a bag-of-words approach and store each unique word from the text as a number. We remove punctuation and stopwords, and get a list of tokens. Then the text collection is converted into a matrix of token counts and stored into `X`. `X` is then transformed.

After processing the reviews text, we split our `X` and `y` into a training and a test set using train_test_split. We use a Multinomial Naive Bayes model to fit to the training set. Then we evaluate the predictions against the actual ratings to judge the accuracy and print out this classification report using `matplotlib` and saved as a png.

We generate another model after this, but instead of using the 1-5 rating as `y`, we use 'pos' or 'neg' with positive being a rating that is greater than 3. We go through the same process to train our model and then, for each review passed in by the application for the current restaurant, we use this second model to predict the overall sentiment of the reviews for this restaurant by either predicting each review as either 'positive' or 'negative'. This increases the accuracy of our prediction to about 70%. Our classification report for this method shows that our model is biased towards positive reviews.

To create a word cloud and show our users what other users are saying about this restaurant, after the tokenization is done on the plain-text reviews, we use part-of-speech tagging. We used tagged text extraction to select all of the adjectives from the reviews. Then for each adjective, the words are rendered to a `word cloud` instance. The size of the word depends on the frequency of the word observed from the reviews. This is done using a Counter on the tokens. Finally, the word cloud is drawn using `matplotlib` and saved as a png.

## How to Run
Running `python analysis_local.py` will run this script. This will use 1,293 Zomato reviews from our database to train the model. The word cloud and classification report will be saved as png images into the `\img\` folder. Our current model yields about 42% accuracy predicting the rating (1-5) of the review and about 70% accuracy predicting whether it is positive or negative review (positive > 3). This script takes about 25 seconds to run.

To increase accuracy of the model, run `python analysis_local.py yelp`. This will use 10,000 Yelp reviews from the `yelp.csv` dataset to increase the accuracy of our model to predict the reviews being passed in. Note this will take a significant amount of time to run (about 9 minutes). The trade off between the slight improvement in accuracy vs. the high runtime made it inefficient and this feature is removed from the `analysis.py` that the application is actually running.

## Notes
Currently, this does not work on cPanel, only localhost. If you SSH in, and run this code through a terminal shell, it will work. However, for some reason on cPanel, when running it through php's `shell_exec()` it does not. There may be a permission issue with the user that `shell_exec()` uses to run the script compared to the user permissions used when I SSH in. Using `test.php` and `test.py`, I am able to demonstrate that we can run a python script through php's `shell_exec()`. The data is successfully passed back and forth between the php and python script even through the browser. However, if I add `import nltk` to the top of `test.py`, it does not run. These two links explore the issue, but trying these fixes broke our cPanel and required a reset so we did not pursue this further.
- https://stackoverflow.com/questions/32491545/accessing-python-nltk-with-php-fails
- https://stackoverflow.com/questions/27730715/nltk-cannot-find-the-file

## Files
- **/img/** - contains the png images outputted by the script
- **analysis_local.py** - this is the file run by our application repurposed for local running. contains a set of reviews to simulate reviews the application would pass
- **reviews.json** - contains all of our review data
- **yelp.csv** - contains 10,000 reviews from Yelp restaurants
- **test.php** - sample php page to test the passing of information from data
- **test.py** - sample python script to test the receiving of data from php and running script using that data

## Python 3.6 Dependencies
- `import sys, json`
- `import nltk`
- `from nltk.corpus import stopwords`
- `from nltk import WordPunctTokenizer, RegexpTokenizer, pos_tag`
- `import numpy as np`
- `import string`
- `import pandas as pd`
- `import sklearn`
- `import re`
- `from collections import Counter`
- `import matplotlib`
- `import matplotlib.pyplot as plt`
- `from sklearn.feature_extraction.text import CountVectorizer`
- `from sklearn.model_selection import train_test_split`
- `from sklearn.naive_bayes import MultinomialNB`
- `from sklearn.metrics import confusion_matrix, classification_report`
- `from wordcloud import WordCloud`

## Have to do this too:
- `pip install scipy`
- `pip install scikit-learn`
- `pip install pandas`
- `pip install nltk`
- `pip install wordcloud`
- `pip install matplotlib`
- `nltk.download('averaged_perceptron_tagger')`
- `nltk.download('stopwords')`
- `nltk.download('universal_tagset')`
- https://stackoverflow.com/questions/37604289/tkinter-tclerror-no-display-name-and-no-display-environment-variable

## Resources
- https://medium.com/tensorist/classifying-yelp-reviews-using-nltk-and-scikit-learn-c58e71e962d9
- https://stackoverflow.com/questions/28200786/how-to-plot-scikit-learn-classification-report
- http://www.nltk.org/howto/sentiment.html
- http://peekaboo-vision.blogspot.com/2012/11/a-wordcloud-in-python.html
- https://pythonspot.com/python-sentiment-analysis/
