
# 📦 Laravel + Vue + Docker – Pastelaria

Aplicação web para gerenciamento de uma Pastelaria fictícia, com frontend em Vue.js, backend Laravel e ambiente em Docker.

---

## 🚀 Como subir o projeto

### Pré-requisitos

- [Docker](https://www.docker.com/) e [Docker Compose](https://docs.docker.com/compose/) instalados.

### 1. Subir containers

Execute o comando abaixo para levantar os containers:

```bash
docker-compose up -d
```

A aplicação será acessível em:

- **Frontend (Vue)**: [http://localhost:8080](http://localhost:8080)
- **Backend (Laravel API)**: [http://localhost:8000](http://localhost:8000)

Verifique as portas no seu arquivo `docker-compose.yml` caso use outras.

---

## 🧱 Configuração inicial do backend (Laravel)

### 1. Acessar o container

```bash
docker exec -it pastelaria-api-app bash
```

### 2. Instalar dependências

```bash
composer install
```

### 3. Criar arquivo `.env`

```bash
cp .env.example .env

php artisan key:generate
```

### 4. Criar link simbólico para as imagens funcionarem

```bash
php artisan storage:link
```

### 5. Rodar as migrations e seeders

```bash
php artisan migrate:fresh --seed
```

Isso irá criar as tabelas e inserir dados de exemplo.

---

## 🖼️ Features

- **CRUD para produtos**
- **CRUD para clientes**
- **CRUD para pedidos**
  - Envio de email para pedidos criados

---

## 🔐 Autenticação

- A autenticação é feita utilizando Laravel Sanctum.
- **Primeiro**, o frontend faz uma requisição para obter o `csrf-cookie`.
- **Em seguida**, o login é feito, e a rota `/api/me` retorna os dados do usuário autenticado.

---

## 🧪 Testes unitários

### Executar testes:

```bash
docker-compose exec app php artisan test
```
---

## 🛠️ Scripts úteis

### Limpar cache e permissões

Caso haja algum problema com cache ou permissões, execute os seguintes comandos:

```bash
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan route:clear
docker-compose exec app chmod -R 777 storage bootstrap/cache
```

### Subir containers Docker novamente

Se precisar parar e iniciar novamente os containers, use:

```bash
docker-compose down
docker-compose up -d --build
```

---

## 📬 Contato

Em caso de dúvidas, sugestões ou bugs, abra uma *issue* ou entre em contato.

---
