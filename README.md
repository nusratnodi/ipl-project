# Student Attendance — PHP + MySQL + JS (XAMPP)

A small student-attendance tracker.

| Layer                | Technology         |
|----------------------|--------------------|
| User Interface       | HTML / CSS         |
| Animations / alerts  | JavaScript (+ `alert()`) |
| Data processing      | PHP (mysqli, CRUD) |
| Data storage         | MySQL              |

## Folder structure

```
single-page-project/
├── index.php             # List + create/update form
├── delete.php            # POST-only delete handler
├── connection.php        # mysqli connection ($name, $user_name, $password, $db_name, $conn)
├── css/style.css         # All styles
├── js/app.js             # UI animations only
├── sql/setup.sql         # Database & table
└── README.md
```

## Backend coding pattern

All PHP files follow the same simple pattern:

```php
include("connection.php");

if (isset($_POST['submit'])) {
    $field = trim($_POST['field']);

    if (empty($field)) {
        echo "<script>alert('All fields are required!');</script>";
    }
    else {
        $sql = "INSERT INTO table_name(col) VALUES('$field')";
        $result = mysqli_query($conn, $sql);
        if ($result)
            echo "<script>alert('Successful!');</script>";
        else
            echo "<script>alert('Failed!');</script>";
    }
}
```

- mysqli (procedural) — `mysqli_query`, `mysqli_num_rows`, `mysqli_fetch_assoc`, `mysqli_affected_rows`.
- Variable names from [connection.php](connection.php): `$name`, `$user_name`, `$password`, `$db_name`, `$conn`.
- Form submit button always has `name="submit"`, checked with `isset($_POST['submit'])`.
- All input collected with `trim($_POST['x'])`.
- User feedback via `echo "<script>alert('…');</script>"`.

## Setup (XAMPP)

1. Copy this folder to `C:\xampp\htdocs\single-page-project\`.
2. Start **Apache** and **MySQL** from the XAMPP Control Panel.
3. In phpMyAdmin (<http://localhost/phpmyadmin>), open **SQL** and run [sql/setup.sql](sql/setup.sql). This creates the `attendance_app` database and the `attendance` table with 3 sample rows.
4. Open <http://localhost/single-page-project/>.

Default DB credentials in [connection.php](connection.php) match XAMPP defaults:

- Host: `localhost`
- User: `root`
- Password: *(empty)*
- Database: `attendance_app`

## CRUD operations

| Op     | Where                       | How                                          |
|--------|-----------------------------|----------------------------------------------|
| Create | [index.php](index.php) form | POST with empty hidden `id` → `INSERT`       |
| Read   | [index.php](index.php) table| `SELECT … ORDER BY date DESC`                |
| Update | `?edit=<id>` then form POST | POST with hidden `id` filled in → `UPDATE`   |
| Delete | Delete button (form POST)   | [delete.php](delete.php) → `DELETE`          |

## JavaScript

[js/app.js](js/app.js) handles only UI animations:

1. Staggered fade-in entrance for cards.
2. `confirm()` prompt before any `form.js-delete` submits.
3. Shake animation on inputs that fail HTML5 validation.
4. Smooth scroll to top when entering edit mode (`?edit=…`).

Backend success/failure messages are shown by `<script>alert(...)</script>` echoed by PHP.

## Security note

This code follows a beginner-friendly tutorial style: inline string SQL interpolation
(`"... WHERE id='$id'"`). That is **vulnerable to SQL injection**. For production,
use mysqli prepared statements (`$stmt = mysqli_prepare(...)`, `mysqli_stmt_bind_param`).
