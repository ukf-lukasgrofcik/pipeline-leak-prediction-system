from tensorflow import keras
import pandas
import numpy

def loadModel(name = 'autoencoder'):
    return keras.models.load_model(f'models/{name}.keras')

def loadDataset(filename):
    return pandas.read_csv(f'data-files/output/{filename}.csv')

def getFeatures(dataframe):
    return dataframe.drop('timestamp', axis = 1)

def makePredictions(model, features):
    predictions = model.predict(features)

    return numpy.mean(numpy.square(predictions - features), axis = 1)

from plotly.graph_objects import Layout, Scatter, Figure
import numpy

def normalizeSeries(series, _min, _max):
    return series
    series_min = numpy.min(series)
    series_max = numpy.max(series)

    scaled_data = _min + ((series - series_min) * (_max - _min)) / (series_max - series_min)

    return scaled_data