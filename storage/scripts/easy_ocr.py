import sys
import json
import re
import cv2

import easyocr

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

    a = 0

    philhealth_id = "philhealth"
    drivers_license_id = "transportation"
    national_id = "Pambansang Pagkakakilanlan"
    postal_id = "Postal Identity Card"
    umid = "Unified Multi-Purpose ID"
    passport_id = "pasaporte"
    tin_id = "internal revenue"

    is_philhealth_id = False
    is_drivers_id = False
    is_national_id = False
    is_postal_id = False
    is_umid = False
    is_passport = False
    is_tin_id = False

    is_surname = False
    is_fname = False
    is_mname = False
    is_name = False

    philhealth_name = False

    lname = ""
    fname = ""
    mname = ""
    name = ""

    cont = True

    result = {}

    # processed = preprocess_image(image_path)

    # Create an EasyOCR reader
    reader = easyocr.Reader(['en'], gpu=False)  # 'en' for English

    # Run OCR on an image
    results = reader.readtext(image_path)  # Set gpu=True if you have a compatible GPU

    for (bbox, text, prob) in results:
        # print(f"Detected text: {text} (Confidence: {prob:.2f})")
        # Case-insensitive search
        if re.search(philhealth_id, text, re.IGNORECASE):
            is_philhealth_id = True
        if re.search(drivers_license_id, text, re.IGNORECASE):
            is_drivers_id = True
        if re.search(national_id, text, re.IGNORECASE):
            is_national_id = True
        if re.search(postal_id, text, re.IGNORECASE):
            is_postal_id = True
        if re.search(umid, text, re.IGNORECASE):
            is_umid = True
        if re.search(passport_id, text, re.IGNORECASE):
            is_passport = True
        if re.search(tin_id, text, re.IGNORECASE):
            is_tin_id = True

        if is_tin_id:
            if re.search('name', text, re.IGNORECASE):
                # print("Found name: " + text)
                is_name = True
            if is_name and not re.search('name', text, re.IGNORECASE) and len(text) != 1:
                if "," in text:
                    name = text.split(",")
                    text = name[1] + " " + name[0]
                text.upper()
                result = {
                    "status": "success",
                    "name": text,
                    "filename": filename,
                    "id_type":  tin_id
                }
                is_name = False
        if is_philhealth_id:
            if text[2] == "-":
                philhealth_name = True
            if philhealth_name and text[2] != "-" and text[11] != "-":
                if "," in text: 
                    name = text.split(",")
                    text = name[1] + " " + name[0]
                text.upper()
                result = {
                    "status": "success",
                    "name": text,
                    "filename": filename,
                    "id_type":  philhealth_id
                }
                philhealth_name = False
        if is_drivers_id:
            if re.search('last name', text, re.IGNORECASE):
                is_name = True
                # print("Found last name 1: " + text)
            if is_name and not re.search('last name', text, re.IGNORECASE):
                # print("Found last name 2: " + text)
                if "," in text:
                    name = text.split(",")
                    text = name[1] + " " + name[0]
                text.upper()
                result = {
                    "status": "success",
                    "name": text,
                    "filename": filename,
                    "id_type":  drivers_license_id
                }
                is_name = False
        if is_national_id:
            if a == 6:
                lname = text
            if a == 8:
                fname = text
            if a == 10:
                mname = text
            text = fname + " " + mname + " " + lname
            text.upper()
            result = {
                "status": "success",
                "name": text,
                "filename": filename,
                "id_type":  national_id
            }
        if is_postal_id and a >= 5 and text != "Address" and cont:
            name += text + " "
            name = name.upper()
            result = {            
                "status": "success",
                "name": name,
                "filename": filename,
                "id_type":  postal_id
            }
        elif is_postal_id and text == "Address":
            cont = False
        if is_umid:
            if text.lower() == 'surname':
                is_surname = True
            if is_surname and text.lower() != 'surname':
                lname = text
                is_surname = False
            if text.lower() == 'given name':
                is_fname = True
            if is_fname and text.lower() != 'given name':
                fname = text
                is_fname = False
            if text.lower() == 'middle name':
                is_mname = True
            if is_mname and text.lower() != 'middle name':
                mname = text
                is_mname = False
            name = fname + " " + mname + " " + lname
            name = name.upper()
            result = {
                "status": "success",
                "name": name,
                "filename": filename,
                "id_type":  umid
            }
        if is_passport:
            if a == 8:
                lname = text
            if a == 9:
                fname = text
            if a == 11:
                mname = text
            name = fname + " " + mname + " " + lname
            name = name.upper()
            result = {
                "status": "success",  
                "name": name,
                "filename": filename,
                "id_type":  passport_id
            }
        if not is_philhealth_id and not is_drivers_id and not is_national_id and not is_postal_id and not is_umid and not is_passport and not is_tin_id:
            result = {
                "status": "error",
                "message": "No text found",
                "filename": filename,
                "id_type":  "none"
            }
        a += 1

    print(json.dumps(result))

filename = sys.argv[1]
# filename = 'tab_passport_2.jpg'

if __name__ == "__main__":
    image_file = "../storage/app/public/" + filename

    # image = cv2.imread(image_file)
    # h, w, _ = image.shape
    # x1, y1 = int(w*0.2), int(h*0.4)
    # x2, y2 = int(w*0.8), int(h*0.6)

    # roi = image[y1:y2, x1:x2]
    # cv2.imwrite("../storage/app/public/cropped_id.png", roi)

    run_easyocr(image_file)
    sys.exit()
