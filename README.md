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

## 🔄 Detailed Workflows

### 📖 Workflow ng Paghiram ng Libro (Borrow)

**Sino ang gumagawa:** Librarian
**Controller:** [`LibrarianController::borrow()`](App/Controllers/LibrarianController.php:54)

#### Step-by-Step Process:

1. **Member Request**
   - Ang member ay pumupunta sa librarian
   - Sinasabi kung anong libro ang gusto i-borrow

2. **Librarian Search Member**
   - I-click ang "Lookup Student" sa sidebar
   - I-type ang email ng member sa search box
   - I-click ang "Search" button
   - **System Action:** Tumatawag ng [`Users::findMember()`](App/Models/Users.php:1) para hanapin ang member

3. **System Display Member Info**
   - Nakikita ang member details (name, email, address)
   - Nakikita ang current borrowed books
   - Nakikita ang outstanding fines
   - **System Action:** Tumatawag ng [`BorrowRecords::getActiveBorrowsByUser()`](App/Models/BorrowRecords.php:40)

4. **Librarian Issue Book**
   - I-type ang book title o ISBN sa "Book Query" field
   - I-select ang due date (default: 14 days from today)
   - I-click ang "Issue Book" button

5. **System Validation**
   - **Check 1:** I-check kung existing ang book
     - Tumatawag ng [`Books::findByAccessionOrId()`](App/Models/Books.php:1)
     - Kung hindi existing → Error: "Book not found"
   - **Check 2:** I-check kung may available copies
     - Tumatawag ng [`Books::getAvailableCopies()`](App/Models/Books.php:1)
     - Kung 0 available → Error: "No available copies"
   - **Check 3:** I-check kung may outstanding fine
     - Tumatawag ng [`BorrowRecords::calculateFine()`](App/Models/BorrowRecords.php:103)
     - Kung may fine → Error: "Student has ₱{fine} outstanding fine. Please settle first."

6. **System Create Loan Record**
   - Tumatawag ng [`BorrowRecords::issue()`](App/Models/BorrowRecords.php:127)
   - I-insert sa `loan` table:
     ```sql
     INSERT INTO loan (user_id, book_id, borrow_date, due_date, status)
     VALUES (?, ?, CURDATE(), ?, 'Borrowed')
     ```

7. **System Update Book Copies**
   - Tumatawag ng [`Books::decrementAvailable()`](App/Models/Books.php:1)
   - I-update ang `available_copies` ng book (minus 1)

8. **System Display Success**
   - Flash message: "Book '{title}' issued successfully. Due: {due_date}"
   - I-redirect sa student lookup page

---

### 🔄 Workflow ng Pagbabalik ng Libro (Return)

**Sino ang gumagawa:** Librarian
**Controller:** [`LibrarianController::return()`](App/Controllers/LibrarianController.php:101)

#### Step-by-Step Process:

1. **Member Return Book**
   - Ang member ay pumupunta sa librarian
   - Ibibigay ang libro na gusto i-return

2. **Librarian Search Member**
   - I-click ang "Lookup Student" sa sidebar
   - I-type ang email ng member
   - I-click ang "Search" button

3. **System Display Borrowed Books**
   - Nakikita ang lahat ng borrowed books ng member
   - Nakikita ang due date ng bawat libro
   - Nakikita kung overdue na ang libro
   - **System Action:** Tumatawag ng [`BorrowRecords::getActiveBorrowsByUser()`](App/Models/BorrowRecords.php:40)

4. **Librarian Click Return**
   - I-click ang "Return" button sa tabi ng libro
   - **System Action:** Tumatawag ng [`LibrarianController::return($recordId)`](App/Controllers/LibrarianController.php:101)

5. **System Validation**
   - **Check 1:** I-check kung existing ang loan record
     - Tumatawag ng [`BorrowRecords::getById()`](App/Models/BorrowRecords.php:14)
     - Kung hindi existing → Error: "Borrow record not found"
   - **Check 2:** I-check kung status ay 'Borrowed'
     - Kung hindi 'Borrowed' → Error: "Borrow record not found"

6. **System Compute Fine**
   - Tumatawag ng [`BorrowRecords::computeReturnFine()`](App/Models/BorrowRecords.php:120)
   - **Fine Calculation:**
     ```php
     if (strtotime($dueDate) >= time()) return 0;
     $days = (int)floor((time() - strtotime($dueDate)) / 86400);
     return $days * FINE_PER_DAY;  // ₱5 per day
     ```
   - **Example:** Kung 3 days overdue → Fine = 3 × ₱5 = ₱15

