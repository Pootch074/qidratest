import pytesseract
from PIL import Image
import cv2

# If on Windows, specify Tesseract path
# pytesseract.pytesseract.tesseract_cmd = r"C:\Program Files\Tesseract-OCR\tesseract.exe"

# Load image
filename = '1000034073.jpg'

image = cv2.imread('../app/public/' + filename)

# Convert to grayscale for better OCR
gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)

# Optional: Apply thresholding to improve accuracy
# gray = cv2.threshold(gray, 0, 255, cv2.THRESH_BINARY | cv2.THRESH_OTSU)[1]
# thresh = cv2.adaptiveThreshold(gray, 255, cv2.ADAPTIVE_THRESH_GAUSSIAN_C,
#                                cv2.THRESH_BINARY, 31, 2)

# Save the preprocessed image temporarily
cv2.imwrite("processed.png", image)

# Run OCR
text = pytesseract.image_to_string(Image.open("processed.png"), lang="eng")

print("Extracted Text:")
print(text)
