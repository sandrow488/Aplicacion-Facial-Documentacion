<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificación Facial WebRTC</title>
    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;
            padding: 40px 20px;
            background-color: #f9fafb;
            color: #1f2937;
            max-width: 680px;
            margin: 0 auto;
            line-height: 1.6;
        }
        h2 { text-align: center; color: #111827; margin-bottom: 30px; }
        .error {
            color: #991b1b;
            background-color: #fef2f2;
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #fecaca;
        }
        .success {
            color: #166534;
            background-color: #f0fdf4;
            padding: 16px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-size: 1.1em;
            border: 1px solid #bbf7d0;
            text-align: center;
        }
        .panel-resultado {
            background: white;
            border: 1px solid #e5e7eb;
            padding: 24px;
            margin-bottom: 24px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .panel-resultado h3 { margin-top: 0; color: #374151; font-size: 1.25em; }
        #video {
            width: 100%;
            max-width: 640px;
            border: none;
            border-radius: 12px;
            margin-bottom: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            background-color: #000;
        }
        form {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        }
        button, #btn-capturar {
            width: 100%;
            background-color: #3b82f6 !important;
            color: white !important;
            border: none !important;
            border-radius: 8px !important;
            font-weight: 500 !important;
            padding: 12px 20px !important;
            margin-top: 10px;
            transition: background-color 0.2s;
        }
        button:hover, #btn-capturar:hover {
            background-color: #2563eb !important;
        }
        button:disabled, #btn-capturar:disabled {
            background-color: #93c5fd !important;
            cursor: not-allowed !important;
        }
        input[type="file"] {
            display: block;
            width: 100%;
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background: #f9fafb;
            color: #4b5563;
        }
    </style>
</head>
<body>
    <h2>Verificación Facial WebRTC</h2>

    @if($errors->any())
        <div class="error">
            <strong>¡Ups! Ha ocurrido un problema:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(isset($resultado))
        <div class="panel-resultado">
            <h3>Resultado de la Inteligencia Artificial:</h3>
            @if(isset($resultado['verified']) && $resultado['verified'])
                <div class="success">✅ ¡Identidad Verificada Correctamente!</div>
            @else
                <div class="error">❌ Identidad NO verificada (No coinciden).</div>
            @endif

            <p><strong>Nivel de diferencia (Distancia):</strong> {{ $resultado['distance'] ?? 'N/A' }}</p>
            <p><strong>Modelo usado:</strong> {{ $resultado['model'] ?? 'N/A' }}</p>
        </div>
    @endif

    <video id="video" width="640" height="480" autoplay></video>
    <canvas id="canvas" width="640" height="480" style="display:none;"></canvas>

    <form id="formulario-facial" method="POST" action="/test-facial" enctype="multipart/form-data">
        @csrf
        <div style="margin-bottom: 15px;">
            <label><strong>1. Sube tu foto de registro (DNI/Perfil):</strong></label><br>
            <input type="file" name="foto_registro" required>
        </div>

        <input type="file" id="foto_webcam" name="foto_webcam" style="display:none;">

        <button type="button" id="btn-capturar" style="padding: 10px 20px; font-size: 1.1em; cursor: pointer;">
            Capturar y Verificar
        </button>
    </form>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const btnCapturar = document.getElementById('btn-capturar');
        const inputWebcam = document.getElementById('foto_webcam');
        const formulario = document.getElementById('formulario-facial');

        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => { video.srcObject = stream; })
            .catch(err => alert("Error al acceder a la cámara: " + err));

        btnCapturar.addEventListener('click', () => {
            btnCapturar.innerText = " Procesando en Docker... ¡Espera!";
            btnCapturar.disabled = true;

            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            canvas.toBlob(blob => {
                const file = new File([blob], "webcam_capture.jpg", { type: "image/jpeg" });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                inputWebcam.files = dataTransfer.files;

                formulario.submit();
            }, 'image/jpeg');
        });
    </script>
</body>
</html>
