# 📖 Educational Project: Guest Book

This project is the result of the practical part of the course **"PHP 8. From Theory to Practice"** (author: Andrey Kudlay). Within this application, the basic functionality of user interaction with the web interface, working with the database, and content administration has been implemented.

## 🎯 Key Features

The following functions were implemented during development:

*   **User System:** Registration and authorization.
*   **Message Management:** Adding new entries (for authorized users).
*   **Moderation:** Ability to change message status and edit them (administrator functions).
*   **Validation:** Input data validation using the `Valitron` library.
*   **Pagination:** Paged output of messages.
*   **Security:** Form data processing, use of sessions, working with PDO to protect against SQL injections.

## 🛠 Technology Stack

*   **Language:** PHP 8.4
*   **Database:** MySQL 8.4
*   **Server:** Apache 2.4
*   **Dependency Management:** Composer
*   **Development Environment:** Docker & Docker Compose

## 🚀 Running the Project

To run the project, you will need **Docker** and **Docker Compose** installed.

### Quick Start

1.  **Build and start containers:**
    ```bash
    make up
    ```
2.  **Install dependencies (via Composer in the container):**
    ```bash
    make composer-install
    ```
3.  **Stop the project:**
    ```bash
    make down
    ```

### 🔗 Available URLs

*   **Web Interface:** [http://localhost](http://localhost)
*   **phpMyAdmin:** [http://localhost:8080](http://localhost:8080) — MySQL database management.
*   **MySQL:** `localhost:3306`

## 📁 Project Structure

*   `src/public/` — Main directory with source code.
    *   `includes/` — Application logic: functions, database operations (`db.php`), pagination class.
    *   `views/` — Display templates (TPL files).
*   `config/` — Apache (`httpd.conf`) and PHP (`php.ini`) configuration files.
*   `docker/` — Dockerfile for building the PHP container.
*   `env/` — Environment variables (`.env`).
*   `Makefile` — Convenient commands for project management.

## 🛠 Main Commands (Makefile)

*   `make up` — Start the project.
*   `make down` — Stop and remove containers.
*   `make restart` — Restart services.
*   `make logs` — View logs of all services.
*   `make status` — Check container status.
*   `make shell-php` — Access the PHP container terminal.
*   `make composer-install` — Install PHP packages.

---
*The project was completed for educational purposes to reinforce PHP 8 skills.*
