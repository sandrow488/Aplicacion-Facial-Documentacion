import logging
from datetime import datetime
from fastapi import FastAPI, File, UploadFile
from deepface import DeepFace
import shutil
import os

# Configurar logging estructurado
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s %(name)s %(levelname)s %(message)s'
)
logger = logging.getLogger(__name__)

app = FastAPI()

@app.get("/health")
def health_check():
    logger.info("Health check endpoint called")
    return {
        "status": "ok",
        "message": "API de reconocimiento facial operativa",
        "timestamp": datetime.utcnow().isoformat(),
        "version": "1.0.0"
    }

@app.get("/ready")
def readiness_check():
    # Aquí puedes verificar dependencias (DB, modelos cargados, etc.)
    logger.info("Readiness check endpoint called")
    return {"status": "ready"}

@app.post("/verify")
async def verify_img(img1: UploadFile = File(...), img2: UploadFile = File(...)):
    path1 = f"temp_{img1.filename}"
    path2 = f"temp_{img2.filename}"

    try:

        with open(path1, "wb") as buffer:
            shutil.copyfileobj(img1.file, buffer)
        with open(path2, "wb") as buffer:
            shutil.copyfileobj(img2.file, buffer)


        result = DeepFace.verify(
            img1_path=path1,
            img2_path=path2,
            model_name="Facenet",
            detector_backend="opencv",
            enforce_detection=False 
        )

        return {
            "verified": bool(result["verified"]),
            "distance": float(result["distance"]),
            "model": result["model"]
        }
    finally:
        if os.path.exists(path1): os.remove(path1)
        if os.path.exists(path2): os.remove(path2)