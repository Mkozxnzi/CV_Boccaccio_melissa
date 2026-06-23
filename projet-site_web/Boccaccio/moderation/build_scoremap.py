import os
import pandas as pd
import math
import json
from collections import defaultdict

# Dossier où se trouve ce script
script_dir = os.path.dirname(__file__)

# Chemins robustes
base_dir = os.path.join(script_dir, "..", "sampled_train", "sampled_train")
csv_path = os.path.join(script_dir, "..", "sampled_train", "annotations_metadata.csv")
output_path = os.path.join(script_dir, "score_map.json")

# Charger les annotations
df = pd.read_csv(csv_path)
label_map = dict(zip(df['file_id'], df['label']))

texts = []
labels = []

# Charger les fichiers texte
for filename in os.listdir(base_dir):
    if filename.endswith(".txt"):
        file_id = filename.replace(".txt", "")
        label = label_map.get(file_id)
        if label is not None:
            with open(os.path.join(base_dir, filename), encoding="utf-8") as f:
                texts.append(f.read())
                labels.append(label)

# Calcul IDF
idf = defaultdict(int)
N = len(texts)

for text in texts:
    unique_words = set(text.lower().split())
    for word in unique_words:
        idf[word] += 1

for word in idf:
    idf[word] = math.log(N / (1 + idf[word]))

# Construire ScoreMap
score_map = defaultdict(float)

for text, label in zip(texts, labels):
    words = text.lower().split()
    tf = defaultdict(int)
    for word in words:
        tf[word] += 1
    for word in tf:
        tfidf = (tf[word] / len(words)) * idf[word]
        if label == "hate":
            score_map[word] -= tfidf
        else:
            score_map[word] += tfidf

# Sauvegarder ScoreMap
os.makedirs(script_dir, exist_ok=True)  # sécurité, même si moderation existe déjà
with open(output_path, "w", encoding="utf-8") as f:
    json.dump(score_map, f, indent=2)

print("ScoreMap générée :", output_path)
