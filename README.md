<div align="center">
      <a href="https://alisonjuliano.com">
        <img src="https://imgur.com/13kinqs.jpg" alt="logo">
    </a>
  <p>
    <p style="font-style: italic;">"I decided to follow the path of rabbit hole, let's see how far this can take us" 
    </p>
    <p style="font-weight: bold;">Sharing, learning and knowing people all around the world. Let's code!</p>
    <a href="https://alisonjuliano.com"> 
    <img src="https://img.shields.io/static/v1?label=Fullstack&message=AJ&color=64ffda&style=for-the-badge&logo=dungeonsanddragons" alt="fullstack">
    </a>
    <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="php">
    <img src="https://img.shields.io/badge/Docker-2CA5E0?style=for-the-badge&logo=docker&logoColor=white" alt="docker">
    <img src="https://img.shields.io/badge/Nginx-009639?style=for-the-badge&logo=nginx&logoColor=white" alt="nginx">
    <img src="https://img.shields.io/badge/Redis-FF4438?style=for-the-badge&logo=redis&logoColor=white" alt="redis">
    <img src="https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white" alt="mysql">
    <img src="https://img.shields.io/badge/TypeScript-2F74C0?style=for-the-badge&logo=typescript&logoColor=white" alt="ts">
    <img src="https://img.shields.io/badge/Javascript-EFD81D?style=for-the-badge&logo=javascript&logoColor=white" alt="js">
    <img src="https://img.shields.io/badge/Vue-4FC08D?style=for-the-badge&logo=vue.js&logoColor=white" alt="vuejs">
    <img src="https://img.shields.io/badge/Tailwind-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="tailwind">
  </p>
</div>

## Sobre o projeto

Este projeto foi desenvolvido para um case de processo seletivo para a empresa Adriano Cobuccio. Ele consiste em construir uma carteira financeira e tem os seguintes objetivos:

Funcionalidades principais

- ✅ Cadastro e autenticação de usuários.
- ✅ Realização de transações financeiras (entrada e saída).
- ✅ Depósito em carteira.
- ✅ Validação de saldo antes das operações.
- ✅ Possibilidade de reversão de transações por inconsistência ou solicitação.

O projeto tambem atende os diferenciais:

- ✅ Uso de Docker.
- ✅ Testes unitários
- ✅ Testes de integração
- ✅ Documentação (README)

Contém também:

- ✅ Gerenciador de estados do VUE.js (Pinia).
- ✅ API para:
  - Buscar transações (`GET /transactions`)
  - Criar nova transação (`POST /transactions`)
  - Atualizar transação (`PUT/PATCH /transactions/{id}`)
- ✅ Tabela UI com as transações de cada usuário.
- ✅ Notificação via email para cada ação de transação.

As tecnologias utilizadas foram:

- **Back-end:** PHP/Laravel, Docker, Nginx, MySQL, Redis.

- **Front-end:** Vue.js, shadcn-vue (UI), TypeScript, JavaScript, Tailwind.

# Requisitos

- [NodeJS/npm](https://nodejs.org/en/download)
- [Composer](https://yarnpkg.com/getting-started/install)
- [Docker/Docker Compose](https://www.docker.com/get-started)
- [WorkOS Setup](https://laravel.com/docs/12.x/starter-kits#workos)
  - Crie uma conta gratuita no [WorkOS](https://workos.com/) e obtenha as credenciais para o seu ambiente (WORKOS_CLIENT_ID e WORKOS_API_KEY). Elas são necessárias (e facilmente encontradas na dashboard inicial da WorkOS) para a autenticação de usuários no sistema.
  - Configure uma URI de redirecionamento no WorkOS. Acesse atraves do menu "Redirects". Em seguida selecione o botão "Add Redirect URI" e adicione a seguinte URL: `http://localhost:80/authenticate`

> **Recomendado:** 
>
> - Gerenciador de banco de dados (PHPMyAdmin, Datagrip, DBeaver, MySQL Workbench, etc)

# Preparando o ambiente

Siga os passos abaixo para configurar e rodar o projeto localmente.:

*Obs: No projeto utilizo o laravel sail, se sinta livre para rodar os comandos docker diretamente caso prefira.*

1. **Clone o Repositório**  
```bash'
    git clone https://github.com/julianoaj/cobbucio-alison-juliano-santos
    cd cobbucio-alison-juliano-santos
```

2. **Configure o arquivo .env**  
```bash'
    cp .env.example .env
```
Edite o arquivo .env e adicione as credenciais do [WorkOS](https://workos.com/) (WORKOS_CLIENT_ID e WORKOS_API_KEY, obtidas no dashboard da [WorkOS](https://workos.com/)). Alem disso será necessário configurar uma URI de redirecionamento dentro do dashboard da WorkOS (siga os passos em [Requisitos](#requisitos)).

3. **Instale as dependências**

```bash
    composer install
    npm install
```

4. **Suba os containers**
```bash'
    ./vendor/bin/sail build
    ./vendor/bin/sail up -d
```

5. **Gere APP_KEY Laravel**

```bash
    ./vendor/bin/sail php artisan key:generate
```

6. **Rode as migrations**

```bash
    ./vendor/bin/sail php artisan migrate
```

7. **Inicialize o front-end**

```bash
    npm run dev
```

## ✅ Testes
Para rodar os testes, utilize o comando:

```bash
    ./vendor/bin/sail pest
```
