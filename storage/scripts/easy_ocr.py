import sys
import json
import re
import cv2
import easyocr
import spacy

def to_camel_case_with_spaces(text):
    return ' '.join(word.capitalize() for word in text.split())

def spacify(text):
    nlp = spacy.load("en_core_web_lg")
    # doc = nlp(text)
    # lemmas = [token.lemma_ for token in doc]
    # print("Lemmas:", lemmas)
    # poses = [(token.pos_, token.lemma_) for token in doc]
    # print("poses:", poses)
    # for token in doc.ents:
    #     print(token.text, token.label_)
    # Extract names
    # text = "REpubiC OF The Philippines PhilHealth T PMtm iMa Philippine Health Insurance Corporation 17-13245678-0 JUAN DELA CRUZ JANUARY 01,2022 MALE PRK SUBDIVISION, BARANGAY, City HERE 1 7 1 3 2 45 6 7  8 0 Siont ute FORMAL ECONOMY"
    # doc = nlp(text)
    text = f"This text contains words from a national id and a name here is: {text}"
    text = to_camel_case_with_spaces(text)
    doc = nlp(text)
    names = [ent.text for ent in doc.ents if ent.label_ == "PERSON"]
    # print(f"Text: {text}")
    # print(f"Extracted Names: {names}")
    # print("-" * 50)
    return names


def preprocess_image(image_path):
    # Load image
    image = cv2.imread(image_path)
    if image is None:
        raise FileNotFoundError(f"Could not read image at {image_path}")
    
    gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
    gray = cv2.resize(gray, None, fx=2, fy=2, interpolation=cv2.INTER_CUBIC)
    # gray = cv2.GaussianBlur(gray, (5, 5), 0)
    thresh = cv2.adaptiveThreshold(gray, 255, cv2.ADAPTIVE_THRESH_GAUSSIAN_C, cv2.THRESH_BINARY, 31, 2)

    return thresh

def run_easyocr(image_path):
    # processed = preprocess_image(image_path)

    # Create an EasyOCR reader
    reader = easyocr.Reader(['en'], gpu=True)  # 'en' for English

    # Run OCR on an image
    arr = reader.readtext(image_path)  # Set gpu=True if you have a compatible GPU
    # print(arr)
    texts = [item[1] for item in arr]
    results = " ".join(texts)
    # print(results)

    result = spacify(results)
    return result

filename = sys.argv[1]
# filename = 'tab_seniors_id_1.jpg'

if __name__ == "__main__":
    image_file = "../storage/app/public/" + filename

    result = run_easyocr(image_file)
    response = {
            "status": "success",
            "result": result,
        }

    print(json.dumps(response))
    sys.exit()
