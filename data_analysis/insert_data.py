import requests
import pymongo
import subprocess
from datetime import datetime
from collections import defaultdict
import os
from dotenv import load_dotenv

load_dotenv()

# Configuración
API_URL = os.getenv("API_URL")
MONGO_URI = os.getenv("MONGO_URI")
DB_NAME = os.getenv("MONGO_DB")
COLLECTION_NAME = os.getenv("MONGO_COLLECTION")

def obtener_compras():
    response = requests.get(API_URL)
    response.raise_for_status()
    return response.json().get("data", [])

def procesar_estadisticas(compras):
    producto_count = defaultdict(int)
    ingresos_por_dia = defaultdict(float)

    for compra in compras:
        fecha = compra["created_at"][:10]
        ingresos_por_dia[fecha] += float(compra["total"])

        for detalle in compra["detalles"]:
            producto = detalle["producto"]["nombre"]
            producto_count[producto] += detalle["cantidad"]

    producto_mas_vendido = max(producto_count, key=producto_count.get, default="N/A")

    return {
        "fecha_actualizacion": datetime.utcnow(),
        "producto_mas_vendido": producto_mas_vendido,
        "productos_vendidos": dict(producto_count),
        "ingresos_por_dia": dict(ingresos_por_dia)
    }

def guardar_en_mongo(stats):
    cliente = pymongo.MongoClient(MONGO_URI)
    db = cliente[DB_NAME]
    coleccion = db[COLLECTION_NAME]

    coleccion.update_one(
        {"_id": "resumen"},
        {"$set": stats},
        upsert=True
    )

if __name__ == "__main__":
    try:
        print("Obteniendo compras del API")
        compras = obtener_compras()

        print("Procesando estadísticas")
        stats = procesar_estadisticas(compras)

        print("Guardando estadísticas en MongoDB")
        guardar_en_mongo(stats)

        print("Ejecutando dashboard de Streamlit")
        subprocess.run(["streamlit", "run", "dashboard.py"])

    except Exception as e:
        print("Error:", e)
