# 🐾 Shelter Paws

*A Laravel + Livewire + Inertia/Vue demo inspired by Shelterluv — built to showcase modern, compassionate full-stack engineering.*

---

## 🚀 Overview

**Shelter Paws** is a portfolio-ready prototype that demonstrates how a modern shelter management system might be built using **Laravel 11**, **Livewire 3**, **Inertia.js**, **Vue 3**, **TailwindCSS**, and **PestPHP**.
It simulates the **Intake → Care → Adoption** workflow and highlights how to balance maintainability, performance, and user empathy.

> 🧡 Built to show how technology can help shelters and rescues save lives — fast, humane, and maintainable.

---

## 🧰 Tech Stack

| Layer        | Tool                            |
| ------------ | ------------------------------- |
| Backend      | Laravel 11 (PHP 8.2)            |
| Frontend     | TailwindCSS + Alpine.js + Vue 3 |
| UI Framework | Livewire 3 + Inertia.js         |
| Database     | MySQL / SQLite                  |
| Auth         | Laravel Breeze (Vue SSR)        |
| Testing      | PestPHP                         |
| API          | Laravel Sanctum                 |

---

## 💡 Features

✅ **Animal Intake & Management** — Livewire CRUD with filters and pagination

✅ **Adoption Queue** — Inertia/Vue interactive list with priority bumping

✅ **Hold Board (Kanban)** — Vue 3 drag‑and‑drop columns for Available → Hold → Pending

✅ **Humane UX** — clean language, accessibility, and responsive Tailwind design

✅ **API-first mindset** — JSON endpoints for animals and queue

✅ **Seeded demo data** — prepopulated animals, applications, and users

---

## 📂 Folder Structure

```
shelter-paws/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── DashboardController.php
│   │   │   ├── Api/AnimalApiController.php
│   │   └── Livewire/
│   │       └── Animals/
│   │           ├── Index.php
│   │           └── Create.php
│   ├── Models/
│   │   ├── Animal.php
│   │   ├── AdoptionApplication.php
│   │   ├── Hold.php
│   │   └── User.php
│   └── Providers/
│       └── AppServiceProvider.php
├── database/
│   ├── factories/
│   │   └── AnimalFactory.php
│   ├── migrations/
│   │   ├── 2025_01_01_000000_create_animals_table.php
│   │   ├── 2025_01_01_000100_create_adoption_applications_table.php
│   │   └── 2025_01_01_000200_create_holds_table.php
│   └── seeders/
│       └── DemoSeeder.php
├── resources/
│   ├── js/
│   │   ├── Pages/
│   │   │   ├── AdoptionQueue.vue
│   │   │   └── HoldBoard.vue
│   │   └── Components/
│   │       └── AnimalCard.vue
│   ├── views/
│   │   ├── livewire/animals/
│   │   │   ├── index.blade.php
│   │   │   └── create.blade.php
│   │   └── layouts/
│   │       └── app.blade.php
├── routes/
│   ├── web.php
│   └── api.php
├── tests/
│   └── Feature/AnimalLifecycleTest.php
├── public/
│   └── img/placeholder-dog.jpg
├── package.json
├── composer.json
├── README.md
└── .env.example
```

---

## ⚙️ Installation

```bash
# 1️⃣ Clone the repo
git clone https://github.com/YOURUSERNAME/shelter-paws.git
cd shelter-paws

# 2️⃣ Install dependencies
composer install
npm install

# 3️⃣ Copy environment file
cp .env.example .env
php artisan key:generate

# 4️⃣ Run migrations & seeders
php artisan migrate --seed

# 5️⃣ Install auth scaffolding (Breeze)
composer require laravel/breeze --dev
php artisan breeze:install vue
npm install && npm run dev

# 6️⃣ Start servers
php artisan serve   # backend
npm run dev         # frontend
```

Visit [http://localhost:8000](http://localhost:8000) and log in with the demo user seeded in `DemoSeeder`.

---

## 🧪 Testing

```bash
php artisan test
```

Example:

```bash
PASS  Tests\Feature\AnimalLifecycleTest
✓ can intake and list available animals
```

---

## 🧭 Demo Flow

| Step | Description                                           |
| ---- | ----------------------------------------------------- |
| 1    | Log in as admin and view **Animals Index** (Livewire) |
| 2    | Add new animal intake and upload optional photo       |
| 3    | Open **Adoption Queue** (Vue) and bump priority       |
| 4    | Use **Hold Board** to drag animals between columns    |
| 5    | Explore API routes via `/api/animals`                 |

---
