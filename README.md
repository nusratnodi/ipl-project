# Single-Page CRUD — PHP + JS + MySQL (XAMPP)

A simple single-page Products CRUD with a sliding banner titled **"This is my project"**.

## Stack

- **PHP** (PDO) — backend REST-style API
- **MySQL** — database
- **Vanilla JS** (fetch API) — frontend, no frameworks
- **XAMPP** — local environment

## Folder structure

```
single-page-project/
├── index.html             # Single-page UI
├── css/style.css          # Styles + sliding banner animation
├── js/app.js              # AJAX CRUD logic
├── api/
│   ├── db.php             # PDO connection
│   └── products.php       # CRUD endpoints
├── sql/setup.sql          # Database & table setup
└── README.md
```

## Setup (XAMPP)

### 1. Copy the project into htdocs

Copy the entire `single-page-project` folder into your XAMPP `htdocs` directory:

```
C:\xampp\htdocs\single-page-project\
```

### 2. Start Apache & MySQL

Open the **XAMPP Control Panel** and click **Start** for both `Apache` and `MySQL`.

### 3. Create the database

Open <http://localhost/phpmyadmin> in your browser, then:

- Click the **SQL** tab
- Paste the contents of `sql/setup.sql`
- Click **Go**

(Or import the file via the **Import** tab.)

This creates the `crud_app` database, the `products` table, and inserts 3 sample rows.

### 4. (Optional) DB credentials

The default XAMPP MySQL config is used:

- Host: `127.0.0.1`
- User: `root`
- Password: *(empty)*
- Database: `crud_app`

If yours differs, edit `api/db.php`.

### 5. Open the app

<http://localhost/single-page-project/>

## Features

- **Sliding banner** — animated title with a slide-in + gentle float, plus a moving shine effect
- **Create** — add a new product (name, price, description)
- **Read** — list all products in a table, newest first
- **Update** — click **Edit** to load a product into the form
- **Delete** — click **Delete** to remove a product (with confirmation)
- **No page reloads** — everything runs through `fetch()`

## API endpoints

All under `api/products.php?action=...`:

| Method | Action   | Body                                    | Response                   |
|--------|----------|-----------------------------------------|----------------------------|
| GET    | `list`   | —                                       | `{ok, data: [...]}`        |
| GET    | `get`    | `?id=N`                                 | `{ok, data: {...}}`        |
| POST   | `create` | `{name, price, description}`            | `{ok, id}`                 |
| POST   | `update` | `{id, name, price, description}`        | `{ok, updated}`            |
| POST   | `delete` | `{id}`                                  | `{ok, deleted}`            |
