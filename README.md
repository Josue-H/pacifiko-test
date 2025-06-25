Requisitos
Docker y Docker Compose instalados y funcionales.


Clonación del repositorio

Clonar este repositorio en local:
[text](https://github.com/Josue-H/pacifiko-test.git)

Abrir el directorio:
cd pacifiko-test

Archivos de configuración
Los archivos .env necesarios ya fueron enviados al correo del evaluador. Asegúrese de colocarlos en:

.env en la raíz del proyecto (Laravel)
.env dentro de data_analysis/ (conexión a MongoDB)



Levantar el entorno
En la raíz del proyecto, ejecutar:

docker-compose up --build

Esto iniciará automáticamente los siguientes servicios:

Laravel (backend y API)
Node.js (compilación de frontend con Vite)
Python + Streamlit (dashboard de análisis)
MySQL (base de datos relacional)

Cabe recalcar que los comandos docker exec -it se deben ejecutar en una terminal diferente que en la que se ejecutó el comando:  docker-compose up --build

Comandos necesarios después de iniciar
Una vez estén levantados los contenedores, ejecutar:

docker exec -it laravel_app composer install

docker exec -it laravel_app php artisan migrate --seed

docker exec -it laravel_app php artisan key:generate


Scripts de análisis (Python + Streamlit)
Los scripts se encuentran en la carpeta data_analysis.
Se realiza la inserción y un pequeño dashboard al ejecutar el siguiente comando:

docker exec -it python_scripts python insert_data.py

Accesos
Servicio	    URL
Laravel	        http://localhost:8010
Vite frontend	http://localhost:5173
Streamlit	    http://localhost:8501 (luego de ejecutado el script para análisis de datos)
MySQL	        Puerto externo: 3307(para no generar problemas con el puerto 3306)


Detener y limpiar
Para detener todo y limpiar los volúmenes:

docker-compose down -v
