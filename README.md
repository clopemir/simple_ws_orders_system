# 📦 Proyecto Laravel + Vue 3 + Inertia

Este repositorio contiene un proyecto web construido con **Laravel** en el backend y **Vue 3 con Inertia.js** en el frontend.
Se trata de un sistema sencillo de pedidos vía WhatsApp.

---

## ⚙️ Requisitos

Antes de comenzar, asegúrate de tener instalado:

- PHP >= 8.1
- Composer
- Node.js >= 16
- NPM o Yarn
- MySQL o PostgreSQL
- Laravel CLI (opcional)
- Extensiones PHP comunes (`pdo`, `mbstring`, etc.)

---

## 🚀 Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/clopemir/simple_ws_orders_system.git
cd simple_ws_orders_system
```

### 2. Instalar dependencias Laravel

```bash
composer install
```

### 3. Instalar dependencias Node

```bash
npm install
```
### 4. Configurar .env

```bash
cp .env.example .env
```

### 5. Generar Key Laravel

```bash
php artisan key:generate
```

### 6. Ejecutar Migraciones y Seeder para usuario principal

```bash
php artisan migrate
php artisan db:seed
```

Por defecto esta configurado SQLite, puedes cambiar a mysql en el .env antes de realizar las migraciones.

### 7. Ejecutar en local

```bash
composer run dev

o

npm run dev | php artisan serve
```

# Ahora puedes acceder al sistema

- para el front desde http://localhost:8000
- para el panel admin desde http://localhost:8000/login

Usuario : test@example.com
Contraseña: password

---

### Deberás configurar tu número de telefono y nombre de tienda en:

- /resources/js/pages/Welcome.vue
- /resources/js/components/AppLogo.vue
---