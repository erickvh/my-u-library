


### Technical Test  Backend MyULibrary

#### Requirements
* Docker / Docker-compose
* PHP7.0 | PHP8.0 
* Composer


### Installation
* composer install
* cp .env.example .env
* Fill .env attributes for `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
* run command `sail up -d` in order to run docker-containers
* run command `sail artisan key:generate` to generate app_key
* run command `sail artisan migrate:refresh --seed` to migrate and populate with fake data


##### Testing
* run command `sail artisan test` to run feature tests
* run command `sail artisan test --coverage-html tests/reports` this will generate complete html report on `test/reports/index.html`


#### Documentation
[Postman documentation](https://documenter.getpostman.com/view/9154195/2s7YYvbNa5#caed2f0a-4b1e-4be9-986f-df9b76a6f106)
