# Eurystheus RESTful API
The repository forms the backend in form of a RESTful API for the Eurystheus project.

## Table of contents
1. [Used](#used)
1. [System Requirements](#system-requirements)
1. [Features / Issues](#features-/-issues)
1. [Install](#install)
    1. [Via GitHub](#via-github)
1. [Credits](#credits)

## Used
* [Laravel 7.18.0](https://laravel.com)
* [XAMPP v3.2.4](https://www.apachefriends.org/index.html)
    * [PHP 7.4.7](https://www.php.net/)
    * [MySQL 10.4.13](https://www.mysql.com/)

## System Requirements
* Unix-like OS
* [PHP 7.4+](https://www.php.net/)
* Web-Server ([Nginx](https://www.nginx.com/) or [Apache2](https://httpd.apache.org/))
* Database ([MariaDB](https://mariadb.org/)/[MySQL](https://www.mysql.com/))

## Features / Issues
* [X] #1 Authentication via JWT
* [ ] Handle projects

## Install
How to install this RESTful API?

#### Via GitHub
* Clone the [GitHub Repository](https://github.com/Timoteus3000/eurystheus-rest-api) : `git clone https://github.com/Timoteus3000/eurystheus-rest-api`
    * Or download the ZIP-File
* Choose a branch you want to use:
    * `master`: is always stable
    * `develop`: current branch that is being worked on
* Go into your database:
    * Create a new database with: `CREATE DATABASE db_Eurystheus;`
* Go into the current project folder:
    * Migrate the Database with: `php artisan migrate`
* Serve the RESTful-API with: `php artisan serve`
* Now you're done!

## Credits
* [Timoteus3000](https://github.com/Timoteus3000)
* [All Contributors](https://github.com/Timoteus3000/eurystheus-rest-api/graphs/contributors)
