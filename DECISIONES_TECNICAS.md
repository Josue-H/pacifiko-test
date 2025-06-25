1. Framework backend: Laravel
Motivo:
Laravel es un framework maduro, ampliamente adoptado, con excelente soporte para APIs RESTful, validaciones, migraciones y seeders. Su estructura modular y el ecosistema robusto facilitan el desarrollo rápido y mantenible.

Ventajas:

Sistema de rutas separado para API y vistas (api.php y web.php)

Middleware integrado

Eloquent ORM para una interacción clara con base de datos

2. Frontend: Vue 3 con Inertia.js
Motivo:
Se eligió Vue 3 con Inertia.js para evitar el uso de una API tradicional consumida por un frontend separado. Inertia actúa como un “glue” entre Laravel y Vue, permitiendo renderizar componentes como si fueran vistas sin perder la reactividad de un SPA.

Ventajas:

Sin necesidad de crear un API REST extra para el frontend

Transiciones rápidas sin recargas

Estructura limpia y cohesiva

3. Base de datos relacional: MySQL
Motivo:
MySQL es un sistema robusto, compatible con Laravel y fácil de configurar localmente. Permite modelar relaciones entre compras, productos y detalles de forma natural.

Esquema:

Productos

Compras

DetalleCompras (pivot con cantidad, subtotal, etc.)

4. Carga de imágenes y almacenamiento
Motivo:
En vez de guardar imágenes como base64 o almacenarlas externamente, se optó por guardarlas en public/images para que se mantuvieran accesibles sin complicar la configuración.

Ventajas:

Compatible con WAMP, XAMPP y Laragon

URLs relativas, fáciles de mostrar sin necesidad de symlinks

5. Seeders personalizados
Motivo:
Para facilitar pruebas manuales y demostrar la funcionalidad, se incluyeron seeders con:

Productos con imágenes reales

15 compras simuladas con Faker

Fechas distribuidas entre el último mes

Esto permite probar filtros por fecha y producto sin tener que crear registros manuales.

6. Análisis de datos: Python + MongoDB
Motivo:
Para mostrar una solución realista de análisis y reportes, se optó por usar Python para consumir los datos y MongoDB para almacenarlos como documentos de forma flexible.

Ventajas de Python:

Ecosistema de análisis de datos maduro

Fácil integración con requests, pandas, etc.

Streamlit permite crear dashboards web en minutos

Ventajas de MongoDB:

Ideal para estadísticas sin esquema fijo

Soporta operaciones upsert para evitar duplicados

Compatible con MongoDB Atlas (uso en nube)

7. MongoDB Atlas vs Community
Motivo:
Se eligió Atlas para simplificar la evaluación: el evaluador puede conectarse directamente a una instancia en la nube sin necesidad de instalar MongoDB local.

Notas:

Se compartieron credenciales seguras y de solo lectura

Acceso posible mediante MongoDB Compass

8. Configuración por variables de entorno (.env)
Motivo:
Para mantener buenas prácticas de seguridad y adaptabilidad, todas las credenciales sensibles y URLs están definidas en archivos .env, tanto para Laravel como para Python.

9. Decisiones UX/UI
Se usó Bootstrap 5 para una interfaz limpia sin requerir configuración adicional

Toasts y validaciones visuales para brindar feedback al usuario

Historial de compras con filtros opcionales (fecha y producto)


10. Modelado de relaciones: uso de tabla intermedia detalle_compras

Motivo:
Para permitir que una compra pueda contener múltiples productos (y un producto pertenecer a múltiples compras), se optó por utilizar una tabla intermedia detalle_compras en lugar de una relación directa. Esto sigue las buenas prácticas de modelado relacional para relaciones de muchos a muchos con datos adicionales.

Ventajas:

Permite almacenar información detallada de cada línea de la compra: cantidad, precio unitario, subtotal.

Escalable

Evita la redundancia y mantiene la integridad de los datos.

12. Contenedores y entorno: Docker

Motivo:
Se utilizó Docker para unificar el entorno de desarrollo, eliminar diferencias entre máquinas y facilitar la evaluación del proyecto sin necesidad de instalar dependencias manualmente (como PHP, Node, MySQL o Python). Todo está encapsulado en servicios definidos dentro de un docker-compose.yml.

Ventajas:

Entorno completamente aislado y reproducible

Base de datos, servidor web, frontend y analítica corriendo en contenedores separados

No se requiere Laragon, XAMPP ni ningún stack local

Compatible con Linux, MacOS y Windows (con WSL)


En conjunto, estas decisiones permiten construir una solución modular, mantenible, fácil de probar y evaluar. Cada herramienta fue seleccionada no solo por su popularidad, sino por su aporte directo a la eficiencia en desarrollo y claridad del producto final.
