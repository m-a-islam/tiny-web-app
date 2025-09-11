# Tiny Web App - Spreadsheet Uploader

This is a simple web application built with the Laravel framework. The application allows a user to upload a spreadsheet file (XLSX or CSV), which is then parsed, stored in a SQLite database, and displayed back to the user with pagination.

## Running the Tiny Web App Without Docker

**Prerequisites:**
To run this project locally, please confirm the PHP development environment that includes:
 -   PHP (version 8.2 or higher)
 -   Composer (the PHP package manager)
 -   The following PHP extensions: `pdo_sqlite`, `sqlite3`, `gd`, `bcmath`, and `zip`.

**2. The Step-by-Step Command Sequence**
Please follow these steps exactly to get the application running.
**Setup Instructions:**

1. Clone the project and navigate into the `src` directory, as all commands must be run from there.
```bash
    git clone https://github.com/m-a-islam/tiny-web-app.git
```
or
```bash
    git clone git@github.com:m-a-islam/tiny-web-app.git
```
and then
```bash
    cd tiny-web-app/src
```

2. Install all PHP dependencies using Composer.
```bash
    composer install
```
3. Create the environment file by copying the example.
```bash
     cp .env.example .env
```
4. Generate the unique application key for Laravel.
```bash
    php artisan key:generate
```
5. Create the empty SQLite database file.
```bash
     touch database/database.sqlite
```
6. Run the database migrations to create the `sample` table.
```bash
     php artisan migrate
```
7. Start Laravel's built-in development server.
```bash
     php artisan serve
```
8. The application will now be running at the URL provided in the terminal (usually **http://127.0.0.1:8000** or **http://127.0.01.8080**).
