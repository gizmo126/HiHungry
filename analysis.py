import sys, json
import nltk
from nltk.corpus import stopwords
from nltk import WordPunctTokenizer, RegexpTokenizer, pos_tag
import numpy as np
import string
import pandas as pd
import sklearn
import re
from collections import Counter
import matplotlib
matplotlib.use('Agg')
import matplotlib.pyplot as plt
from sklearn.feature_extraction.text import CountVectorizer
from sklearn.model_selection import train_test_split
from sklearn.naive_bayes import MultinomialNB
from sklearn.metrics import confusion_matrix, classification_report
from wordcloud import WordCloud

# pip install scipy
# pip install scikit-learn
# pip install pandas
# pip install nltk
# pip install wordcloud
# pip install matplotlib
# https://stackoverflow.com/questions/37604289/tkinter-tclerror-no-display-name-and-no-display-environment-variable
# nltk.download('averaged_perceptron_tagger')
# nltk.download('stopwords')
# nltk.download('universal_tagset')

#Load the data that PHP sent us
try:
    data = json.loads(sys.argv[1])
except:
    print("ERROR")
    sys.exit(1)

results = []

def text_process(text):
    nopunc = [char for char in text if char not in string.punctuation]
    nopunc = ''.join(nopunc)
    return [word for word in nopunc.split() if word.lower() not in stopwords.words('english')]

def plot_classification_report(cr, title='Classification Report for Restaurant Ratings on Multinomial Naive Bayes', with_avg_total=False, cmap=plt.cm.Blues):
    lines = cr.split('\n')
    classes = []
    plotMat = []
    for line in lines[2 : (len(lines) - 3)]:
        t = line.split()
        classes.append(t[0])
        v = [float(x) for x in t[1: len(t) - 1]]
        plotMat.append(v)
    if with_avg_total:
        aveTotal = lines[len(lines) - 1].split()
        classes.append('avg/total')
        vAveTotal = [float(x) for x in t[1:len(aveTotal) - 1]]
        plotMat.append(vAveTotal)
    plt.imshow(plotMat, interpolation='nearest', cmap=cmap)
    plt.title(title)
    plt.colorbar()
    x_tick_marks = np.arange(3)
    y_tick_marks = np.arange(len(classes))
    plt.xticks(x_tick_marks, ['precision', 'recall', 'f1-score'], rotation=45)
    plt.yticks(y_tick_marks, classes)
    plt.tight_layout()
    plt.ylabel('Ratings')
    plt.xlabel('Measures')

def save_classification_plot():
    sampleClassificationReport = classification_report(y_test, preds)
    plot_classification_report(sampleClassificationReport)
    plt.savefig('img/classif_report.png', dpi=200, format='png', bbox_inches='tight')
    plt.close()

def show_wordcloud(data, title='What Others Diners Say:'):
    wordcloud = WordCloud(
        background_color='white',
        stopwords=stopwords.words('english'),
        max_font_size=40,
        scale=3,
        random_state=1
    ).generate(str(" ".join(data)))
    fig = plt.figure(1, figsize=(15, 10))
    plt.axis('off')
    if title:
        fig.suptitle(title, fontsize=50, weight='bold')
        fig.subplots_adjust(top=1.1)
    plt.imshow(wordcloud)

def draw_wordcloud():
    text = " ".join(data)
    tokenizer = RegexpTokenizer(r'\w+')
    wtks = tokenizer.tokenize(text.lower())
    adj_rgxs = re.compile(r"(ADJ)")
    ptgs = pos_tag(wtks, tagset='universal')
    adjs = [tkn[0] for tkn in ptgs if re.match(adj_rgxs, tkn[1])]
    adjs_freq = Counter([txt for txt in adjs])
    adjs = [txt for txt in adjs if adjs_freq[txt] > 0]
    show_wordcloud(adjs)
    plt.savefig('img/word_cloud.png', dpi=200, format='png', bbox_inches='tight')
    plt.close()

with open('reviews.json', 'r') as json_file:
    reviews = json.load(json_file)

all_reviews = []
all_ratings = []
all_sentiment = []

for review in reviews:
    all_reviews.append(review[0])
    all_ratings.append(review[1])
    all_sentiment.append(review[2])

X = all_reviews
y = all_ratings

bow_transformer = CountVectorizer(analyzer=text_process).fit(X)
unique_words = len(bow_transformer.vocabulary_)
X = bow_transformer.transform(X)

# split our X and y into a training and a test set using train_test_split
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.3, random_state=101)

# Multinomial Naive Bayes  model and fit to X_train and y_train
nb = MultinomialNB()
nb.fit(X_train, y_train)
preds = nb.predict(X_test)
save_classification_plot()
draw_wordcloud()

# do it again for sentiment
X2 = all_reviews
y2 = all_sentiment

bow_transformer = CountVectorizer(analyzer=text_process).fit(X2)
unique_words = len(bow_transformer.vocabulary_)
X2 = bow_transformer.transform(X2)

# split our X and y into a training and a test set using train_test_split
X_train2, X_test2, y_train2, y_test2 = train_test_split(X2, y2, test_size=0.3, random_state=101)

# Multinomial Naive Bayes  model and fit to X_train and y_train
nb2 = MultinomialNB()
nb2.fit(X_train2, y_train2)
preds2 = nb2.predict(X_test2)
predictions = []
for d in data:
    negative_review_transformed = bow_transformer.transform(data)
    predictions.append(nb2.predict(negative_review_transformed)[0])

if predictions.count('pos') > len(predictions)/2:
    result = 1  # positive
else:
    result = -1 # negative

# Send it to stdout (to PHP)
print(json.dumps(result))
