from tensorflow.keras.preprocessing import image
import numpy as np
from tensorflow import keras
import matplotlib.pyplot as plt

model = keras.models.load_model("best_model.h5")
img = image.load_img("test.png", color_mode="grayscale", target_size=(28, 28))
img_array = image.img_to_array(img)
img_array = img_array.reshape(1, 28, 28, 1).astype("float32") / 255

prediction = model.predict(img_array)
predicted_class = np.argmax(prediction)

#  prikaz
plt.imshow(img_array[0].reshape(28, 28), cmap='gray')
plt.title(f"Predikcija: {predicted_class}")
plt.axis("off")
plt.show()
