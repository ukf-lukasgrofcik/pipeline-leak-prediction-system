import pandas
from sklearn.model_selection import train_test_split
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Dense
from tensorflow.keras.callbacks import EarlyStopping

def loadDataset():
    return pandas.read_csv('../csv/output/data_train.csv')

def trainTestSplit(dataframe, test_size = 0.2):
    features = dataframe.drop('timestamp', axis = 1)

    return train_test_split(features, features, test_size = test_size, random_state = 42)

def trainModel(train_x, train_y, epochs = 20, batch = 4, neurons = 512):
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

    model.fit(train_x, train_y, epochs = epochs, batch_size = batch, shuffle = True, callbacks = callbacks)

    return model

def evaluateModel(model, test_x, test_y):
    return model.evaluate(test_x, test_y)

def saveModel(model, filename = 'autoencoder'):
    model.save(f'../models/{filename}.keras')
    model.save(f'../models/{filename}.h5')

dataset = loadDataset()
train_x, test_x, train_y, test_y = trainTestSplit(dataset)

E = 50
B = 16
N = 1024

model = trainModel(train_x, train_y, E, B, N)
results = evaluateModel(model, test_x, test_y)
saveModel(model, f'autoencoder_{N}_{E}_{B}_test')

print(results)