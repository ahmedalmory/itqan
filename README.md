# Itqan Quran Application

This is a Laravel-based Quran learning management system. Follow the steps below to set up and run the project.

## Requirements

- PHP 8.2 or higher
- Composer
- MySQL or MariaDB
- Node.js and npm
- Git

## Installation Steps

### 1. Clone the Repository

```bash
git clone https://github.com/ahmedalmory/itqan
cd itqan
```


### 2. Environment Setup

Copy the `.env.example` file to create a new `.env` file:

```bash
cp .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

### 3. Configure the Database

Edit the `.env` file and update the following database connection parameters:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

### 4. Install Dependencies

Install PHP dependencies:

```bash
composer install
```

### 5. Run Migrations and Seed the Database

Run migrations to create tables and Seed the database with initial data:

```bash
php artisan migrate --seed
```

### 7. Run the Application

Start the Laravel development server:

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`.

## Default Users

After seeding, you can log in with the following default accounts:

- **Super Admin**:
  - Email: admin@example.com
  - Password: password

- **Department Admin**:
  - Email: dept_admin1@example.com
  - Password: password

- **Supervisor**:
  - Email: supervisor1@example.com
  - Password: password

- **Teacher**:
  - Email: male_teacher1@example.com (or female_teacher1@example.com)
  - Password: password

- **Student**:
  - Email: mstudent1@example.com (or fstudent1@example.com)
  - Password: password