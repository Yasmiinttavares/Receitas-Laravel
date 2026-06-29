# Roteiro da Apresentação — Tarefa Final GCS 2026/A

Este roteiro segue a ordem de validação do enunciado. A mudança de label/campo e a nova tabela devem ser definidas e implementadas somente no dia da apresentação.

## Antes da apresentação

- Confirmar que o repositório está público: `https://github.com/Yasmiinttavares/Receitas-Laravel`
- Confirmar que as branches `main` e `homolog` estão sincronizadas
- Confirmar acesso SSH à VM: `ssh univates@177.44.248.48`
- Deixar abertas duas abas: **GitHub Actions** e **GitHub Issues**
- Confirmar que não há imagens ou containers da aplicação na VM
- Se o professor encontrar algo antigo, remover somente quando ele solicitar

---

## 1. Mostrar a VM limpa

```bash
ssh univates@177.44.248.48
docker images
docker ps -a
```

Resultado esperado: nenhuma imagem e nenhum container da aplicação.

---

## 2. Instalar ferramentas e projeto automaticamente

Executar um único bootstrap:

```bash
curl -fsSL https://raw.githubusercontent.com/Yasmiinttavares/Receitas-Laravel/main/scripts/bootstrap.sh | sh
```

Após o bootstrap, reconectar ao SSH (necessário para aplicar permissão do Docker):

```bash
exit
ssh univates@177.44.248.48
cd ~/Receitas-Laravel
```

O script instala Git, Docker e Docker Compose, inicia o Docker e clona o projeto.

---

## 3. Criar Homologação

```bash
./scripts/deploy.sh homolog
sudo docker compose -f docker-compose.homolog.yml ps
```

Acessar `http://177.44.248.48:8080`, fazer login, listar receitas e cadastrar uma receita.

Antes das mudanças, registrar o estado atual dos bancos:

```bash
sudo docker compose -f docker-compose.homolog.yml exec db psql -U receitas_user -d receitas_homolog -c "\dt"
sudo docker compose -f docker-compose.homolog.yml exec db psql -U receitas_user -d receitas_homolog -c "SELECT COUNT(*) FROM receitas;"
```

---

## 4. Criar Produção

```bash
./scripts/deploy.sh prod
sudo docker compose -f docker-compose.prod.yml ps
```

Acessar `http://177.44.248.48:8081`, fazer login, listar receitas e cadastrar outra receita.

```bash
sudo docker compose -f docker-compose.prod.yml exec db psql -U receitas_user -d receitas_prod -c "\dt"
sudo docker compose -f docker-compose.prod.yml exec db psql -U receitas_user -d receitas_prod -c "SELECT COUNT(*) FROM receitas;"
```

---

## 5. Registrar a mudança

Criar uma **GitHub Issue** com o título: `Alterar campo e criar tabela solicitada`

Descrição sugerida:

```text
Alterar o campo indicado pelo professor de [TEXTO ATUAL] para [NOVO TEXTO].
Criar a tabela [NOME DA TABELA] por migration, sem apagar tabelas ou dados existentes.
Validar primeiro em Homologação e depois promover o mesmo commit para Produção.
```

---

## 6. Implementar código e banco

No computador de desenvolvimento (VS Code):

```bash
git switch homolog
git pull origin homolog
```

1. Alterar o campo/label solicitado no arquivo `.blade.php` indicado
2. Criar nova migration: `php artisan make:migration create_NOME_DA_TABELA_table`
3. A migration deve conter apenas `Schema::create(...)` — não apagar tabelas ou dados existentes

Exemplo de migration:

```php
Schema::create('categorias', function (Blueprint $table) {
    $table->id();
    $table->string('nome');
    $table->timestamps();
});
```

---

## 7. Testar, versionar e integrar

```bash
php artisan test
git add .
git commit -m "feat: altera campo e adiciona tabela solicitada"
git push origin homolog
```

Mostrar o **GitHub Actions** executando automaticamente:

- 20 testes automatizados e suas estatísticas
- Laravel Pint para qualidade de código
- Build da imagem Docker

Após o CI aprovado, o GitHub Actions abre o Pull Request de `homolog` para `main`. **Não fazer o merge ainda.**

---

## 8. Atualizar e validar Homologação

Na VM:

```bash
cd ~/Receitas-Laravel
./scripts/deploy.sh homolog
```

Na aba de Homologação, atualizar a página e mostrar o campo alterado. Depois:

```bash
sudo docker compose -f docker-compose.homolog.yml exec db psql -U receitas_user -d receitas_homolog -c "\dt"
sudo docker compose -f docker-compose.homolog.yml exec db psql -U receitas_user -d receitas_homolog -c "SELECT COUNT(*) FROM receitas;"
```

Confirmar que a nova tabela apareceu e que os dados anteriores foram preservados.

---

## 9. Aprovar e promover Produção

Após validação em Homologação, fazer o **merge manual do Pull Request** para `main`. Então:

```bash
./scripts/deploy.sh prod
```

Na aba de Produção, atualizar a página e mostrar o campo alterado. Depois:

```bash
sudo docker compose -f docker-compose.prod.yml exec db psql -U receitas_user -d receitas_prod -c "\dt"
sudo docker compose -f docker-compose.prod.yml exec db psql -U receitas_user -d receitas_prod -c "SELECT COUNT(*) FROM receitas;"
```

Confirmar a nova tabela e os dados antigos preservados.

---

## Checklist Final

- [ ] VM inicialmente sem imagens e containers
- [ ] Ferramentas e projeto instalados por script (bootstrap.sh)
- [ ] Homologação e Produção em containers separados
- [ ] Login, listagem e cadastro de receitas funcionando nos dois ambientes
- [ ] Mudança registrada em GitHub Issue
- [ ] Campo e migration implementados ao vivo
- [ ] Commit e push realizados na branch `homolog`
- [ ] 20 testes, qualidade (Pint) e build aprovados no GitHub Actions
- [ ] Homologação atualizada primeiro
- [ ] Pull Request mesclado manualmente somente após validação em Homologação
- [ ] Produção promovida a partir da `main`
- [ ] Nova tabela adicionada sem perder dados existentes
