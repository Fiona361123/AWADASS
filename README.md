# JobBridge Job Board – Project Setup Guide

## Contributors

* Huang Wan Jun
* Lee Mun Onn
* Shia Kah Yee
* Yu Xiao Shi

---

## Before Running the Project

Please complete the following steps before running the project:

### 1. Install Dependencies

Install all required backend and frontend dependencies:

```bash
npm install
```

```bash
composer install
```

---

### 2. Environment Configuration

Set up your environment file:

```bash
cp .env.example .env
```

Then update the `.env` file with the appropriate configuration for your system.

---

### 3. Broadcast Configuration (Important)

Add the following configuration to your `.env` file to enable broadcasting (as a fallback if needed):

```
BROADCAST_DRIVER=pusher
PUSHER_APP_ID="2130607"
PUSHER_APP_KEY="b3bd2c9574b4a72310ec"
PUSHER_APP_SECRET="f4d21b8aac01c3647a99"
PUSHER_APP_CLUSTER="ap1"
```

---

### 4. Generate Application Key

```bash
php artisan key:generate
```

---

### 5. Run the Project

Start the development servers:

```bash
npm run dev
```

```bash
php artisan serve
```

---

## Presentation Video

Here is the link to our presentation video:
https://drive.google.com/file/d/1SMaCPk3PtJW2FYhT2yAWZv7JEy2x4RW5/view?usp=drive_link

---