7. **System Update Loan Record**
   - Tumatawag ng [`BorrowRecords::markReturned()`](App/Models/BorrowRecords.php:136)
   - I-update sa `loan` table:
     ```sql
     UPDATE loan 
     SET status='Returned', return_date=CURDATE(), fine_amount=? 
     WHERE loan_id=?
     ```

8. **System Update Book Copies**
   - Tumatawag ng [`Books::incrementAvailable()`](App/Models/Books.php:1)
   - I-update ang `available_copies` ng book (plus 1)

9. **System Display Result**
   - Kung may fine: Flash message: "Book returned successfully. Fine: ₱{fine}"
   - Kung walang fine: Flash message: "Book returned successfully."
   - I-redirect sa student lookup page

---

### 💰 Workflow ng Pagbabayad ng Fine (Pay Fine)

**Sino ang gumagawa:** Librarian
**Controller:** [`LibrarianController::payFine()`](App/Controllers/LibrarianController.php:125)

#### Step-by-Step Process:

1. **Member Pay Fine**
   - Ang member ay pumupunta sa librarian
   - Ibibigay ang payment para sa fine

2. **Librarian Search Member**
   - I-click ang "Lookup Student" sa sidebar
   - I-type ang email ng member
   - I-click ang "Search" button

3. **System Display Fine Info**
   - Nakikita ang total outstanding fine
   - Nakikita ang mga overdue books
   - **System Action:** Tumatawag ng [`BorrowRecords::calculateFine()`](App/Models/BorrowRecords.php:103)

4. **Librarian Click Pay Fine**
   - I-click ang "Pay Fine" button
   - **System Action:** Tumatawag ng [`LibrarianController::payFine($userId)`](App/Controllers/LibrarianController.php:125)

5. **System Clear Fine**
   - Tumatawag ng [`BorrowRecords::clearFine()`](App/Models/BorrowRecords.php:144)
   - I-update sa `loan` table:
     ```sql
     UPDATE loan SET fine_amount=0 WHERE user_id=? AND fine_amount>0
     ```

6. **System Display Success**
   - Flash message: "Fine cleared successfully."
   - I-redirect sa student lookup page

---

### 👤 Workflow ng Pagdagdag ng User (Add User)

**Sino ang gumagawa:** Admin
**Controller:** [`UsersController::add()`](App/Controllers/UsersController.php:33)

#### Step-by-Step Process:

1. **Admin Click Add User**
   - I-click ang "Users" sa sidebar
   - I-click ang "Add User" button
   - **System Action:** Tumatawag ng [`UsersController::add()`](App/Controllers/UsersController.php:33)

2. **System Display Form**
   - Nakikita ang form na may fields:
     - Full Name (required)
     - Email (required)
     - Password (required, min 6 characters)
     - Role (required: admin, librarian, member)
     - Phone
     - Address

3. **Admin Fill Form**
   - I-fill ang lahat ng required fields
   - I-click ang "Add User" button

4. **System Validation**
   - **Check 1:** I-check kung lahat ng required fields ay filled
     - Kung may kulang → Error: "All fields are required."
   - **Check 2:** I-check kung valid ang email format
     - Tumatawag ng [`filter_var($email, FILTER_VALIDATE_EMAIL)`](App/Controllers/UsersController.php:47)
     - Kung invalid → Error: "Invalid email format."
   - **Check 3:** I-check kung ang password ay at least 6 characters
     - Kung masyadong maikli → Error: "Password must be at least 6 characters."

5. **System Create User**
   - Tumatawag ng [`Users::add()`](App/Models/Users.php:1)
   - I-hash ang password gamit ang `password_hash()`
   - I-insert sa `users` table:
     ```sql
     INSERT INTO users (fullname, email, password, role, phone, address, data_registered)
     VALUES (?, ?, ?, ?, ?, ?, CURDATE())
     ```

6. **System Display Success**
   - Flash message: "User added successfully."
   - I-redirect sa users list page

---

### 📚 Workflow ng Pagdagdag ng Libro (Add Book)

**Sino ang gumagawa:** Admin o Librarian
**Controller:** [`BookController::add()`](App/Controllers/BookController.php:32)

#### Step-by-Step Process:

1. **Admin/Librarian Click Add Book**
   - I-click ang "Books" sa sidebar
   - I-click ang "Add Book" button
   - **System Action:** Tumatawag ng [`BookController::add()`](App/Controllers/BookController.php:32)

2. **System Display Form**
   - Nakikita ang form na may fields:
     - Title (required)
     - Author
     - ISBN (optional, auto-generated kung empty)
     - Genre
     - Publication Year
     - Total Copies (default: 1)

3. **Admin/Librarian Fill Form**
   - I-fill ang title (required)
   - I-fill ang ibang fields (optional)
   - I-click ang "Add Book" button

