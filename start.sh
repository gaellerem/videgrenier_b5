#!/bin/bash

# Vérifie si un argument est passé
if [ -z "$1" ]; then
  echo "Usage: ./start.sh [dev|prod]"
  exit 1
fi

ENV=$1

# Détermine le chemin selon l'environnement
if [ "$ENV" == "prod" ]; then
  FOLDER="prod"
  PROJECT_NAME="videgrenier-prod"
elif [ "$ENV" == "dev" ]; then
  FOLDER="dev"
  PROJECT_NAME="videgrenier-dev"
else
  echo "Environnement inconnu : $ENV"
  echo "Utilise 'dev' ou 'prod'."
  exit 1
fi

echo "🚀 Lancement de l'environnement $ENV..."

cd "docker/$FOLDER" || {
  echo "❌ Impossible de trouver le dossier docker/$FOLDER"
  exit 1
}

docker compose -p "$PROJECT_NAME" --env-file .env up --build -d