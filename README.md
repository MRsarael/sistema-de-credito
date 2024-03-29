<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

## Sobre o projeto

Sistema de simulação de oferta de crédito consumindo a API de testes de *Gosat*.

Acessando a hora *home* é possível acessar o front da aplicação feito com javascript e Bootstrap 5.

<p align="center">
    <img src="https://raw.githubusercontent.com/MRsarael/sistema-de-credito/main/public/images/tela1.png" width="400">
</p>

## CPF's disponíveis para teste:<br/>
<p>
    <br/>11111111111
    <br/>22222222222
    <br/>12312312312
</p>

## Passos para configurar o projeto para teste

```bash
git clone https://github.com/MRsarael/sistema-de-credito.git
composer install
php artisan key:generate
composer dump-autoload
OBS: Será necessário criar um banco de dados e configurar a conexão no .env
OBS: Configurar o NAME_APP no *.env*. EX: APP_URL=http://127.0.0.1:8000
php artisan migrate
```

## Rotas disponíveis

| Rota                             | Método    | Descrição                                                          |
| ---------------------------------| --------- | ------------------------------------------------------------------ |
|`/api/person`                     | `POST`    | Cadastro de pessoas                                                |
|`/api/person/{id}`                | `GET`     | Listagem de pessoa cadastrada                                      |
|`/api/person`                     | `GET`     | Lista todas as pessoas cadastradas                                 |
|`/api/person`                     | `PUT`     | Edita os dados de uma pessoa                                       |
|`/api/person/{id}`                | `DELETE`  | Remove uma pessoa                                                  |
|`/api/person/credit/offer/{id?}`  | `GET`     | Consulta as ofertas de crédito disponíveis na base de dados        |
|`/api/person/credit/simulation`   | `POST`    | Realiza a simulação de uma oferta de crédito disponível por pessoa |
|`/api/credit/{id}`                | `POST`    | Consulta na Gost as ofertas de crédito para o CPF                  |

