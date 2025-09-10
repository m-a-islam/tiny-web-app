# Tiny Web App - Spreadsheet Uploader

This is a simple web application built with the Laravel framework that fulfills the requirements of the Web Application Engineer technical task. The application allows a user to upload a spreadsheet file (XLSX or CSV), which is then parsed, stored in a SQLite database, and displayed back to the user with pagination.

The entire development environment is containerized using Docker, making it fully portable and easy to run with a single command.

## Features

-   **Spreadsheet Upload:** Users can upload `.xlsx` or `.csv` files.
-   **Modern UI:** A clean user interface with a global "drag-and-drop" area for a seamless user experience.
-   **Data Persistence:** Uploaded data is parsed and stored in a local SQLite database.
-   **Dynamic Data Display:** All data from the database is displayed in a paginated table.
-   **Custom File Naming:** Uploaded files are stored on the server with a custom name format (`project-name_date_time.extension`).
-   **Robust Validation:**
    -   **Server-Side:** The application validates that a file is present, has a correct MIME type, and does not exceed a 10MB size limit.
    -   **Client-Side:** JavaScript provides immediate user feedback if the selected file is larger than 10MB, preventing a long upload that is destined to fail.
-   **User Feedback:** Clear success and error messages are displayed to the user.
-   **Comprehensive Test Suite:** The application includes both Feature and Unit tests to ensure reliability and code quality.

## Technical Stack

-   **Backend:** PHP 8.2, Laravel 11
-   **Database:** SQLite
-   **Frontend:** HTML5, Tailwind CSS (via CDN), Vanilla JavaScript
-   **Development Environment:** Docker, Docker Compose, Nginx
-   **Testing:** PHPUnit

## Prerequisites

Before you begin, ensure you have the following installed on your local machine:
-   Docker
-   Docker Compose

## Installation & Setup

Please follow these steps exactly to get the application running.

**1. Clone the Repository**
```bash
git clone <your-repository-url>
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

**4. Generate the Application Key**
Laravel requires an application key for encryption.
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

### Test Cases Covered

The test suite includes **Feature Tests** that simulate user interactions and **Unit Tests** that test individual classes in isolation.

#### Feature Tests (`tests/Feature/`)

-   **Main Page Loads:** Verifies that the home page returns a successful `200` HTTP status.
-   **Successful File Upload:** Tests the entire "happy path" - a valid file is uploaded, data is stored in the database, the file is saved to disk, and the user is redirected.
-   **Validation: No File Provided:** Ensures the application returns a validation error if the form is submitted without a file.
-   **Validation: Invalid File Type:** Ensures the application rejects files that are not spreadsheets (e.g., an image).
-   **Validation: File Too Large:** Ensures the application correctly rejects a file that is larger than the 10MB limit.
-   **Edge Case: Empty Spreadsheet:** Verifies that a spreadsheet with only a header row is handled gracefully and does not add any records to the database.

#### Unit Tests (`tests/Unit/`)

-   **SpreadsheetImportService:** This test verifies the core business logic of the application in isolation. It mocks the `Excel` facade and uses an in-memory database to confirm that the service correctly parses data and creates database records, proving the logic is sound independent of any controller or HTTP request.

## Architectural Decisions & Best Practices

This project was built with a focus on clean, modern software architecture.

-   **Containerized Development:** The entire environment is defined in `docker-compose.yml`. This guarantees a consistent and reproducible environment, solving the "it works on my machine" problem.

-   **Separation of Concerns (SOLID Principles):**
    -   **Thin Controllers:** The `SampleController`'s responsibility is limited to handling the HTTP request and response. All complex logic is delegated to other classes.
    -   **Service Layer:** The core business logic for processing a spreadsheet is extracted into a dedicated `SpreadsheetImportService`. This makes the logic reusable, self-contained, and highly testable.
    -   **Form Request Class:** Validation logic is encapsulated in the `StoreSpreadsheetRequest` class, removing clutter from the controller and making the validation rules reusable.

-   **Robust File Handling:** File size is validated at three distinct layers:
    1.  **Client-Side (JavaScript):** Provides instant user feedback.
    2.  **Application-Level (Laravel Validation):** Provides clean error messages.
    3.  **Server-Level (`php.ini`):** Provides a hard security limit.

-   **Clean Frontend Assets:** All custom CSS and JavaScript are separated into their own files in the `public/assets` directory, keeping the main Blade template clean and focused on HTML structure.