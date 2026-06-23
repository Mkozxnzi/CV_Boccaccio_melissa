# bert_moderation.py
from transformers import AutoTokenizer, AutoModelForSequenceClassification, pipeline
import torch
import os
from functools import lru_cache

# Nom du modèle à utiliser (tu peux remplacer par ton modèle fine-tuné local)
MODEL_NAME = os.environ.get("MODERATION_MODEL", "distilbert-base-uncased-finetuned-sst-2-english")

_device = 0 if torch.cuda.is_available() else -1

@lru_cache(maxsize=1)
def get_pipeline():
    """
    Charge et met en cache le pipeline HF. Utilise device GPU si disponible.
    """
    # Charger tokenizer + model via pipeline for convenience
    clf = pipeline("sentiment-analysis", model=MODEL_NAME, tokenizer=MODEL_NAME, device=_device)
    return clf

def predict(text):
    """
    Renvoie un dict { 'prob_hate': float, 'label': str }.
    Interprétation par défaut:
      - si modèle renvoie labels like POSITIVE/NEGATIVE, on mappe NEGATIVE -> prob_hate proche 1
      - si modèle renvoie classes personnalisées, on utilise la probabilité de la classe 'hate' si disponible
    """
    clf = get_pipeline()
    out = clf(text, truncation=True)[0]  # dict: {'label': 'POSITIVE', 'score': 0.98}
    label = out.get("label")
    score = float(out.get("score", 0.0))

    # Heuristique de mapping par défaut 
    if label.upper() in ("NEGATIVE", "TOXIC", "OFFENSIVE", "HATE"):
        prob_hate = score
    elif label.upper() in ("POSITIVE", "NON_TOXIC", "CLEAN"):
        prob_hate = 1.0 - score
    else:
        # Pour les labels inconnus:
        if label.startswith("LABEL_"):
            # si score élevé, on interprète LABEL_1 comme potentiellement haineux par défaut
            prob_hate = score if label.endswith("_1") or label.endswith("1") else (1.0 - score)
        else:
            prob_hate = 1.0 - score

    return {"prob_hate": prob_hate, "label": label, "score": score}
