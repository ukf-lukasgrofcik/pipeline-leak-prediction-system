from tensorflow import keras
import pandas
import numpy
import os

def loadModel(name = 'autoencoder'):
    return keras.models.load_model(f'./python/models/{name}')

def loadDataset(filename):
    return pandas.read_csv(f'./python/csv/drift/{filename}.csv')

def getFeatures(dataframe):
    return dataframe.drop('timestamp', axis = 1)

def makePredictions(model, features):
    predictions = model.predict(features)

    return numpy.mean(numpy.square(predictions - features), axis = 1)

dataset = loadDataset('data_test_no_leak')
features = getFeatures(dataset)

models = []

for modelName in [file for file in os.listdir('./python/models') if not file.startswith('.') and not file.endswith('.h5')]:
    datasetNoLeak = loadDataset('data_test_no_leak')
    featuresNoLeak = getFeatures(datasetNoLeak)

    model = loadModel(modelName)

    reconstructionErrors = makePredictions(model, featuresNoLeak)

    maxReconstructionError = max(reconstructionErrors)
    threshold = 2 * maxReconstructionError

    datasetLeak = loadDataset('data_test_leak')
    featuresLeak = getFeatures(datasetLeak)

    reconstructionErrors = makePredictions(model, featuresLeak)

    datasetLeak['reconstruction_error'] = makePredictions(model, featuresLeak)

    timestamps = datasetLeak[datasetLeak['reconstruction_error'] > threshold]['timestamp']

    timestampLeakDetected = min(timestamps) if len(timestamps) > 0 else None

    models.append([modelName, threshold, timestampLeakDetected])

validModels = [model for model in models if model[2] != None]

bestModel = min(validModels, key=lambda model: model[2]) if len(validModels) > 0 else None

print('All models:')
print(models)
print('Best model:')
print(bestModel)