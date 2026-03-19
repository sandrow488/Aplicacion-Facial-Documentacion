# Usamos una imagen ligera de Python
FROM python:3.10-slim

# Directorio de trabajo dentro del contenedor
WORKDIR /app

# Instalamos dependencias del sistema operativo que necesita OpenCV
RUN apt-get update && apt-get install -y \
    libgl1 \
    libglib2.0-0 \
    && rm -rf /var/lib/apt/lists/*

# Copiamos las dependencias de Python y las instalamos
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# Copiamos el resto de nuestro código (el main.py)
COPY . .

# Exponemos el puerto interno 5000
EXPOSE 5000

# Comando para arrancar el servidor FastAPI en el puerto 5000
CMD ["uvicorn", "main:app", "--host", "0.0.0.0", "--port", "5000"]