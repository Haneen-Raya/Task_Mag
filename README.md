# Task Management API

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Overview

This project is a Task Management API built with the Laravel framework. It allows users to register, log in, create, update, view, and delete tasks. The project supports user roles (admin and regular user) with different permissions for each role.

## Key Features

*   Authentication system using Laravel Sanctum (Register, Login, Logout).
*   Task Management (CRUD Operations).
*   Task Status Management (CRUD Operations for Statuses - admin restricted).
*   Role-based Authorization (Admin/User).
*   Task filtering by status and priority.
*   API Resources for formatting JSON responses.
*   Service layer for business logic.
*   Request Validation.

## Prerequisites

*   PHP >= 8.2
*   Composer
*   Node.js & npm (for running Vite in development)
*   A database (MySQL, PostgreSQL, SQLite - configured for SQLite by default, changeable in `.env`)
*   Postman (or any other API client for testing)

## Installation and Setup

1.  **Clone the repository:**
    ```bash
    git clone <your_repository_url>
    cd <project_folder_name>
    ```

2.  **Install dependencies:**
    ```bash
    composer install
    npm install # For frontend assets (Vite)
    ```

3.  **Set up the environment file (`.env`):**
    Copy the `.env.example` file to `.env`:
    ```bash
    cp .env.example .env
    ```
    Generate the application key:
    ```bash
    php artisan key:generate
    ```
    **Configure your database:**
    Edit the database variables in your `.env` file to match your setup (if not using the default SQLite):
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_user
    DB_PASSWORD=your_database_password
    ```

4.  **Run database migrations:**
    If using MySQL or PostgreSQL, ensure the database specified in `.env` exists. This command will create the necessary tables:
    ```bash
    php artisan migrate
    ```
    For SQLite, the database file will be created automatically.

5.  **Run database seeders:**
    This will populate the database with an admin user and initial task statuses.
    ```bash
    php artisan db:seed
    ```
    **Seeder Details:**
    *   `AdminUserSeeder`: Creates a user with admin privileges.
        *   **Default Email:** `admin@example.com`
        *   **Default Password:** `password` (Highly recommended to change this in a production environment!)
    *   `StatusSeeder`: Creates basic task statuses like "Open", "In Progress", "Completed", etc. (This is crucial for being able to create tasks, as `TaskService` defaults `status_id` to 2).

6.  **Start the development server:**
    ```bash
    php artisan serve
    ```
    The server will typically start on `http://127.0.0.1:8000`.

7.  **(Optional) Run Vite for frontend development (if modifying CSS/JS assets):**
    ```bash
    npm run dev
    ```

## API Routes (Endpoints)

All API routes are defined in `routes/api.php`. They are prefixed with `/api/`.

### Authentication

*   `POST /api/register`: Register a new user.
    *   **Body:** `name`, `email`, `password`, `password_confirmation`, `role` (optional, 'user' or 'admin')
*   `POST /api/login`: Log in an existing user.
    *   **Body:** `email`, `password`
*   `POST /api/logout`: (Authentication required) Log out the current user.

### Tasks
*(Authentication required for all task routes)*

All task routes are handled by `App\Http\Controllers\TaskController` and use `App\Http\Resources\TaskResource` for responses. Authorization is managed by `App\Policies\TaskPolicy`.

*   `GET /api/tasks`: List tasks.
    *   Regular users see their own tasks. Admins see all tasks.
    *   **Query Parameters:** `status_id` (integer), `priority` (string: 'low', 'medium', 'high') for filtering.
*   `POST /api/tasks`: Create a new task.
    *   **Body:** `title` (required), `description` (nullable), `status_id` (required, integer, must exist in statuses table), `due_date` (nullable, YYYY-MM-DD, after or equal to today).
    *   Admins can optionally provide `user_id` to assign the task to another user.
*   `GET /api/tasks/{task}`: Show a specific task (e.g., `/api/tasks/1`).
*   `PUT /api/tasks/{task}`: Update a specific task.
    *   **Body:** (Provide fields to update) `title`, `description`, `status_id`, `due_date`.
    *   Admins can update any task and can change the `user_id`.
*   `DELETE /api/tasks/{task}`: Delete a specific task.

### Statuses
*(Authentication and Admin privileges required for all status routes)*

All status routes are handled by `App\Http\Controllers\StatusController`. Authorization is managed by `App\Policies\StatusPolicy`.

*   `GET /api/status`: List all statuses.
*   `POST /api/status`: Create a new status.
    *   **Body:** `name` (required, string, unique)
