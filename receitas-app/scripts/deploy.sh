#!/bin/sh
set -e

AMBIENTE=$1

if [ -z "$AMBIENTE" ]; then
  echo "Uso: ./scripts/deploy.sh [homolog|prod]"
  exit 1
fi

if [ "$AMBIENTE" != "homolog" ] && [ "$AMBIENTE" != "prod" ]; then
  echo "Ambiente inválido: $AMBIENTE. Use 'homolog' ou 'prod'."
  exit 1
fi

echo "==> Atualizando código do repositório..."
git pull origin "$AMBIENTE" 2>/dev/null || git pull origin main

echo "==> Subindo ambiente: $AMBIENTE..."
sudo docker compose -f "docker-compose.$AMBIENTE.yml" up -d --build

echo "==> Aguardando banco de dados ficar pronto..."
sleep 5

echo "==> Executando migrations ($AMBIENTE)..."
sudo docker compose -f "docker-compose.$AMBIENTE.yml" exec app php artisan migrate --force

echo ""
echo "==> Deploy de $AMBIENTE concluído!"

if [ "$AMBIENTE" = "homolog" ]; then
  echo "Acesse: http://177.44.248.48:8080"
else
  echo "Acesse: http://177.44.248.48:8081"
fi
