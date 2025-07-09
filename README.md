# Symfony 7.3 Project

A modern web application built with Symfony 7.3

---

## Requirements

- PHP >= 8.2
- Composer
- Symfony CLI (optional, recommended)
- Database (MySQL, PostgreSQL, SQLite, etc.)

---

## Installation

1. **Clone the repository**

```bash
git clone https://github.com/jaijil-dev/propeller.git
cd propeller
```
2. **Create environment file**

Copy the sample file and adjust it:

```bash
cp .env.dev .env
```

Edit `.env` or `.env.local` to add API_BASE_URL(api domain name) and API_TOKEN (Bearer Token)

2. **Install dependencies**

```bash
composer install
```

5. **Start the server**

Using Symfony CLI:

```bash
symfony server:start
```

Or PHP built-in server:

```bash
php -S 127.0.0.1:8000 -t public
```

---

## Usage

Access your project at:

```
http://127.0.0.1:8000
```

---

##  Working

After setting up project use the js code in /api-client.js to run directly in browser

---

## License

This project is licensed under the [MIT License](LICENSE).
