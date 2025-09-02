# DaryllGimeno Technical Exam - Praxy

A Laravel + Vue 3 + Inertia.js technical exam project with product management (CRUD), dashboard, authentication, and frontend
---

## 📌 Requirements

- **PHP** >= 8.1  
- **Composer**  
- **MySQL** or MariaDB  
- **Node.js** >= 18 and **npm**  
- **Git**

---

## ⚙️ Installation Guide

### 1. Clone the repository
```bash
git clone https://github.com/Daryllgimeno/DaryllGimeno-technical-exam-praxy.git
cd Daryllgimeno-technical-exam-praxy
```

### 2. Install dependencies
```bash
composer install
npm install
```

### 3. Setup environment
Copy the `.env.example` to `.env`:

```bash
cp .env.example .env
```

Update your database credentials in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=productdb
DB_USERNAME=root
DB_PASSWORD=
```

Other important settings:
```env
APP_NAME=Laravel
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
```

### 4. Generate application key
```bash
php artisan key:generate
```

### 5. Run migrations & seeders
```bash
php artisan migrate --seed
```

If you want to refresh the database:
```bash
php artisan migrate:fresh --seed
```

You can also run seeders individually:
```bash
php artisan db:seed --class=LoginSeeder
php artisan db:seed --class=ProductSeeder
```

---

## ▶️ Running the Project

### Start Laravel backend
```bash
php artisan serve
```
Runs at: **http://127.0.0.1:8000**

### Start Vite frontend
```bash
npm run dev
```
Runs at: **http://localhost:5173**

---

## 📦 Main Dependencies

- Laravel 10  
- Vue 3  
- Inertia.js  
- Vite  
- TailwindCSS  
- Axios  
- Heroicons  
- Iconoir  
- Chart.js + Vue-Chartjs  
- Video.js  

---



- Default database: **`productdb`**  
- Seeder creates sample login and product data.  
- Make sure MySQL service is running before running migrations.  


