<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Installation

- una, make sure na running ang `apache` server at `mysql` sa xampp
- pumunta sa `xampp/htdocs` at gumawa ng folder na `iris`
- sa loob ng `iris` folder, i-right click at i-click ang `git bash here`
- Kunin ang files sa github repository gamit ang command na ito (i-type sa terminal)
```
git init
```
```
git remote add origin https://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@github.com/sonoiche/iris.git
```
```
git pull origin master
```
- kapag na-download na lahat ng files, pumunta sa browser at i-type ang `http://localhost/phpmyadmin/`
- gumawa ng bagong database, i-click ang `New` sa left side. Database name must be `iris_db`
- kapag nakagawa na ng database ay bumalik sa git bash terminal at i-type ang mga command
```
cd api
```
```
cp .env.example .env
```
```
composer install
```
```
php artisan key:generate
```
```
php artisan migrate
```
```
php artisan passport:install
```
```
php artisan storage:link
```
```
php artisan serve
```

kapag running na ang laravel backend, ay mag open ng panibagong git bash sa folder na `iris` para i-install naman ang frontend UI at i-type ang mga sumusunod na command
```
cd ui
```
```
cp .env.example .env.local
```
```
npm install
```
```
npm run serve
```

- pwede ng i-check ang application sa browser using `http://localhost:8080/`
- you can login as an admin using these credentials
