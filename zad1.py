from tensorflow import keras
from tensorflow.keras import layers, models, callbacks
from tensorflow.keras.utils import to_categorical
from sklearn.metrics import confusion_matrix, accuracy_score
import numpy as np
import matplotlib.pyplot as plt
import seaborn as sns

# MNIST podatkovni skup
(x_train, y_train), (x_test, y_test) = keras.datasets.mnist.load_data()
x_train_s = x_train.reshape(-1, 28, 28, 1) / 255.0
x_test_s = x_test.reshape(-1, 28, 28, 1) / 255.0

y_train_s = to_categorical(y_train, num_classes=10)
y_test_s = to_categorical(y_test, num_classes=10)

# TODO: strukturiraj konvolucijsku neuronsku mrezu
model = models.Sequential([
    layers.Conv2D(32, (3, 3), activation='relu', input_shape=(28, 28, 1)),
    layers.MaxPooling2D((2, 2)),
    layers.Conv2D(64, (3, 3), activation='relu'),
    layers.MaxPooling2D((2, 2)),
    layers.Flatten(),
    layers.Dense(64, activation='relu'),
    layers.Dense(10, activation='softmax')
])

# TODO: definiraj karakteristike procesa ucenja pomocu .compile()
model.compile(optimizer='adam',
              loss='categorical_crossentropy',
              metrics=['accuracy'])

# TODO: definiraj callbacks
my_callbacks = [
    callbacks.TensorBoard(log_dir='logs', update_freq=100),
    callbacks.ModelCheckpoint(filepath='best_model.h5',
                              monitor='val_accuracy',
                              mode='max',
                              save_best_only=True)
]

# TODO: provedi treniranje mreze pomocu .fit()
model.fit(x_train_s, y_train_s,
          epochs=10,
          batch_size=64,
          validation_split=0.1,
          callbacks=my_callbacks)

#TODO: Ucitaj najbolji model
best_model = keras.models.load_model('best_model.h5')

# TODO: Izracunajte tocnost mreze na skupu podataka za ucenje i skupu podataka za testiranje
train_loss, train_acc = best_model.evaluate(x_train_s, y_train_s, verbose=0)
test_loss, test_acc = best_model.evaluate(x_test_s, y_test_s, verbose=0)
print(f'Točnost na skupu za učenje: {train_acc:.4f}')
print(f'Točnost na testnom skupu: {test_acc:.4f}')

# TODO: Prikazite matricu zabune na skupu podataka za testiranje
y_pred = best_model.predict(x_test_s)
y_pred_classes = np.argmax(y_pred, axis=1)
y_true = np.argmax(y_test_s, axis=1)

cm = confusion_matrix(y_true, y_pred_classes)
plt.figure(figsize=(10, 8))
sns.heatmap(cm, annot=True, fmt='d', cmap='Blues')
plt.title('Matrica zabune - Testni skup')
plt.xlabel('Predviđeno')
plt.ylabel('Stvarno')
plt.show()
