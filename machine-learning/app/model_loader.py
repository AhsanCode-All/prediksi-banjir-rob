import joblib
from pathlib import Path

MODEL_PATH = (
    Path(__file__).resolve().parent.parent
    / "models"
    / "model_random_forest_tuned.pkl"
)


def load_model():
    if not MODEL_PATH.exists():
        raise FileNotFoundError(
            f"File model tidak ditemukan: {MODEL_PATH}"
        )

    model = joblib.load(MODEL_PATH)

    return model