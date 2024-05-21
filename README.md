# Feedback Tool

### Requirements

Feedback Tool was developed under Laravel version 11.x, see Laravel v11 doc for server requirements:
https://laravel.com/docs/11.x/deployment#server-requirements

**Prerequisite for Dockerized development**

1. Composer - https://getcomposer.org/doc/00-intro.md
2. Docker - https://docs.docker.com/get-docker/

# INSTALLATION

Clone the project
```
git clone https://github.com/motorpilotltd/feedback-tool.git

```

Go to the project root folder.
```
cd feedback-tool/
```

**Important:** Install ***composer*** and ***node*** packages(laravel/framework, symfony/* and etc...).

```
composer install
npm install
```

Require the **sail** package via composer

```
composer require laravel/sail --dev
```

Source: [Laravel Sail - Laravel - The PHP Framework For Web Artisans](https://laravel.com/docs/11.x/sail#installing-sail-into-existing-applications)

Configure `.env` file, simply copy the **.env.example** file to **.env** and start modifying the necessary server configuration:
```
# For debugging mode
APP_DEBUG=true
```

Database settings:
```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306

# Database credentials
DB_DATABASE=feedback_db
DB_USERNAME=sail
DB_PASSWORD=password
```

Setting the app's admin email account
Default: `admin@example.com`
```
APP_ADMIN_EMAIL=admin@example.com
```

For mail you can set to **log** so email will appear on the app's log file(*storage/logs/laravel.log*)
```
MAIL_MAILER=log
```

The MAIL_FROM_ADDRESS must not be a null value
```
MAIL_FROM_ADDRESS="no-reply@example.com"
```

# Run the application on *sail*

Simply run this **sail** command:
```
vendor/bin/sail up
```

Or you can configure bash alias so you only need to run ```sail``` command instead of ```vendor/bin/sail```:

```bash
alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
```

And you can now execute sail commands like:
```
sail up
```

To run in **detached** mode:
```
sail up -d
```

And to **stop the application**:
```
sail down
```

# Migration and seeding the database

To add dummy data and the admin account:
```
sail php artisan migrate:fresh --seed
```

# Symlink attachments storage to public folder

```
php artisan storage:link
```

# Visit development app

You can now visit the app at http://localhost

Log in to the admin account:  
*email*: admin@example.com  
*password*: password

**Important:** Remember to change the admin password immediately after **installation**.

# Building assets

You can run ```sail npm run dev``` to run the vite development server (hot reloading).

# Production settings

Modify `.env` file and change `APP_ENV` to production:
```
..
APP_ENV=production
..
```

Finally, build and minify the frontend assets(js/css):
```
npm run build
```
