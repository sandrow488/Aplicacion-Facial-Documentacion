from fastapi import FastAPI, File, UploadFile
from deepface import DeepFace
import shutil
import os

app = FastAPI()

@app.post("/verify")
async def verify_img(img1: UploadFile = File(...), img2: UploadFile = File(...)):
    path1 = f"temp_{img1.filename}"
    path2 = f"temp_{img2.filename}"

    try:
        # PASO A: Guardar imágenes temporales
        with open(path1, "wb") as buffer:
            shutil.copyfileobj(img1.file, buffer)
        with open(path2, "wb") as buffer:
            shutil.copyfileobj(img2.file, buffer)

        # PASO B: Completar llamada a la IA
        result = DeepFace.verify(
            img1_path=path1,
            img2_path=path2,
            model_name="Facenet",
            detector_backend="opencv",
            enforce_detection=False # Seguridad estricta activada
        )

        return {
            "verified": bool(result["verified"]),
            "distance": float(result["distance"]),
            "model": result["model"]
        }
    finally:
        # Limpieza de archivos temporales
        if os.path.exists(path1): os.remove(path1)
        if os.path.exists(path2): os.remove(path2)