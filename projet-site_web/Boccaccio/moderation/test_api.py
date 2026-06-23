import requests

url = "http://127.0.0.1:8000/check"

data = {"message": "Je te déteste"}

response = requests.post(url, json=data)  # le paramètre json fait tout correctement
print(response.json())
