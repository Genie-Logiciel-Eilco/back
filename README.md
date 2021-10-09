# Lazy Lib Backend


## Development

### Requirements :
```
Mysql 8.x
Php 7.4 or higher
Composer
Docker (maybe in prod only not sure yet)
```

### Create Mysql user and add db :

```
CREATE DATABASE lazy_lib;
CREATE USER 'lazy_manager'@'localhost' IDENTIFIED BY 'Manager1234.';
GRANT ALL PRIVILEGES ON lazy_lib. * TO 'lazy_manager'@'localhost';
```
### Make a copy of **_.env.example_** file and name it **_.env_** (don't edit/delete **_.env.example_**),

Fill **_.env_**  with your variables, for example:

```
DB_DATABASE=lazy_lib
DB_USERNAME=lazy_manager
DB_PASSWORD=Manager1234.
```

Run `php artisan key:generate` to set `APP_KEY` in **_.env_**.

Run `php artisan migrate:fresh --seed` to migrate the database and populate it with the necessary data.

Run `php artisan storage:link` to create a symbolic link from `public/storage` to `storage/app/public`.

Run `php artisan ser` for a local dev server listening on port 8000.


### Additional documentation

If you edit your **_.env_** make sure to run `php artisan config:cache`.

I added to `App\Http\Controllers\Controller` some basic helpers you can use in your controllers like `sendResponse`, `sendError`, `getAuthenticatedUser`, `verifyPermission`, etc.

To Generate a model with its migration, seeder, factory, and resource controller run `php artisan make:model ModelName -a`,

Edit your Migration with the appropriate DB properties in `database/migrations/xxxx_xx_xx_xxxxxxx_migration_name.php`.

In order to migrate the new migration run `php artisan migrate`.

Edit your Model in `app/Models/ModelName.php`.

Edit your Controller in `app/Http/Controllers/ModelNameController.php`.

Call your controller using Laravel's router in `routes/api.php`.

All API routes (in `routes/api.php`) have the _/api_/URI prefix automatically applied.