# Symmetrical Telegram API

A demo API application using Laravel that facilitates the Note Library management. The system allow user to add multiple Notes and save transcription with AI summury.

### Application Features:

-   Laravel v11 + Filament v3.3.20 + Passport API

## Clone Repository

Clone the repo locally:

```sh
git clone https://github.com/anikninja/symmetrical-telegram.git
cd symmetrical-telegram
```

Install PHP dependencies:

```sh
composer install
```

Install NPM dependencies:

```sh
npm install && npm run build
```

Setup configuration:

```sh
cp .env.example .env
```

Generate application key:

```sh
php artisan key:generate
```

Create an SQLite database. You can also use another database (MySQL, Postgres), simply update your configuration accordingly.

```sh
touch database/database.sqlite
```

Run database migrations:

```sh
php artisan migrate
```

Run database seeder:

```sh
php artisan db:seed
```

Run server:

```sh
php artisan serve
```

Test It is running or not? Click on following url [http://127.0.0.1:8000] in your Terminal.

## Credits

🚀 Original work by Optimal Byte Ltd.
