import pandas as pd


FEATURES = [
    "elevasi (m)",
    "curah_hujan (mm/hari)",
    "pasang_tertinggi (m)",
    "gelombang_tertinggi (m)",
    "jarak_pantai (km)"
]


def predict_flood(model, data):
    input_data = pd.DataFrame(
        [[
            data.elevasi,
            data.curah_hujan,
            data.pasang_tertinggi,
            data.gelombang_tertinggi,
            data.jarak_pantai
        ]],
        columns=FEATURES
    )

    prediction = int(model.predict(input_data)[0])

    if prediction == 1:
        status = "Banjir rob terindikasi"
    else:
        status = "Tidak terindikasi banjir rob"

    probability = None

    if hasattr(model, "predict_proba"):
        probabilities = model.predict_proba(input_data)[0]
        class_labels = list(model.classes_)

        if 1 in class_labels:
            index = class_labels.index(1)
            probability = float(probabilities[index])

    return {
        "prediction": prediction,
        "status": status,
        "probability": probability
    }