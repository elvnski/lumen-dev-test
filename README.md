# API for a Task Management System

## Overview

This is a RESTful API built using Lumen that allows users to manage tasks efficiently. It supports basic CRUD (Create, Read, Update, Delete) operations and includes additional features like task filtering, pagination, and search functionality. The API is designed to be simple yet robust, with validation and error handling to ensure data integrity.

## Requirements

- **PHP 8.2** is required to run this application.
- Ensure you have the correct PHP version installed before configuring the environment.


### Task Model

- **Fields**:
    - `id`: (Primary Key, Auto-incrementing integer)
    - `title`: (String, Required, Unique)
    - `description`: (Text, Optional)
    - `status`: (Enum, Default: "pending", Possible values: "pending", "completed")
    - `due_date`: (Date, Must be a future date)
    - `created_at`: (Timestamp, Auto-managed by Laravel)
    - `updated_at`: (Timestamp, Auto-managed by Laravel)

### API Endpoints

1. **Create a Task**
    - **Endpoint**: `POST /tasks`
    - **Request Body**:
      ```json
      {
        "title": "Task Title",
        "description": "Task Description",
        "due_date": "YYYY-MM-DD"
      }
      ```
    - **Responses**:
        - `201 Created`: Task successfully created.
        - `422 Unprocessable Content`: Validation errors.


2. **Get All Tasks**
    - **Endpoint**: `GET /tasks`
    - **Query Parameters**:
        - `status` (Optional): Filter by task status.
        - `due_date` (Optional): Filter by due date.
        - `page` (Optional): Page number for pagination.
        - `per_page` (Optional): Number of tasks per page for pagination (default is 10).
    - **Responses**:
        - `200 OK`: List of tasks with pagination.

    - **Examples**:
        - **Filtering**:
          ```bash
          curl -X GET "http://localhost:8000/tasks?status=pending&due_date=2024-12-31"
          ```
        - **Searching**:
          ```bash
          curl -X GET "http://localhost:8000/tasks?search=New"
          ```
        - **Pagination**:
            - By default, the result is paginated by 10 tasks per page. You can specify a different number using the `per_page` parameter:
          ```bash
          curl -X GET "http://localhost:8000/tasks?page=1&per_page=20"
          ```

3. **Get a Specific Task**
    - **Endpoint**: `GET /tasks/{id}`
    - **Responses**:
        - `200 OK`: Task details.
        - `404 Not Found`: Task not found.


4. **Update a Task**
    - **Endpoint**: `PUT /tasks/{id}`
    - **Request Body**:
      ```json
      {
        "title": "Updated Task Title",
        "description": "Updated Task Description",
        "status": "completed",
        "due_date": "YYYY-MM-DD"
      }
      ```
    - **Responses**:
        - `200 OK`: Task successfully updated.
        - `422 Unprocessable Content`: Validation errors.
        - `404 Not Found`: Task not found.


5. **Delete a Task**
    - **Endpoint**: `DELETE /tasks/{id}`
    - **Responses**:
        - `204 No Content`: Task successfully deleted.
        - `404 Not Found`: Task not found.

### Validation

- All incoming requests are validated to ensure required fields are present and data types are correct.
- Appropriate error messages are returned for invalid requests.

### Database

- **Database**: PostgreSQL
- **Migration**: Includes a migration script to create the `tasks` table with the specified fields and constraints.

### Features

1. **Task Filtering**:
    - Filter tasks by `status` and `due_date` using query parameters in the `GET /tasks` endpoint.

2. **Pagination**:
    - Implement pagination for the task listing endpoint using `page` and `per_page` query parameters.

3. **Search Functionality**:
    - Add search functionality to find tasks by `title` using a query parameter in the `GET /tasks` endpoint.

## Setup Instructions

1. **Clone the Repository**
   ```bash
   git clone https://github.com/elvnski/lumen-dev-test.git
   cd lumen-dev-test
   ```

2. Install Dependencies
   ```bash
    composer install
   ```
    

3. Configure Environment
- Copy the `.env.example` file to `.env`
  ```bash
    cp .env.example .env
  ```

- Update the `.env` file with your PostgreSQL database credentials:
    ```dotenv
      DB_CONNECTION=pgsql
      DB_HOST=127.0.0.1
      DB_PORT=5432
      DB_DATABASE=your_database
      DB_USERNAME=your_username
      DB_PASSWORD=your_password
    ```

4. Generate Application Key
    ```bash
        php artisan key:generate
    ````

5. Run Migrations
    ```bash
    php artisan migrate
    ````

6. Start the Server
    ```bash
    php -S localhost:8000 -t public
    ````


## Usage

- Use tools like Postman or cURL to interact with the API endpoints.
- Ensure you are sending requests to `http://localhost:8000` or your configured base URL.

## Contact

For any questions or feedback, please contact me, Elvin Owuor, at [elvinowuor@gmail.com](mailto:elvinowuor@gmail.com).
