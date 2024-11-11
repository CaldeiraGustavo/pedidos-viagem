<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Onfly | Teste técnico PHP

Você precisa desenvolver um microsserviço em Laravel para gerenciar pedidos de viagem corporativa. O microsserviço deve expor uma API REST para as seguintes operações:

1. **Criar um pedido de viagem**: Um pedido deve incluir o ID do pedido, o nome do solicitante, o destino, a data de ida, a data de volta e o status (solicitado, aprovado, cancelado).
2. **Atualizar o status de um pedido de viagem**: Possibilitar a atualização do status para "aprovado" ou "cancelado".
3. **Consultar um pedido de viagem**: Retornar as informações detalhadas de um pedido de viagem com base no ID fornecido.
4. **Listar todos os pedidos de viagem**: Retornar todos os pedidos de viagem cadastrados, com a opção de filtrar por status.

## Parecer sobre dados Técnicos

1. [x] Utilize o framework **Laravel** (versão mais recente possível).
2. [x] A API deve seguir as boas práticas de arquitetura de microsserviços.
3. [x] Utilize um **banco de dados relacional** (MySQL) e forneça uma migração para a estrutura das tabelas necessárias.
4. [x] Implemente **validação** de dados no backend e tratamento de erros apropriado.
5. [x] Escreva **testes automatizados** (preferencialmente com PHPUnit) para as principais funcionalidades.
6. [x] Utilize **Docker** para facilitar a execução do serviço. A aplicação deve poder ser executada via Docker.
7. [x] Implemente **autenticação simples** usando tokens (como JWT) para proteger a API.

## Como instalar
1. Clone o projeto: https://github.com/CaldeiraGustavo/pedidos-viagem.git
2. Navegue até a pasta do projeto: ``` cd pedidos-viagem/ ```
3. Rode os comandos do docker: ```docker-compose build && docker-compose up -d```
4. Crie o arquivo .env ``` cp .env.example .env ```
5. Acesse o container e instale as dependências do projeto: ``` composer install ```
6. Execute o comando: ``` php artisan key:api --name="name" --credential="credential" ```
7. Execute as migrations: ``` php artisan migrate" ```
8. Navegue até localhost/ para acessar a documentação da API

## Informações importantes
- O comando ``` php artisan key:api``` gera um JWT de acesso a API, sem ele não é possível acessar os endpoints;
- Caso queira executar os testes, basta apenas configurar um arquivo .env.testing, rodar as migrations e depois utilizar ``` php artisan test ```