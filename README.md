# Tiny Web App


## Features

-   **Spreadsheet Upload:** Users can upload `.xlsx` or `.csv` files.
-   **Modern UI:** A clean user interface with a global "drag-and-drop" area for a seamless user experience.
-   **Data Persistence:** Uploaded data is parsed and stored in a local SQLite database.
-   **Dynamic Data Display:** All data from the database is displayed in a paginated table.
-   **Robust Validation:**
    -   **Server-Side:** The application validates that a file is present, has a correct MIME type, and does not exceed a 10MB size limit.
    -   **Client-Side:** JavaScript provides immediate user feedback if the selected file is larger than 10MB, preventing a long upload that is destined to fail.
-   **User Feedback:** Clear success and error messages are displayed to the user.
-   **Comprehensive Test Suite:** The application includes both Feature and Unit tests to ensure reliability and code quality.

## Technical Stack

-   **Backend:** PHP 8.2, Laravel 12
-   **Database:** SQLite
-   **Frontend:** HTML5, Tailwind CSS (via CDN), Vanilla JavaScript
-   **Development Environment:** Docker, Docker Compose, Nginx (`this is optional if wish to run the app locally without Docker.`)
-   **Testing:** PHPUnit

Choose how you want to run the project:

| Environment                                   | Description                       | Link                                         |
|-----------------------------------------------|-----------------------------------|----------------------------------------------|
| Docker (recommended)                          | Reproducible containerized stack  | [Run with Docker](./README-DOCKER.md)        |
| Local PHP install (macOS/Linux or Windows 11) | Use your existing local toolchain | [Run locally (no Docker)](./README-LOCAL.md) |




### Test Cases Covered

The test suite includes **Feature Tests** that simulate user interactions and **Unit Tests** that test individual classes in isolation.

#### Feature Tests (`tests/Feature/`)

-   **Main Page Loads:** Verifies that the home page returns a successful `200` HTTP status.
-   **Successful File Upload:** Tests the entire "best case" - a valid file is uploaded, data is parsed and stored in the database, and the user is redirected.
-   **Validation: No File Provided:** Ensures the application returns a validation error if the form is submitted without a file.
-   **Validation: Invalid File Type:** Ensures the application rejects files that are not spreadsheets (e.g., an image).
-   **Validation: File Too Large:** Ensures the application correctly rejects a file that is larger than the 10MB limit.

#### Unit Tests (`tests/Unit/`)

-   **SpreadsheetImportService:** This test verifies the core business logic of the application in isolation.
It mocks the `Excel` facade and uses an in-memory database to confirm that the service correctly parses data
and creates database records, proving the logic is sound independent of any controller or HTTP request.

## Architectural Decisions

This project was built with a focus on clean, modern software architecture.

-   **Containerized Development:** The entire environment is defined in `docker-compose.yml`.  
This guarantees a consistent and reproducible environment, solving the "it works on my machine" problem.  

-   **Separation of Concerns (SOLID Principles):**
    -   **Thin Controllers:** The `FileUploadController`'s responsibility is limited to handling the HTTP request and response.
    All complex logic is delegated to other classes.
    -   **Service Layer:** The core business logic for processing a spreadsheet is extracted into a dedicated `SpreadsheetImportService`.
    This makes the logic reusable, self-contained, and highly testable.
    -   **Form Request Class:** Validation logic is encapsulated in the `StoreSpreadsheetRequest` class,
    removing clutter from the controller and making the validation rules reusable.

-   **Robust File Handling:** File size is validated at three distinct layers:
    1.  **Client-Side (JavaScript):** Provides instant user feedback.
    2.  **Application-Level (Laravel Validation):** Provides clean error messages.
    3.  **Server-Level (`php.ini`):** Provides a hard security limit.

-   **Clean Frontend Assets:** All custom CSS and JavaScript are separated into their own files in the `public/assets` directory, keeping the main Blade template clean and focused on HTML structure.



