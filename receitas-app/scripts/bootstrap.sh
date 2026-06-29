#!/bin/sh
set -e

echo "==> Atualizando pacotes..."
sudo apt-get update -y

echo "==> Instalando dependências..."
sudo apt-get install -y ca-certificates curl gnupg lsb-release git

echo "==> Instalando Docker..."
sudo install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
sudo chmod a+r /etc/apt/keyrings/docker.gpg

echo \
  "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
  $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

sudo apt-get update -y
sudo apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

echo "==> Iniciando Docker..."
sudo systemctl enable docker
sudo systemctl start docker
sudo usermod -aG docker $USER

echo "==> Clonando projeto..."
if [ -d "$HOME/Receitas-Laravel" ]; then
  echo "Projeto já existe, atualizando..."
  cd "$HOME/Receitas-Laravel"
  git pull origin main
else
  git clone https://github.com/Yasmiinttavares/Receitas-Laravel.git "$HOME/Receitas-Laravel"
  cd "$HOME/Receitas-Laravel"
fi

echo ""
echo "==> Bootstrap concluído!"
echo "Execute: cd ~/Receitas-Laravel"
echo "Depois: ./scripts/deploy.sh homolog"
