from flask import Flask, request, jsonify
from tensorflow import keras
import numpy
import os

app = Flask(__name__)

@app.route('/predict', methods = ['POST'])
def predict():
    # Get features from POST request
    features = request.get_json(force = True)['features']

    # If features is not a list or does not contain 400 numbers, return error
    if not isinstance(features, list) or len(features) != 400:
        return jsonify({ 'error': 'Invalid features' }), 500

    # Load autoencoder model
    path = os.path.join(os.path.dirname(__file__), 'model/autoencoder.h5')
    model = keras.models.load_model(path)

    # Make prediction from features
    predictions = model.predict([features])

    # Calculate reconstruction error
    reconstruction_error = numpy.mean(numpy.square(predictions - [features]), axis = 1)

    # Return reconstruction error
    return jsonify({ 'reconstruction_error': reconstruction_error[0], 'features': features })

if __name__ == '__main__':
    app.run(host = '0.0.0.0', port = 5000)