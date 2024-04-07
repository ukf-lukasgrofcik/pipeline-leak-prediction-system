import pandas
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Dense
from tensorflow.keras.callbacks import EarlyStopping
import os

def loadDataset():
    return pandas.read_csv('./python/csv/output/data_train.csv')

def trainModel(features, epochs = 20, batch = 4, neurons = 512):
    features = features.sample(frac = 1).reset_index(drop = True)

    model = Sequential([
        # Encoder
        Sequential([
            Dense(400, input_shape = (400,), activation = 'relu'),

            Dense(neurons, activation = 'relu'),
        ]),
        # Decoder
        Sequential([
            Dense(neurons, input_shape = (neurons,), activation = 'relu'),

            Dense(400, activation = 'linear'),
        ]),
    ])

    model.compile(optimizer = 'adam', loss = 'mean_squared_error')

    callbacks = [
        EarlyStopping(monitor = 'loss', patience = 5, restore_best_weights = True) # stop when loss starts rising
    ]

    model.fit(features, features, epochs = epochs, batch_size = batch, shuffle = True, callbacks = callbacks)

    return model

def evaluateModel(model, test_x, test_y):
    return model.evaluate(test_x, test_y)

def saveModel(model, filename = 'autoencoder'):
    model.save(f'./python/models/{filename}.keras')
    model.save(f'./python/models/{filename}.h5')

dataset = loadDataset()

features = dataset.drop('timestamp', axis = 1)

EPOCHS = 50
BATCH_SIZE = 16
NEURONS = 1024
VERSIONS = 1

if not os.path.exists('./python/models'):
    os.makedirs('./python/models')

for modelVersion in range(1, VERSIONS + 1):
    model = trainModel(features, features, EPOCHS, BATCH_SIZE, NEURONS)

    saveModel(model, f'autoencoder_{NEURONS}_{EPOCHS}_{BATCH_SIZE}_{modelVersion}')