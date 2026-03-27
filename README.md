# Library Management System (LMS)

## 📚 Pangkalahatang Descripsyon

Ang **Library Management System** ay isang web-based application na ginawa gamit ang **PHP** na tumutulong sa pamamahala ng library. Ito ay may tatlong user roles: **Admin**, **Librarian**, at **Member**.

---

## 🏗️ Paano Gumagana ang Sistema

### Architecture (MVC Pattern)

Ang sistema ay gumagamit ng **Model-View-Controller (MVC)** architecture:

```
App/
├── Config/          # Database at configuration files
├── Controllers/     # Business logic (Controllers)
├── Core/            # Core classes (Router, DB, View)
├── Models/          # Database queries (Models)
└── Views/           # HTML templates (Views)
```

### Paano Gumagana ang Routing

1. Ang lahat ng request ay dumaan sa [`public/index.php`](public/index.php:1)
2. Ang [`Router`](App/Core/Router.php:5) class ang bahala sa pag-determine kung anong controller at method ang tatawagin
3. Ang URL format ay: `index.php?url=Controller/method/parameter`

**Halimbawa:**
- `index.php?url=Book/index` → Tumatawag ng [`BookController::index()`](App/Controllers/BookController.php:19)
- `index.php?url=Users/edit/5` → Tumatawag ng [`UsersController::edit(5)`](App/Controllers/UsersController.php:75)

### Database Structure

Ang sistema ay gumagamit ng **MySQL** database na may tatlong tables:

1. **`users`** - Mga user accounts (admin, librarian, member)
2. **`book`** - Mga libro sa library
3. **`loan`** - Mga borrow records at transactions

---

## 🔧 Paano I-setup ang Sistema

### Requirements
- XAMPP (Apache + MySQL + PHP)
- PHP 8.0+
- MySQL/MariaDB

### Installation Steps

1. **I-copy ang project sa XAMPP htdocs:**
   ```
   C:\xampp\htdocs\LMS
   ```

2. **I-start ang XAMPP:**
   - Buksan ang XAMPP Control Panel
   - I-start ang **Apache** at **MySQL**

3. **I-import ang database:**
   - Buksan ang phpMyAdmin: `http://localhost/phpmyadmin`
   - Gumawa ng bagong database na `lms_db`
   - I-import ang [`lms_db.sql`](lms_db.sql:1) file

4. **I-configure ang database connection:**
   - Buksan ang [`App/Config/Database.php`](App/Config/Database.php:1)
   - I-update ang database credentials kung kinakailangan:
     ```php
     $host = '127.0.0.1:3306';
     $dbname = 'lms_db';
     $username = 'root';
     $password = '';
     ```

5. **I-access ang sistema:**
   - Buksan ang browser at pumunta sa: `http://localhost/LMS/public/`
   - Automatic na ma-redirect sa login page

---

## 👥 Mga User Roles at Features

### 🔑 Default Accounts

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@gmail.com | (password ay naka-hash sa database) |
| Librarian | juan@gmail.com | (password ay naka-hash sa database) |
| Member | pedro@gmail.com | (password ay naka-hash sa database) |

---

### 👨‍💼 Admin

Ang Admin ay may **full access** sa lahat ng features:

#### Features:
1. **Dashboard** - Nakikita ang statistics:
   - Active borrows
   - Overdue books
   - Pending fines
   - Total users
   - Total books
   - Recent transactions

2. **User Management** ([`UsersController`](App/Controllers/UsersController.php:8))
   - Magdagdag ng bagong user (admin, librarian, member)
   - Mag-edit ng user information
   - Mag-delete ng user
   - Maghanap ng user by role

3. **Book Management** ([`BookController`](App/Controllers/BookController.php:8))
   - Magdagdag ng bagong libro
   - Mag-edit ng libro (title, author, ISBN, copies)
   - Mag-delete ng libro
   - Maghanap ng libro

4. **Student Lookup** ([`LibrarianController`](App/Controllers/LibrarianController.php:10))
   - Maghanap ng member by email
   - Makita ang mga borrowed books ng member
   - Makita ang outstanding fines

5. **Transactions** ([`LibrarianController::transactions()`](App/Controllers/LibrarianController.php:137))
   - Makita ang lahat ng transactions
   - I-filter by status (Borrowed, Returned, Overdue)

---

### 📚 Librarian

Ang Librarian ay may access sa library operations:

#### Features:
1. **Dashboard** - Nakikita ang:
   - Active borrows
   - Overdue books
   - Pending fines
   - Recent transactions

2. **Student Lookup** ([`LibrarianController::lookup()`](App/Controllers/LibrarianController.php:26))
   - Maghanap ng member by email
   - Makita ang borrowed books
   - Makita ang outstanding fines

3. **Issue Book** ([`LibrarianController::borrow()`](App/Controllers/LibrarianController.php:54))
   - Mag-issue ng libro sa member
   - Automatic na mag-set ng due date (14 days)
   - Hindi pwede mag-issue kung may outstanding fine

