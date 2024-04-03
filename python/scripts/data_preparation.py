import pandas
import os

def loadDataFiles(subdirectory):
    return [ pandas.read_csv(f'../csv/{subdirectory}/{file}') for file in os.listdir(f'../csv/{subdirectory}') if not file.startswith('.') ]

def transformDataframes(dataframes):
    return [ transformDataframe(dataframe) for dataframe in dataframes ]

def transformDataframe(dataframe):
    dataframe = dataframe[dataframe['name'].isin(['F1', 'F2', 'P1', 'P2'])]
    dataframe = dataframe[dataframe['time'] != '2009-04-22 21:25:53.537000']
    dataframe = dataframe[dataframe['time'] != '2009-04-22 21:25:53.537']

    return dataframe[[ 'name', 'value', 'time' ]]

def getFramesFromAllDataFiles(dataframes):
    return [ getFramesFromData(dataframe) for dataframe in dataframes ]

def getFramesFromData(dataframe, frame_row_count = 100):
    F1 = dataframe[dataframe['name'] == 'F1'].sort_values('time')
    F2 = dataframe[dataframe['name'] == 'F2'].sort_values('time')
    P1 = dataframe[dataframe['name'] == 'P1'].sort_values('time')
    P2 = dataframe[dataframe['name'] == 'P2'].sort_values('time')

    frames = []

    for i in range(0, len(F1) - frame_row_count + 1):
        frame = pandas.concat([
            F1[i : i + frame_row_count],
            F2[i : i + frame_row_count],
            P1[i : i + frame_row_count],
            P2[i : i + frame_row_count]
        ])

        frames.append(frame)

    return frames

def extractFeaturesAndTimestamps(frames_all):
    features = []
    timestamps = []

    for frames in frames_all:
        for frame in frames:
            frame = frame.sort_values(['name', 'time'])

            features.append(list(frame['value']))
            timestamps.append(frame['time'].max())

    return features, timestamps

def buildDataframe(features, timestamps):
    columns = []

    for point in ['F1', 'F2', 'P1', 'P2']:
        for i in range(1, 101):
            columns.append(f'{point}-{i}')

    dataframe = pandas.DataFrame(features, columns = columns)
    dataframe['timestamp'] = [ timestamp.split('.')[0] for timestamp in timestamps ]

    return dataframe

def saveDataframe(dataframe, output_name):
    dataframe.to_csv(f'../csv/output/{output_name}.csv', index = False)

def prepareData(source_dir, output_name):
    dataframes = loadDataFiles(source_dir)
    dataframes = transformDataframes(dataframes)
    frames_all = getFramesFromAllDataFiles(dataframes)
    features, timestamps = extractFeaturesAndTimestamps(frames_all)
    dataframe = buildDataframe(features, timestamps)
    saveDataframe(dataframe, output_name)

def validateDataFile(filename):
    dataframe = pandas.read_csv(f'../csv/output/{filename}.csv')

    has_nan_values = dataframe.isna().any().any()
    has_zero_values = (dataframe == 0).any().any()

    if has_nan_values: print(f'CSV file {filename}.csv contains NaN values!')
    if has_zero_values: print(f'CSV file {filename}.csv contains Zero values!')

dataframe = prepareData('train', 'data_train')
validateDataFile('data_train')

prepareData('test-no-leak', 'data_test_no_leak')
validateDataFile('data_test_no_leak')

prepareData('test-leak', 'data_test_leak')
validateDataFile('data_test_leak')