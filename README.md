# Instructions on how to run the project

### Prerequisites

Ensure you have the following installed:

-   **PHP** v8.4.8
-   **Composer** v2.8.9
-   **Node.js** v22.17.0
-   **MySQL** v8.4.4
-   **Laravel** v12.0.0
-   **Vue.js** v3.4.0

_Note: These versions are recommended but not mandatory; other versions will probably work as well._

### Database Configuration

Create a `.env` file with the following database connection variables:

```ini
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=smalllistings_db
DB_USERNAME=root
DB_PASSWORD=root
```

You can copy from the `.env.example` file using:

```bash
cp .env.example .env
```

### Installation Steps

1. **Install PHP dependencies:**

```bash
composer install
```

2. **Install Node.js dependencies (optional):**

```bash
npm install
```

3. **Generate application key:**

```bash
php artisan key:generate
```

4. **Create symbolic link for storage:**

```bash
php artisan storage:link
```

5. **Set up the database:**

    - Ensure a database named `smalllistings_db` exists (or the name used in `.env`).

6. **Run database migrations:**

```bash
php artisan migrate
```

7. **Run database seeder:**

```bash
php artisan db:seed
```

**Note ! ! !**

After running the database seeder, the following users will be available:

-   **Admin User**  
    Email: `admin@gmail.com`  
    Password: `password`  
    Role: `admin`

-   **Customer User**  
    Email: `milos@gmail.com`  
    Password: `password`  
    Role: `customer`

8. **To run the project, use the following commands:**

```bash
# Run the development server
npm run dev

# Start the PHP server
php artisan serve
```

9. **View project:**

    - Go to: localhost:8000

**Enjoy! 😃**
