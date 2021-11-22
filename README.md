# Lazy Lib Backend


## Development

### Requirements :
```
Mysql 8.x
Php 7.4 
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

### Create mail config
```
MAIL_DRIVER=smtp
MAIL_HOST=smtp.googlemail.com
MAIL_PORT=465
MAIL_USERNAME=ltaeyong574@gmail.com
MAIL_PASSWORD=SE300799
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=zackautocracy@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Add front url
```
APP_FRONT_URL=https://www.lazy_lib.com (Not created yet)
```

### Add back url

```
APP_URL=http://104.248.39.111
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

## Production (Test in digitalocean)

https://www.digitalocean.com/community/tutorials/how-to-install-and-configure-laravel-with-lemp-on-ubuntu-18-04

1- Create a droplet of 2 go ram 2 cpu 50gb stockage.

2- Add ssh key to droplet password is Manager1234 you will find the private key within the project.

3- Add a new user to ubuntu with username lazy_manager and with the same password.

4- Update and upgrade
```sudo apt-get update && sudo apt-get upgrade```

5- Add nginx 
```sudo apt-get install nginx```

6- Make sure that Nginx registers itself as a service with ufw 
```sudo ufw app list```

...see tutorial
### File too large : Look for this link and search for ngix:
```https://stackoverflow.com/questions/28529518/small-file-get-uploaded-but-not-large-file-in-laravel/28581314```
```Don't forget to create your .ini in nginx folder```
### Unable to write in the Directory
```
 useradd -U -G www-data -m -c "deploy" deploybot
 chown deploybot:www-data /var/www/back
 chmod 2755 /var/www/back
 sudo usermod -aG www-data deploybot
 sudo chown -R www-data:www-data /var/www/back
 sudo chown -R www-data:www-data /var/www/back/public
 sudo chmod -R 775 /var/www
```
# Prod bbd instance in aws
## Convert .ppk to .pem to use ssh command
```
sudo apt-get install putty-tools 
puttygen server1.ppk -O private-openssh -o server1.pem 
chmod 400 server1.pem 
ssh -i server1.pem user@ip_address
```
## Update and upgrade
```
sudo apt-get update && sudo apt-get upgrade
```
## Add user to machine as sudoer
```
adduser bobbhy
usermod -aG sudo bobbhy
```
## Copy ssh key from admin to the new user (Stay connected as admin)
```
sudo apt-get install rsync grsync 
sudo rsync --archive --chown=bobbhy:bobbhy ~/.ssh /home/bobbhy
```
## Install db 
``` 
sudo apt-get install default-mysql-server
sudo mysql_secure_installation
CREATE DATABASE lazy_lib;
CREATE USER 'lazy_manager'@'localhost' IDENTIFIED BY 'Manager1234.';
GRANT ALL PRIVILEGES ON lazy_lib. * TO 'lazy_manager'@'localhost';
```