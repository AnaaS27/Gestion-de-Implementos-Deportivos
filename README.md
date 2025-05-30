# Gestión de Implementos Deportivos - Despliegue con Docker y CI/CD

# Objetivo

Implementar un sistema web para la gestión de implementos deportivos, desplegado con Docker y automatizado con GitHub Actions. Este proyecto hace parte del módulo "Despliegue de Aplicaciones Web con Docker y CI/CD".

# Integrantes

- Ana Sofia Chocue  
- Danna Camila Flórez  
- Leidy Johanna Vareño  
- Oliver Camilo Bueno  
- Daniel Felipe Hurtado  
- Fabián Esteban Galvis  

# Tecnologías Utilizadas

- *Frontend*: HTML, PHP, CSS, Bootstrap  
- *Backend*: PHP 8.2 con Apache  
- *Base de datos*: MySQL 8.0  
- *Contenerización*: Docker, Docker Compose  
- *CI/CD*: GitHub Actions

# Despliegue Local

# 1. Clonar el repositorio

git clone https://github.com/AnaaS27/Gestion-de-Implementos-Deportivos.git
cd Gestion-de-Implementos-Deportivos

# 2. Construir y levantar los contenedores

docker compose up -d --build

# 3. Acceder a la aplicación

Abre tu navegador y visita:
http://localhost:8080


# Configuración de la Base de Datos

Host: db
Usuario: usuario
Contraseña: clave123
Base de datos: gestion_deportiva

La base de datos se inicializa automáticamente al levantar los contenedores, gracias al script init.sql.

# Pipeline CI/CD

Este repositorio incluye un pipeline de integración continua utilizando GitHub Actions ubicado en .github/workflows/deploy.yml, que:

Verifica el código PHP

Puede extenderse para construir imágenes y hacer despliegue automatizado

# Notas Finales

Las credenciales están definidas como variables de entorno en docker-compose.yml.
El entorno fue probado en Ubuntu con Docker Desktop.
El pipeline de GitHub Actions se ejecuta automáticamente al hacer push.