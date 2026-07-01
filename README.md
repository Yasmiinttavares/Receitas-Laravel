[Documentação.pdf](https://github.com/user-attachments/files/29514427/Documentacao.pdf)

Roteiro da Apresentação — Tarefa Final 
1. Mostrar a VM limpa
ssh univates@177.44.248.48
sudo docker images
sudo docker ps -a
2. Instalar ferramentas e projeto
curl -fsSL https://raw.githubusercontent.com/Yasmiinttavares/Receitas-Laravel/main/scripts/bootstrap.sh | sh
exit
ssh univates@177.44.248.48

cd ~/Receitas-Laravel
(O script instala Git, Docker e Docker Compose, inicia o Docker e clona o projeto)

3. Criar Homologação
./scripts/deploy.sh homolog
sudo docker compose -f docker-compose.homolog.yml ps
Acessar http://177.44.248.48:8080
Estado atual do banco:
sudo docker compose -f docker-compose.homolog.yml exec db psql -U receitas_user -d receitas_homolog -c "\dt"
sudo docker compose -f docker-compose.homolog.yml exec db psql -U receitas_user -d receitas_homolog -c "SELECT COUNT(*) FROM receitas;"

4. Criar Produção
./scripts/deploy.sh prod
sudo docker compose -f docker-compose.prod.yml ps
Acessar http://177.44.248.48:8081
Estado atual do banco:
sudo docker compose -f docker-compose.prod.yml exec db psql -U receitas_user -d receitas_prod -c "\dt"
sudo docker compose -f docker-compose.prod.yml exec db psql -U receitas_user -d receitas_prod -c "SELECT COUNT(*) FROM receitas;"
5. Registrar a mudança solicitada 

*************
6. Implementar código e banco
VS Code:
git switch homolog
git pull origin homolog
1.	Alterar o campo/label solicitado no arquivo .blade.php indicado
2.	Criar nova migration: php artisan make:migration create_NOME_DA_TABELA_table
3.	A migration deve conter apenas Schema::create

Exemplo de migration:
Schema::create('categorias', function (Blueprint $table) {
    $table->id();
    $table->string('nome');
    $table->timestamps();
});

7. Testar, versionar e integrar
php artisan test
git add .
git commit -m "feat: altera campo e adiciona tabela solicitada"
git push origin homolog
Mostrar o GitHub Actions executando automaticamente:
•	20 testes automatizados e suas estatísticas
•	Laravel Pint para qualidade de código
•	Build da imagem Docker
Após o CI aprovado, o GitHub Actions abre o Pull Request de homolog para main. Não fazer o merge ainda.




8. Atualizar e validar Homologação
Na VM:
cd ~/Receitas-Laravel
./scripts/deploy.sh homolog

 Banco nova tabela
sudo docker compose -f docker-compose.homolog.yml exec db psql -U receitas_user -d receitas_homolog -c "\dt"
sudo docker compose -f docker-compose.homolog.yml exec db psql -U receitas_user -d receitas_homolog -c "SELECT COUNT(*) FROM receitas;"

9. Aprovar e promover Produção

./scripts/deploy.sh prod
Alterações
sudo docker compose -f docker-compose.prod.yml exec db psql -U receitas_user -d receitas_prod -c "\dt"
sudo docker compose -f docker-compose.prod.yml exec db psql -U receitas_user -d receitas_prod -c "SELECT COUNT(*) FROM receitas;"
