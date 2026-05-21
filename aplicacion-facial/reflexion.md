# Reflexión — DevOps 2026

## 1. ¿Qué problema resuelve el CI/CD respecto al despliegue manual?

Antes del CI/CD, cada cambio requería conectarse por SSH al servidor,
copiar ficheros manualmente y reiniciar los servicios. Un descuido podía
romper el sitio en producción. Con Jenkins automatizando el pipeline,
el proceso es siempre igual: si el código tiene errores, el pipeline
se detiene y el servidor nunca recibe código roto.

## 2. ¿Por qué usamos Jenkins y no GitHub Actions para el backend?

GitHub Actions ejecuta los pipelines en servidores de GitHub en internet
y no puede acceder a nuestra Raspberry Pi en red local. Jenkins vive
dentro de nuestra red, al lado del servidor, por lo que puede copiar
ficheros y ejecutar comandos directamente en la máquina.

## 3. ¿Qué ventajas aporta una CDN como Cloudflare o ImageKit?

Una CDN sirve el contenido desde el servidor más cercano al usuario,
reduciendo la latencia. Además protege la IP real del servidor,
absorbe ataques DDoS y gestiona HTTPS automáticamente. ImageKit
específicamente optimiza las imágenes en tiempo real convirtiéndolas
a WebP, reduciendo su peso entre un 30-40% sin pérdida de calidad visible.
