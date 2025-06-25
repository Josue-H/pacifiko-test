README

Nombre del proyecto: pacifiko-test

Este repositorio contiene el desarrollo de una prueba técnica que consta de los siguientes módulos:

Módulo 1: API RESTful de productos y compras desarrollada en Laravel.

Módulo 2: Interfaz web para simular compras y visualizar historial (Vue.js + Inertia).

Módulo 3: Análisis de datos a través de un script en Python y visualización mediante un dashboard.

REQUISITOS DEL SISTEMA

Para poder correr el proyecto correctamente es necesario contar con lo siguiente:

REQUISITOS GENERALES

PHP >= 8.1

Composer

Node.js y npm

MySQL

Python >= 3.9

pip

MongoDB Compass (para conexión remota)

MongoDB Atlas (ya configurado con una base remota)

Se recomienda usar uno de los siguientes entornos de desarrollo local:

Laragon (recomendado para Windows, configurado automáticamente)

XAMPP

WAMP

Asegúrese de que los servicios de MySQL y PHP estén corriendo antes de ejecutar el proyecto.

INSTALACIÓN Y CONFIGURACIÓN DE LARAVEL + INERTIA + VUE

Si estás usando Laragon, WAMP u otro entorno local similar, asegúrate de clonar el proyecto dentro del 
directorio adecuado (normalmente "www") para que el servidor pueda encontrarlo sin perderse.
Por ejemplo para Laragon
cd C:\laragon\www
git clone https://github.com/tu-usuario/pacifiko-test.git
cd pacifiko-test

Instalar las dependencias de Laravel
composer install

Instalar las dependencias de Vue y Vite
npm install

Copiar el archivo .env
cp .env.example .env

Generar la clave de la aplicación
php artisan key:generate

Configurar el archivo .env con los valores de base de datos y URL
El archivo .env será enviado por correo con los valores necesarios para facilitar su ejecución.

Ejecutar las migraciones y los seeders
php artisan migrate --seed

Iniciar el servidor de desarrollo de Laravel
php artisan serve

Iniciar el servidor de Vite para Vue
npm run dev

Acceder a la aplicación desde el navegador
La URL predeterminada es http://localhost:8000 Sí se usa Laragon es la siguiente http://pacifiko-test.test

INSTALACIÓN Y CONFIGURACIÓN DEL SCRIPT DE ANÁLISIS DE DATOS (PYTHON)

Acceder al directorio de análisis
cd data_analysis

Crear un entorno virtual de Python
python -m venv venv
En Linux/Mac: source venv/bin/activate
En Windows: venv\Scripts\activate

Instalar las dependencias necesarias
pip install -r requirements.txt

El archivo requirements.txt contiene:

python-dotenv
requests
pymongo
streamlit

Crear un archivo .env en el directorio data_analysis con las siguientes variables

API_URL=http://pacifiko-test.test/api/compras
MONGO_URI=conexion_al_cluster_de_mongodb
MONGO_DB=nombre_de_base_de_datos
MONGO_COLLECTION=nombre_de_coleccion

Este archivo .env será proporcionado por separado.

Ejecutar el script de análisis para insertar o actualizar estadísticas
python insert_data.py

Este script obtiene las compras desde la API de Laravel, calcula:

Producto más vendido

Total de ingresos por día
y almacena esta información en MongoDB usando upsert para evitar duplicaciones.

Ejecutar el dashboard de visualización
streamlit run dashboard.py

Esto abrirá una pestaña del navegador con un panel de control que permite ver las estadísticas almacenadas.


RECOMENDACIONES

Es importante ejecutar correctamente los seeders y migraciones para que haya datos de prueba.

El script de Python usa variables de entorno para manejar credenciales de forma segura.

MongoDB puede ser configurado usando Atlas (opción recomendada) o instalado localmente.

El usuario de MongoDB utilizado por el script puede ser de solo lectura si se desea dar acceso al evaluador para verificar los datos insertados.

OBSERVACIONES SOBRE EL SCRIPT DE ANÁLISIS

El script usa upsert para evitar duplicaciones en la colección de MongoDB.

Cada ejecución actualiza las estadísticas, no las multiplica.

El dashboard permite visualizar en tiempo real las estadísticas generadas.

Se utiliza MongoDB por su flexibilidad para almacenar documentos y facilitar la construcción de dashboards.

¿QUÉ HACE CADA MÓDULO?

MÓDULO 1 – API de productos y compras

GET /api/productos: listar todos los productos

POST /api/compras: registrar una compra con productos

GET /api/compras: consultar compras por fecha, rango o producto

MÓDULO 2 – Interfaz web

Página de productos: permite seleccionar productos y simular una compra

Página de historial: permite aplicar filtros por fecha y producto y muestra el detalle de cada compra

MÓDULO 3 – Análisis de datos

Script en Python que consume las compras y genera estadísticas

Inserción de datos en MongoDB

Dashboard con visualización gráfica simple a través de Streamlit


Estructura general del proyecto

El proyecto está dividido en dos partes principales:

Aplicación Laravel (backend + frontend con Vue e Inertia)
Aquí se encuentra todo el código relacionado con la API, la lógica de negocio y la interfaz web. Los componentes de Vue están organizados dentro de resources/js/Pages, mientras que las rutas están definidas en routes/web.php y routes/api.php. Las migraciones, modelos y seeders están ubicados dentro de database/.

Análisis de datos (Python)
En la carpeta data_analysis se encuentra el script de Python que se conecta con la API para obtener los datos de compras, calcular estadísticas y almacenarlas en MongoDB. También incluye un pequeño dashboard web hecho con Streamlit para visualizar estas estadísticas de forma simple.



Cómo probar

1. Ingresar a la app en http://pacifiko-test.test o http://localhost:8000
2. Simular una compra desde la página de productos.
3. Ir al historial para visualizar las compras realizadas.
4. Ejecutar el script de análisis con `python insert_data.py`
5. Ver el panel de estadísticas con `streamlit run dashboard.py`
