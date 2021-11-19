<p align="center"><a href="https://script.nl" target="_blank"><img src="https://www.script.nl/script-logo.png" width="400"></a></p>

## About Laravel & Vue template project

Het is een laravel&Vue template project met daar in VueX en de VueRouter. Het is bedoeld om snel aan de slag te kunnen met het programmeren van jouw eigen applicatie.

-   Er kunnen geen rechten aan dit project worden ontleend.

### Project setup

```sh
npm install

composer install

mysql -u root
# run: create database database_name;

cp .env.example .env
code .env
# edit if needed: DB_PORT=, DB_DATABASE=, DB_USERNAME=, DB_PASSWORD=

php artisan key:generate
php artisan migrate:fresh --seed
```

### Run project

Run in 1 terminal the javascript development server:

```sh
npm run watch
```

Run in another terminal the laravel development server:

```sh
php artisan serve
```
