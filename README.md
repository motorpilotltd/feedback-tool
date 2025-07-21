# Feedback Tool

This Feedback Tool was developed on [Laravel 11.x](https://laravel.com/docs/11.x/). 

# Requirements
 
See [Laravel 11.x deployment documentation](https://laravel.com/docs/11.x/deployment#server-requirements) for server requirements and deployment guidance.  
MySQL 8.0+ is recommended, can also be run with MariaDB or SQLite.

For Docker based development (recommended) using Laravel Sail you will need to install Docker and, for Windows, WSL2 (and ensure it is integrated with Docker).  
Docker: https://docs.docker.com/get-docker/  
Docker on Windows (via WSL2): https://docs.docker.com/desktop/wsl/

To run locally without Docker [Laravel Herd](https://herd.laravel.com/) is an option for Windows and MacOS or you can use your own preferred stack. Minimum requirement is PHP (8.2/8.3) with MySQL and Composer. Laravel's local development server can then be used via `php artisan serve`.

Node and NPM are also required for development and/or building assets.

# Installation (Using Sail and Docker)

For full Sail documentation please visit [Laravel Sail - Laravel - The PHP Framework For Web Artisans](https://laravel.com/docs/11.x/sail).

## Windows Performance Advice

Whilst it is possible to run the following with the code in a folder on the Windows file system, i.e. somewhere within a `/mnt/<drive>` folder when viewed in the Linux distros, it is recommended to clone (or simply copy) the repository to the Linux file system for a significant performance improvement.

## Initialisation

On Windows the following instructions should be carried out within a WSL Linux distro.

Clone the repository.
```bash
git clone https://github.com/motorpilotltd/feedback-tool.git
```

Go to the project root folder.
```bash
cd feedback-tool/
```

Run the script to install Composer and Node dependencies. This utilises a docker container so there is no need to have the ability to install the dependencies on your local system.
```bash
./pre-sail.sh
```

Configure the `.env` file, simply copy `.env.sail.example` file to `.env` and modify if necessary. The default configuration should be suitable in most situations.

## Run the application with Sail

You can configure and alias to allow the use od `sail` rather than `vendor/bin/sail`.
```bash
alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
```
The rest of this documentation will assume you have set up the alias.

The `up` command starts the Docker containers (the `-d` option starts the containers in the background in 'detached' mode)
```bash
sail up -d
```

### Key generation

The `APP_KEY` needs to be generated.
```bash
sail php artisan key:generate
```

### Database migration and seeding

Generate the database tables and populate with example data, including an admin account.
```bash
sail php artisan migrate:fresh --seed
```

Alternatively, if you wish to generate the database tables without any example data.
```bash
sail php artisan migrate:fresh
```

## Accessing the App

If `.env` has been modified from `.env.sail.example` then adjust the following instructions accordingly.

### Web

The app will be available at http://localhost:8080.

To log in to the configured admin account:  
*email:* admin@example.com  
*password:* password

**Important:** Remember to change the admin password immediately after **installation**.

### Database

The MySQL database is accessible on `localhost:33066`.  
*username:* sail  
*password:* password

### Mail

[Mailpit](https://mailpit.axllent.org/) is used as the SMTP server to capture outgoing email. The Mailpit dashboard can be accessed at http://localhost:8025.

## Stopping the application

To stop the application and leave the containers in their current state.
```bash
sail stop
```

To stop the application and remove the containers.
```bash
sail down
```

This assumes containers are running in the background, if running in the foreground `CTRL + C` will stop the container.

# Development

To configure the development environment Node dependencies need to be installed.
```
sail npm install
```

To run the vite development server (hot reloading).
```bash
sail npm run dev
```

To build assets following changes to JS/CSS.
```bash
sail npm run build
```
At present we store built assets in the repo to avoid the need for a build step during deployment. This *may* change in the future.

## Windows

As indicated earlier it *is* possible to clone the repository to the Windows file system and launch the app using Sail. Whilst this can make it easier for development using a Windows IDE it can dramatically affect the performance when running the app. With the repository cloned to the Linux file system it can still be possible to develop using a Windows IDE, e.g. VS Code has the remote-wsl extension to open folders within WSL.

# Deployment/Production

You may wish to consider [Laravel Forge](https://forge.laravel.com/) or [Laravel Vapor](https://vapor.laravel.com/) for deployment.

Copy `.env.example` to `.env` and modify according to your production needs.

Examples:  
Ensure `APP_ENV` is set to `production`.  
Ensure `APP_DEBUG` is set to `false`.
Check database settings.
Check logging settings.  
Check mail settings.
Ensure `APP_ADMIN_EMAIL` and `MAIL_FROM_ADDRESS` are set to suitable addresses.

Assets are built and committed when required to avoid end users having to build them on deployment. If changes have been made locally then it will be necessary to build them before/during deployment (Requires Node, NPM and dependencies installed).  
```bash
npm run build
```

On a fresh install the database tables can be created and seeded as per the [instructions for running locally using Sail and Docker](#database-migration-and-seeding). Don't forget to immediately change the admin password following installation!
