from fastapi import FastAPI, HTTPException

from app.model_loader import load_model
from app.schemas import PredictionInput, PredictionOutput
from app.predictor import predict_flood


app = FastAPI(
    title="API Prediksi Banjir Rob",
    description="API Machine Learning untuk prediksi indikasi banjir rob",
    version="1.0.0"
)


try:
    model = load_model()
except Exception as error:
    model = None
    print(f"Gagal memuat model: {error}")


@app.get("/")
def home():
    return {
        "message": "API Prediksi Banjir Rob berjalan"
    }


@app.get("/health")
def health():
    if model is None:
        raise HTTPException(
            status_code=503,
            detail="Model belum berhasil dimuat"
        )

    return {
        "status": "OK",
        "model": "Random Forest Tuned"
    }


@app.post("/predict", response_model=PredictionOutput)
def predict(data: PredictionInput):
    if model is None:
        raise HTTPException(
            status_code=503,
            detail="Model belum tersedia"
        )

    try:
        result = predict_flood(model, data)
        return result

    except Exception as error:
        raise HTTPException(
            status_code=500,
            detail=f"Terjadi kesalahan prediksi: {str(error)}"
        )