4. **Return Book** ([`LibrarianController::return()`](App/Controllers/LibrarianController.php:101))
   - I-mark ang libro as returned
   - Automatic na mag-compute ng fine kung overdue
   - Fine: ₱5 per day overdue

5. **Pay Fine** ([`LibrarianController::payFine()`](App/Controllers/LibrarianController.php:125))
   - I-clear ang outstanding fine ng member

6. **Book Management** ([`BookController`](App/Controllers/BookController.php:8))
   - Magdagdag, mag-edit, at mag-delete ng libro

7. **Transactions** - Makita ang lahat ng transactions

---

### 👤 Member

Ang Member ay may limited access:

#### Features:
1. **My Dashboard** ([`DashboardController::member()`](App/Controllers/DashboardController.php:56))
   - Makita ang kanyang borrowed books
   - Makita ang due dates
   - Makita ang status (Borrowed, Returned, Overdue)

2. **Browse Books** ([`BookController::browse()`](App/Controllers/BookController.php:170))
   - Maghanap ng libro
   - Makita ang available copies
   - Hindi pwede mag-borrow directly (kailangan through librarian)

---

## 📋 Mga Importanteng Features

### Auto-Generated ISBN
- Kung hindi mag-provide ng ISBN, automatic na magge-generate ang sistema
- Format: `{timestamp}-{random}`

### Fine Calculation
- **Rate:** ₱5 per day overdue
- **Default borrow period:** 14 days
- Automatic na nagco-compute ng fine kapag nagre-return ng libro

### Rate Limiting
- Maximum 5 failed login attempts
- Automatic na maglo-lock ng account kapag na-reach ang limit

### Session Management
- Automatic na nagre-redirect sa tamang dashboard base sa role
- Hindi pwede i-access ang protected pages kapag hindi logged in

---

## 🗂️ File Structure

```
LMS/
├── .htaccess                    # Apache URL rewriting
├── lms_db.sql                   # Database schema
├── public/
│   ├── index.php               # Entry point
│   ├── .htaccess               # Public directory config
│   └── bootstrap/              # Bootstrap CSS/JS
├── App/
│   ├── Config/
│   │   ├── Database.php        # Database connection
│   │   └── init.php            # App constants
│   ├── Controllers/
│   │   ├── AppController.php   # Base controller
│   │   ├── AuthController.php  # Login/Logout
│   │   ├── BookController.php  # Book CRUD
│   │   ├── DashboardController.php
│   │   ├── LibrarianController.php
│   │   └── UsersController.php
│   ├── Core/
│   │   ├── bootstrap.php       # Autoloader
│   │   ├── DB.php              # Database class
│   │   ├── Router.php          # URL routing
│   │   └── View.php            # View renderer
│   ├── Models/
│   │   ├── Books.php           # Book queries
│   │   ├── BorrowRecords.php   # Loan queries
│   │   └── Users.php           # User queries
│   └── Views/
│       ├── Auth/               # Login page
│       ├── Book/               # Book pages
│       ├── Dashboard/          # Dashboard pages
│       ├── Layout/             # Layout templates
│       ├── Librarian/          # Librarian pages
│       └── Users/              # User pages
```

---

## 🔄 Workflow ng Pagbabalik ng Libro

1. **Member** → Pumupunta sa librarian para magbalik ng libro
2. **Librarian** → Hinahanap ang member gamit ang email
3. **Librarian** → I-click ang "Return" button
4. **System** → Automatic na nagco-compute ng fine kung overdue
5. **System** → I-update ang status to "Returned"
6. **System** → I-increment ang available copies ng libro

---

## 🔍 Workflow ng Paghiram ng Libro

1. **Member** → Pumupunta sa librarian para maghiram ng libro
2. **Librarian** → Hinahanap ang member gamit ang email
3. **Librarian** → I-type ang book title o ISBN
4. **System** → I-check kung may available copies
5. **System** → I-check kung may outstanding fine ang member
6. **System** → I-create ang loan record
7. **System** → I-decrement ang available copies

---

## ⚙️ Configuration

### Database Settings ([`App/Config/Database.php`](App/Config/Database.php:1))
```php
$host = '127.0.0.1:3306';
$dbname = 'lms_db';
$username = 'root';
$password = '';
```

### App Constants ([`App/Config/init.php`](App/Config/init.php:1))
```php
define('FINE_PER_DAY', 5);        // ₱5 per day overdue
define('DEFAULT_BORROW_DAYS', 14); // 14 days borrow period
```

---

## 🐛 Troubleshooting

### Hindi ma-access ang sistema?
1. Siguraduhin na naka-start ang Apache at MySQL sa XAMPP
2. I-check kung tama ang database credentials sa [`App/Config/Database.php`](App/Config/Database.php:1)
3. I-import ang [`lms_db.sql`](lms_db.sql:1) sa phpMyAdmin

### Hindi ma-login?
1. I-check kung tama ang email at password
2. I-check kung active ang account sa database
3. I-reset ang rate limiting kung na-lock ang account

### Hindi ma-delete ang libro?
1. I-check kung may active borrows ang libro
2. I-return muna ang lahat ng borrowed copies

---