4. **System Validation**
   - **Check 1:** I-check kung may title
     - Kung walang title → Error: "Title is required."

5. **System Generate ISBN**
   - Kung walang ISBN na provided:
     - Tumatawag ng [`BookController::generateUniqueISBN()`](App/Controllers/BookController.php:157)
     - Format: `{timestamp}-{random}` (e.g., "1711527189-3456")
     - I-check kung unique ang ISBN

6. **System Create Book**
   - Tumatawag ng [`Books::create()`](App/Models/Books.php:1)
   - I-insert sa `book` table:
     ```sql
     INSERT INTO book (isbn, title, author, genre, publication_year, total_copies, available_copies)
     VALUES (?, ?, ?, ?, ?, ?, ?)
     ```

7. **System Display Success**
   - Flash message: "Book added successfully."
   - I-redirect sa books list page

---

### 🔍 Workflow ng Student Lookup

**Sino ang gumagawa:** Admin o Librarian
**Controller:** [`LibrarianController::lookup()`](App/Controllers/LibrarianController.php:26)

#### Step-by-Step Process:

1. **Admin/Librarian Click Lookup**
   - I-click ang "Lookup Student" sa sidebar
   - **System Action:** Tumatawag ng [`LibrarianController::lookup()`](App/Controllers/LibrarianController.php:26)

2. **System Display Search Form**
   - Nakikita ang search box
   - Nakikita ang "Search" button

3. **Admin/Librarian Search Member**
   - I-type ang email ng member
   - I-click ang "Search" button

4. **System Search Member**
   - Tumatawag ng [`Users::findMember()`](App/Models/Users.php:1)
   - **Query:**
     ```sql
     SELECT * FROM users WHERE email = ? AND role = 'member'
     ```

5. **System Display Results**
   - **Kung found:**
     - Nakikita ang member details:
       - Full Name
       - Email
       - Phone
       - Address
     - Nakikita ang current borrowed books:
       - Book Title
       - Author
       - Borrow Date
       - Due Date
       - Status (Borrowed/Overdue)
     - Nakikita ang total outstanding fine
     - Nakikita ang "Issue Book" form
     - Nakikita ang "Return" button sa bawat borrowed book
     - Nakikita ang "Pay Fine" button kung may fine
   - **Kung not found:**
     - Error message: "Student '{email}' not found."

---

### 📊 Workflow ng Dashboard

**Sino ang gumagawa:** Admin, Librarian, o Member
**Controller:** [`DashboardController`](App/Controllers/DashboardController.php:10)

#### Admin Dashboard ([`DashboardController::admin()`](App/Controllers/DashboardController.php:26))

1. **Admin Click Dashboard**
   - I-click ang "Dashboard" sa sidebar
   - **System Action:** Tumatawag ng [`DashboardController::admin()`](App/Controllers/DashboardController.php:26)

2. **System Sync Overdue Books**
   - Tumatawag ng [`AppController::syncOverdue()`](App/Controllers/AppController.php:1)
   - I-update ang status ng mga overdue books:
     ```sql
     UPDATE loan SET status='Overdue' WHERE status='Borrowed' AND due_date < CURDATE()
     ```

3. **System Load Statistics**
   - Tumatawag ng [`BorrowRecords::getStats()`](App/Models/BorrowRecords.php:90)
   - **Query:**
     ```sql
     SELECT COUNT(*) FROM loan WHERE status='Borrowed'  -- Active borrows
     SELECT COUNT(*) FROM loan WHERE status='Overdue'   -- Overdue books
     SELECT COALESCE(SUM(fine_amount),0) FROM loan WHERE status IN ('Borrowed','Overdue') AND fine_amount>0  -- Pending fines
     ```

4. **System Load Recent Transactions**
   - Tumatawag ng [`BorrowRecords::getRecent(10)`](App/Models/BorrowRecords.php:75)
   - **Query:**
     ```sql
     SELECT u.fullname, b.title, l.status, l.borrow_date, l.return_date
     FROM loan l
     JOIN users u ON u.user_id = l.user_id
     JOIN book b ON b.book_id = l.book_id
     WHERE DATE(l.borrow_date) = CURDATE() OR DATE(l.return_date) = CURDATE()
     ORDER BY l.loan_id DESC LIMIT 10
     ```

5. **System Display Dashboard**
   - Nakikita ang statistics cards:
     - Active Borrows
     - Overdue Books
     - Pending Fines
     - Total Users
     - Total Books
   - Nakikita ang recent transactions table

#### Librarian Dashboard ([`DashboardController::librarian()`](App/Controllers/DashboardController.php:42))

