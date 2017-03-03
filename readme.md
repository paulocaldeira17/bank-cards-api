# Bank Cards Api

An API that allows you to create multiple cards and transactions using merchants authorization requests.

### Requirements

* PHP >=5.6.4
* [Composer](https://getcomposer.org/download/)
* [NodeJs](https://nodejs.org/en/download/)
* [npm](https://docs.npmjs.com/getting-started/installing-node)
* [gulp](https://github.com/gulpjs/gulp/blob/master/docs/getting-started.md)

### Installation

After install requirements run these commands on your console.

1. Access your project root folder `cd project-root`.
2. Install composer dependencies using `composer install`
3. Install NPM dependencies using `npm install`.
4. Generate [API Documentation](#API-Documentation) using `gulp` command.
5. Set your `APP_KEY` inside `.env` file.
6. Migrate database using `php artisan migrate`. Please certify that `database/database.sqlite` database is created.
7. Run `php -S localhost:8000 -t public` to start your server.

### API Documentation

To access api documentation you just need to access to `http://localhost:8000/documentation/v1/`. 
**Note:** Last slash must be placed or it won't work.

### Authorization 

1. [Create a user](http://localhost:8000/documentation/v1/#api-Users-StoreUser).
2. [Request/Generate a user `api_token`](http://localhost:8000/documentation/v1/#api-Users-GenerateTokenUser).

Please check [API Documentation](#API-Documentation) to see how to make these requests.

### Database

I'm currently using SQLite database but you can configure other database storage. I just choose it to remove an extra storage server configuration.
If you want to change, you can do it using Laravel's database [configurations](https://laravel.com/docs/5.4/database).


