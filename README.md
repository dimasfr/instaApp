# 📸 Laravel + Vue Timeline App

A simple social timeline application built with **Laravel** as the backend API and **Vue 3** as the frontend.  
Users can register, login, create posts with multiple photos, like/unlike posts, and comment — similar to a simplified Instagram feed.

---

## 🚀 Features

### 👤 Authentication
- User registration & login (JWT-based)
- Persistent session with token storage
- Redirect handling on login/logout
- Public dashboard accessible even without login (limited view)

### 📰 Post Management
- Create post with text and multiple images
- Soft delete and hard delete support for posts
- Real-time post refresh after submission
- Masked or blurred content preview for guest users (not logged in)

### ❤️ Likes & 💬 Comments
- Like/unlike functionality with live count
- Comment system linked to posts
- Restricted comment visibility for non-authenticated users

### 🔔 UI Enhancements
- Toast notifications (success, error, info)
- Lucide icons for cleaner modern UI (Post, Like, Delete, Logout)
- Responsive and minimal design with Tailwind CSS
- Image preview and auto-reset on new upload

---

## 🏗️ Tech Stack

### Backend
- **Laravel 11**
- **Sanctum / JWT** for authentication
- **Eloquent ORM** for database
- **Migration + Seeder** for schema setup
- **CORS & API routes** for Vue integration

### Frontend
- **Vue 3 (Composition API)**
- **Vue Router** for page navigation
- **Axios** for API communication
- **Tailwind CSS** for styling
- **Lucide-Vue** for icons
- **Toast helper** for user notifications

---

## ⚙️ Installation

### 1️⃣ Backend (Laravel)
```bash
cd backend
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

The backend will run on:
```
http://localhost:8000
```

---

### 2️⃣ Frontend (Vue)
```bash
cd frontend
cp .env.example .env
npm install
npm run dev
```

The frontend will run on:
```
http://localhost:5173
```

---

## 🔐 Authentication Flow

| Step | Description |
|------|--------------|
| `/register` | User registers with name, email, password |
| `/login` | Authenticates user and returns JWT token |
| `localStorage` | Stores token on successful login |
| `api.js` | Automatically attaches token on every request |
| `router.beforeEach` | Handles redirects between login/register/dashboard |

---

## 📂 Project Structure

```
laravel-vue-timeline/
├── backend/ (Laravel API)
│   ├── app/Http/Controllers/
│   ├── database/migrations/
│   ├── routes/api.php
│   └── ...
│
└── frontend/ (Vue 3 App)
    ├── src/
    │   ├── pages/
    │   │   ├── LoginPage.vue
    │   │   ├── RegisterPage.vue
    │   │   └── DashboardPage.vue
    │   ├── components/
    │   │   ├── PostCard.vue
    │   │   ├── CommentList.vue
    │   │   └── Toast.vue
    │   ├── utils/
    │   │   ├── api.js
    │   │   └── toast.js
    │   └── router/
    │       └── index.js
    └── ...
```

---

## 🧩 Notable Features

- ✅ Toast notification helper (`/utils/toast.js`)
- ✅ Preview reset and revoke URL on post submission
- ✅ File input auto-clear after successful post
- ✅ Conditional rendering for guest vs logged-in users
- ✅ Clean reusable components

---

## 🧠 Future Improvements
- Add pagination and infinite scroll
- Implement image lazy loading
- Add user profile pages
- Support comment reply (nested comments)
- Realtime updates with Laravel Echo / Pusher

---

## 🧑‍💻 Author

**Dimas Fajar Ramadhan**  
Fullstack Developer — Laravel + Vue  
📧 dimasfr918@gmail.com  
🌐 https://github.com/dimasfr

---

## 📜 License

This project is licensed under the **MIT License**.
