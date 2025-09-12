# Tiny Web App - Spreadsheet Uploader

This is a simple web application built with the Laravel framework. The application allows a user to upload a spreadsheet file (XLSX or CSV), which is then parsed, stored in a SQLite database, and displayed back to the user with pagination.

The entire development environment is containerized using Docker, making it fully portable and easy to run with a single command.

## Running the Tiny Web App With Docker


## Prerequisites

Before you begin, ensure you have the following installed on your local machine:
-   Docker
-   Docker Compose (Notes: use command (`docker compose` for macOS), (Linux, and Windows with WSL2; use `docker-compose` for Windows with Hyper-V))

## Installation & Setup

Please follow these steps exactly to get the application running.

**1. Clone the Repository**
```bash
git clone https://github.com/m-a-islam/tiny-web-app.git
```
or
```bash
git clone git@github.com:m-a-islam/tiny-web-app.git
```
and then
```bash
cd tiny-web-app 
```

**2. Create the Environment File**
Copy the example environment file. The application is already configured to use SQLite.
```bash
cp src/.env.example src/.env
```

**3. Build and Start the Docker Containers**
This command will build the custom PHP image and start the app and nginx services in the background.
```bash
  docker-compose up -d --build
```
  
###### ****Optional Steps: Initialize the Application****

Note: Steps 4 and 5 must be run from inside the `app` container.  
It is optional because I have written a custom entrypoint script that runs these commands automatically when the container starts for the first time.  
However, if you want to run them manually, follow the instructions below.  
**4. Generate the Application Key**  
Laravel requires an application key for encryption.
```bash
  docker-compose exec app composer install
```

```bash
  docker-compose exec app php artisan key:generate
```

**5. Run Database Migrations**
This command will create the sample table in the SQLite database.
```bash
  docker-compose exec app php artisan migrate
```

**6. You're All Set!**
The application is now running and available at http://localhost:8080.

## How to Use the Application

1.  **Open the Application:** Navigate to [http://localhost:8080](http://localhost:8080) in your web browser.
2.  **Create a Sample File:** Create a spreadsheet file (`.xlsx` or `.csv`). The file **must** contain a header row with the exact column names: `name`, `type`, `location`.
3.  **Upload the File:** You can either:
    -   Drag the file from your computer and drop it anywhere on the browser window.
    -   Click inside the dashed box to open a file selection dialog.
4.  **Submit the Form:** Click the "Upload and Display Data" button.
5.  **View the Results:** The page will reload, and you will see a success message. The data from your spreadsheet will now appear in the paginated table. The original uploaded file will be stored in the `src/storage/app/uploads/` directory.

---

## Running the Automated Tests

A key focus of this project was to ensure the code is reliable and maintainable. To that end, a comprehensive test suite has been included.

To run all the tests, execute the following command from the project root:

```bash
docker-compose exec app php artisan test
```