*   `GET /api/status/{status}`: Show a specific status and its associated tasks (e.g., `/api/status/1`).
*   `PUT /api/status/{status}`: Update a specific status.
    *   **Body:** `name` (string, unique, ignoring current status ID)
*   `DELETE /api/status/{status}`: Delete a specific status.

## Postman Collection

You can test the API using the Postman collection.

**Published Postman Collection Link:**
[https://documenter.getpostman.com/view/39062755/2sB2qUo57m]

**Instructions for using the Postman Collection:**
1.  Import the collection into Postman.
2.  You will need an authentication `token`. Use the Register (`POST /api/register`) or Login (`POST /api/login`) endpoints to obtain a token.
3.  When logging in as `admin@example.com` (password: `password`), you will receive a token granting admin privileges.
4.  Set the received `token` (prefixed with `Bearer `) in the `Authorization` header for your authenticated requests.
    Example: `Authorization: Bearer YOUR_AUTH_TOKEN`

## Software Requirements Specification (SRS - Simplified View)

### 1. Introduction
    - **Purpose:** To provide a robust API for task management with role-based access control.
    - **Scope:** The project includes user authentication, task management, and status management.

### 2. General Description
    - **Product Perspective:** A backend API service consumable by frontend applications (web, mobile).
    - **Product Functions:**
        - User registration and login.
        - CRUD operations for tasks.
        - CRUD operations for task statuses (admin-only).
        - Task prioritization and due dates.
        - Task filtering.
    - **User Characteristics:**
        - **Normal User:** Can manage their own tasks.
        - **Admin User:** Can manage all tasks, users (partially implemented), and task statuses.

### 3. Functional Requirements

    **FR1: Authentication System**
        - FR1.1: Users shall be able to register for a new account.
        - FR1.2: Users shall be able to log in using their email and password.
        - FR1.3: Authenticated users shall be able to log out.
        - FR1.4: An API token shall be generated upon successful registration or login for subsequent authenticated requests.

    **FR2: User Management (Basic)**
        - FR2.1: Each user shall have a role (`admin` or `user`). (Implemented)
        - FR2.2 (Admin): Admins should be able to list users. (Further endpoint needed)
        - FR2.3 (Admin): Admins should be able to modify user roles. (Further endpoint needed)

    **FR3: Task Management**
        - FR3.1: Authenticated users shall be able to create a new task.
            - Required fields: `title`, `status_id`.
            - Optional fields: `description`, `due_date`, `priority`.
        - FR3.2: Users shall be able to view their own tasks.
        - FR3.3 (Admin): Admins shall be able to view all tasks in the system.
        - FR3.4: Users shall be able to view details of a task they own.
        - FR3.5 (Admin): Admins shall be able to view details of any task.
        - FR3.6: Users shall be able to update a task they own.
        - FR3.7 (Admin): Admins shall be able to update any task, including changing the assigned user.
        - FR3.8: Users shall be able to delete a task they own.
        - FR3.9 (Admin): Admins shall be able to delete any task.
        - FR3.10: Users shall be able to filter tasks by `status_id` and `priority`.

    **FR4: Status Management (Admin Only)**
        - FR4.1: Admins shall be able to create a new task status.
        - FR4.2: Admins shall be able to list all task statuses.
        - FR4.3: Admins shall be able to view details of a specific status.
        - FR4.4: Admins shall be able to update an existing status.
        - FR4.5: Admins shall be able to delete an existing status.

### 4. Non-Functional Requirements

    - **NFR1 (Performance):** API responses should be fast (e.g., < 500ms for typical requests).
    - **NFR2 (Security):** Endpoints must be properly secured using authentication and authorization. Passwords must be hashed.
    - **NFR3 (Maintainability):** Code should be well-organized, and easy to understand and modify.
    - **NFR4 (Scalability):** The system should be designed to allow for easy addition of new features.

### 5. Database Design (Based on migrations)

*   **users**: `id`, `name`, `email`, `password`, `role` (`admin`, `user`), `timestamps`.
*   **tasks**: `id`, `user_id` (FK to users), `status_id` (FK to statuses), `title`, `description`, `due_date`, `priority` (`low`, `medium`, `high`), `softDeletes`, `timestamps`.
*   **statuses**: `id`, `name`, `softDeletes`, `timestamps`.
*   **personal_access_tokens**: (For Sanctum).
*   (Other default Laravel tables: `password_reset_tokens`, `sessions`, `cache`, `jobs`, `failed_jobs`).

## Contributing

Please see the [Laravel contribution guide](https://laravel.com/docs/contributions) for details.

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
