import streamlit as st
import pymongo
import pandas as pd
import os
from dotenv import load_dotenv

load_dotenv()

API_URL = os.getenv("API_URL")
MONGO_URI = os.getenv("MONGO_URI")
DB_NAME = os.getenv("MONGO_DB")
COLLECTION_NAME = os.getenv("MONGO_COLLECTION")

# Conectar con Mongo
cliente = pymongo.MongoClient(MONGO_URI)
db = cliente[DB_NAME]
coleccion = db[COLLECTION_NAME]

data = coleccion.find_one({"_id": "resumen"})

st.title("Dashboard de Compras")
st.caption("Generado con datos desde el backend y sincronizado a MongoDB")

if not data:
    st.warning("No se encontraron estadísticas.")
else:
    st.metric("Producto más vendido", data.get("producto_mas_vendido", "N/A"))

    st.subheader("Ingresos por día")
    df_ingresos = pd.DataFrame(list(data["ingresos_por_dia"].items()), columns=["Fecha", "Total"])
    df_ingresos["Fecha"] = pd.to_datetime(df_ingresos["Fecha"])
    df_ingresos = df_ingresos.sort_values("Fecha")
    st.line_chart(df_ingresos.set_index("Fecha"))

    st.subheader("Productos vendidos")
    df_productos = pd.DataFrame(list(data["productos_vendidos"].items()), columns=["Producto", "Cantidad"])
    st.dataframe(df_productos)
