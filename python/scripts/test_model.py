from tensorflow import keras
import pandas
import numpy
import os

def loadModel(name = 'autoencoder'):
    return keras.models.load_model(f'./python/models/{name}.keras')

def loadDataset(filename):
    return pandas.read_csv(f'./python/csv/output/{filename}.csv')

def getFeatures(dataframe):
    return dataframe.drop('timestamp', axis = 1)

def makePredictions(model, features):
    predictions = model.predict(features)

    return numpy.mean(numpy.square(predictions - features), axis = 1)

dataset = loadDataset('data_test_no_leak')
features = getFeatures(dataset)

models = []

for modelName in [ model for model in os.listdir(f'./python/models') if not file.startswith('.') ]:
    model = loadModel(modelName)

    reconstructionErrors = makePredictions(model, features)

    maxReconstructionError = max(reconstructionErrors)

    model.append([ modelName, maxReconstructionError ])

bestModel = min(models, key = lambda model: model[1])

print('All models:')
print(models)
print('Best model:')
print(bestModel)