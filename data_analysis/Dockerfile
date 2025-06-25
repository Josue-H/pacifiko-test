FROM python:3.11

WORKDIR /app/data_analysis

COPY requirements.txt .

RUN pip install --no-cache-dir -r requirements.txt

COPY . .

CMD ["streamlit", "run", "dashboard.py"]
