# BillingCalculation
# Billing page designed with PHP Laravel

# Project Setup Instructions

Follow these steps to set up and run the project:

## Prerequisites
- PHP (version 8.2 or higher recommended)
- Composer
- MySQL or any compatible database
- Laravel CLI

---

## Step 1: Install Dependencies
Run the following command to install all required PHP dependencies:
```bash
composer update
```

---

## Step 2: Create the `.env` File
1. Copy the example environment file:
   ```bash
   cp .env.example .env
   ```
2. Update the `.env` file with your database credentials and other environment-specific settings:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   ```

---

## Step 3: Run Migrations
Run the following command to create the necessary database tables:
```bash
php artisan migrate
```

---

## Step 4: Seed the Database
1. Seed the default data:
   ```bash
   php artisan db:seed
   ```
2. Seed specific tables, such as the `ProductsTableSeeder`:
   ```bash
   php artisan db:seed --class=ProductsTableSeeder
   ```

---

## Additional Commands
- To clear caches:
  ```bash
  php artisan config:clear
  php artisan cache:clear
  ```
- To serve the application locally:
  ```bash
  php artisan serve
  ```


