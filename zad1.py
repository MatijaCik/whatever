import numpy as np
import matplotlib.pyplot as plt
import tensorflow as tf
from tensorflow import keras
from tensorflow.keras import layers
from sklearn.metrics import confusion_matrix, ConfusionMatrixDisplay
import seaborn as sns

# 1. Učitavanje i priprema MNIST podataka
(x_train, y_train), (x_test, y_test) = keras.datasets.mnist.load_data()
x_train = x_train.reshape(-1, 28, 28, 1).astype("float32") / 255
x_test = x_test.reshape(-1, 28, 28, 1).astype("float32") / 255

# 2. One-hot enkodiranje labela
y_train_cat = keras.utils.to_categorical(y_train, 10)
y_test_cat = keras.utils.to_categorical(y_test, 10)

# 3. Definicija CNN modela prema slici 8.1
model = keras.Sequential([
    layers.Conv2D(32, (3, 3), activation='relu', input_shape=(28, 28, 1)),
    layers.MaxPooling2D((2, 2)),
    layers.Conv2D(64, (3, 3), activation='relu'),
    layers.MaxPooling2D((2, 2)),
    layers.Flatten(),
    layers.Dense(64, activation='relu'),
    layers.Dense(10, activation='softmax')
])

model.compile(optimizer='adam',
              loss='categorical_crossentropy',
              metrics=['accuracy'])

# 4. Callback-ovi: TensorBoard i ModelCheckpoint
callbacks = [
    keras.callbacks.TensorBoard(log_dir='logs', update_freq=100),
    keras.callbacks.ModelCheckpoint('best_model.h5', save_best_only=True,
                                    monitor='val_accuracy', mode='max')
]

# 5. Treniranje modela
history = model.fit(x_train, y_train_cat,
                    epochs=10,
                    batch_size=64,
                    validation_split=0.1,
                    callbacks=callbacks)

# 6. Učitavanje najboljeg modela i evaluacija
best_model = keras.models.load_model("best_model.h5")
train_loss, train_acc = best_model.evaluate(x_train, y_train_cat, verbose=0)
test_loss, test_acc = best_model.evaluate(x_test, y_test_cat, verbose=0)
print(f"Točnost na skupu za učenje: {train_acc:.4f}")
print(f"Točnost na testnom skupu: {test_acc:.4f}")

# 7. Matrice zabune
y_pred_train = np.argmax(best_model.predict(x_train), axis=1)
y_pred_test = np.argmax(best_model.predict(x_test), axis=1)

cm_train = confusion_matrix(y_train, y_pred_train)
cm_test = confusion_matrix(y_test, y_pred_test)

ConfusionMatrixDisplay(cm_train).plot()
plt.title("Matrica zabune - Skup za učenje")
plt.show()

ConfusionMatrixDisplay(cm_test).plot()
plt.title("Matrica zabune - Testni skup")
plt.show()
