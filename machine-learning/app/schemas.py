from pydantic import BaseModel, Field

class PredictionInput(BaseModel):
    elevasi: float = Field(...)
    curah_hujan: float = Field(...)
    pasang_tertinggi: float = Field(...)
    gelombang_tertinggi: float = Field(...)
    jarak_pantai: float = Field(...)


class PredictionOutput(BaseModel):
    prediction: int
    status: str
    probability: float