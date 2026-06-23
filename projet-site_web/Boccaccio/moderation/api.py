# api.py
from fastapi.middleware.cors import CORSMiddleware
from fastapi import FastAPI
import json
import os
from pydantic import BaseModel
from typing import Dict, Any
from bert_moderation import predict as bert_predict

class Message(BaseModel):
    message: str

app = FastAPI()

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # restreins si besoin
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

script_dir = os.path.dirname(__file__)
scoremap_path = os.path.join(script_dir, "score_map.json")

with open(scoremap_path, encoding="utf-8") as f:
    score_map = json.load(f)

STOPWORDS = {
    "the","to","be","are","you","i","me","my","they","them","and",
    "a","of","in","on","for","that","is","it","with","as",
    "je","tu","il","elle","on","nous","vous","ils","elles",
    "le","la","les","un","une","des","de","du","dans","pour",
    "et","en","que","qui","quoi","où","donc","mais","or","ni","car",".","?","!",";",
    ",","*",">","<","=","&","/"
}

# Combinaison: poids pour bert (0..1). Si alpha=0.8 => BERT a 80% d'influence.
ALPHA = float(os.environ.get("MODERATION_ALPHA", "0.8"))

# Normalisation simple pour legacy score: la score_map peut être très variable,
# on normalise avec une petite heuristique (clamp)
def legacy_score_normalized(words):
    score_details = {w: score_map.get(w, 0.0) for w in words}
    raw = sum(score_details.values())
    # normaliser sur une échelle 0..1 avec tanh-like behaviour
    import math
    normalized = (math.tanh(raw) + 1) / 2  # de -1..1 -> 0..1
    return normalized, score_details

@app.post("/check")
async def check_message(msg: Message):
    raw_words = msg.message.lower().split()
    words = [w for w in raw_words if w not in STOPWORDS]

    if not words:
        return {"valid": True, "score": 0}

    # Legacy score
    legacy_norm, details = legacy_score_normalized(words)

    # BERT prediction (prob_hate : 0..1 where 1 = hate)
    bert_out = bert_predict(msg.message)
    bert_prob = bert_out.get("prob_hate", 0.0)

    # On combine: final_prob = alpha * bert_prob + (1-alpha) * legacy_norm
    final_prob = ALPHA * bert_prob + (1.0 - ALPHA) * legacy_norm

    # seuil de décision
    THRESHOLD = float(os.environ.get("MODERATION_THRESHOLD", "0.5"))
    valid = final_prob < THRESHOLD  # si prob de haine < threshold => accepted

    return {
        "valid": bool(valid),
        "final_prob": float(final_prob),
        "bert": bert_out,
        "legacy_norm": float(legacy_norm),
        "details": details
    }
