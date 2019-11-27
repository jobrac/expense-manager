# Installation

```sh
$ composer install
$ cp .env.example .env #put your mysql credentials at .env file
$ php artisan key:generate
$ php artisan migrate:fresh --seed
$ php artisan serve
```
**email** : admin@admin.com
**password** : 'password'