1. **Librarian Click Dashboard**
   - I-click ang "Dashboard" sa sidebar
   - **System Action:** Tumatawag ng [`DashboardController::librarian()`](App/Controllers/DashboardController.php:42)

2. **System Sync Overdue Books**
   - Same as Admin Dashboard

3. **System Load Statistics**
   - Same as Admin Dashboard

4. **System Load Recent Transactions**
   - Same as Admin Dashboard

5. **System Display Dashboard**
   - Nakikita ang statistics cards:
     - Active Borrows
     - Overdue Books
     - Pending Fines
   - Nakikita ang recent transactions table

#### Member Dashboard ([`DashboardController::member()`](App/Controllers/DashboardController.php:56))

1. **Member Click Dashboard**
   - I-click ang "My Dashboard" sa sidebar
   - **System Action:** Tumatawag ng [`DashboardController::member()`](App/Controllers/DashboardController.php:56)

2. **System Load Borrowed Books**
   - Tumatawag ng [`BorrowRecords::getByUser($userId)`](App/Models/BorrowRecords.php:27)
   - **Query:**
     ```sql
     SELECT l.loan_id, l.*, b.title, b.author
     FROM loan l
     JOIN book b ON b.book_id = l.book_id
     WHERE l.user_id = ?
     ORDER BY l.borrow_date DESC
     ```

3. **System Display Dashboard**
   - Nakikita ang borrowed books table:
     - Book Title
     - Author
     - Borrow Date
     - Due Date
     - Status (Borrowed/Returned/Overdue)
     - Fine Amount (kung may fine)

---

### 🔐 Workflow ng Login

**Controller:** [`AuthController::login()`](App/Controllers/AuthController.php:18)

#### Step-by-Step Process:

1. **User Access System**
   - Buksan ang `http://localhost/LMS/public/`
   - **System Action:** Tumatawag ng [`Router`](App/Core/Router.php:5)
   - **System Action:** I-check kung logged in ang user
   - Kung hindi logged in → I-redirect sa login page

2. **System Display Login Form**
   - Nakikita ang login form:
     - Email/Username field
     - Password field
     - "Login" button

3. **User Fill Login Form**
   - I-type ang email o username
   - I-type ang password
   - I-click ang "Login" button

4. **System Validation**
   - **Check 1:** I-check kung na-reach ang rate limit
     - Tumatawag ng `$_SESSION[$key]`
     - Kung >= 5 attempts → Error: "Too many failed attempts. Please wait a few minutes."
   - **Check 2:** I-check kung filled ang lahat ng fields
     - Kung may kulang → Error: "All fields are required."
   - **Check 3:** I-check kung existing ang user
     - Tumatawag ng [`Users::findByIdentifier()`](App/Models/Users.php:1)
     - Kung hindi existing → Error: "Account not found. Check your credentials."
     - I-increment ang failed attempts counter
   - **Check 4:** I-check kung active ang account
     - Kung hindi active → Error: "Your account is inactive. Contact the librarian."
   - **Check 5:** I-check kung tama ang password
     - Tumatawag ng [`password_verify()`](App/Controllers/AuthController.php:50)
     - Kung mali → Error: "Incorrect password. {remaining} attempt(s) remaining."
     - I-increment ang failed attempts counter

5. **System Create Session**
   - I-reset ang failed attempts counter
   - I-regenerate ang session ID
   - I-set ang session variables:
     ```php
     $_SESSION['user_id'] = $user->id;
     $_SESSION['user_name'] = $user->fullname;
     $_SESSION['user_role'] = $user->role;
     $_SESSION['logged_in'] = true;
     ```

6. **System Redirect to Dashboard**
   - Tumatawag ng [`AuthController::redirectByRole()`](App/Controllers/AuthController.php:85)
   - **Redirect Logic:**
     ```php
     $map = [
         'admin'     => 'Dashboard/admin',
         'librarian' => 'Dashboard/librarian',
         'member'    => 'Dashboard/member',
     ];
     ```
   - I-redirect sa tamang dashboard base sa role

---

### 🚪 Workflow ng Logout

**Controller:** [`AuthController::logout()`](App/Controllers/AuthController.php:77)

#### Step-by-Step Process:

1. **User Click Logout**
   - I-click ang "Logout" button sa navbar
   - **System Action:** Tumatawag ng [`AuthController::logout()`](App/Controllers/AuthController.php:77)

2. **System Clear Session**
   - I-clear ang lahat ng session variables:
     ```php
     session_unset();
     ```
   - I-destroy ang session:
     ```php
     session_destroy();
     ```

3. **System Redirect to Login**
   - I-redirect sa login page:
     ```php
     header('Location: ' . BASE_URL . 'Auth/login');
     ```

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
