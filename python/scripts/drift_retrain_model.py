import pandas
from tensorflow import keras
from tensorflow.keras.callbacks import EarlyStopping
import os

def loadDataset():
    return pandas.read_csv('./python/csv/drift/data_train.csv').drop('timestamp', axis = 1)

def loadModel():
    return keras.models.load_model(f'./python/models/drifted_model.keras')

def retrainModel(features, epochs, batch):
    features = features.sample(frac = 1).reset_index(drop = True)

    model = loadModel()

    callbacks = [
        EarlyStopping(monitor = 'loss', patience = 5, restore_best_weights = True)
    ]

    model.fit(features, features, epochs = epochs, batch_size = batch, shuffle = True, callbacks = callbacks)

    return model

def saveModel(model, filename):
    model.save(f'./python/models/{filename}.keras')
    model.save(f'./python/models/{filename}.h5')

dataset = loadDataset()

EPOCHS = 50
BATCH_SIZE = 16
VERSIONS = 3

if not os.path.exists('./python/models'):
    os.makedirs('./python/models')

for modelVersion in range(1, VERSIONS + 1):
    model = trainModel(features, EPOCHS, BATCH_SIZE)

    saveModel(model, f'retrained_model_{modelVersion}')