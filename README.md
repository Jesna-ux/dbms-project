# 💰 Travel Expense Manager

A web-based Travel Expense Management System developed using PHP and MySQL to help users track, manage, and organize travel-related expenses efficiently.

## 🌟 Features

### Authentication & User Management

* **User Registration** - Create a new account securely
* **User Login** - Secure authentication using email and password
* **Session Management** - User sessions maintained throughout usage
* **Logout Functionality** - Securely end user sessions

### Expense Management

* **Add Expenses** - Record travel-related expenses
* **Edit Expenses** - Modify existing expense records
* **View Expenses** - Display all recorded expenses in an organized format
* **Expense Tracking** - Monitor travel spending effectively
* **Delete Expenses** - Remove unwanted expense entries

### Dashboard

* **Expense Overview** - View expense summaries
* **Quick Access Navigation** - Easy access to all modules
* **User-Friendly Interface** - Simple and intuitive design

### Database Integration

* **MySQL Database Connectivity**
* **Persistent Data Storage**
* **Efficient Record Management**

## 🛠️ Technologies Used

* **PHP** - Backend development
* **MySQL** - Database management
* **HTML5** - Structure and content
* **CSS3** - Styling and layout
* **JavaScript** - Client-side interactions
* **XAMPP** - Local development environment

## 📦 Project Structure

```text
travel_expense/
├── add_expense.php
├── connection.php
├── dashboard.php
├── edit_expense.php
├── expenses.php
├── index.php
├── logout.php
├── register.php
├── README.md
└── css/js files
```

## 🚀 Getting Started

### Prerequisites

* XAMPP
* PHP 8.x or later
* MySQL
* Web Browser

### Installation

1. Clone the repository:


2. Move the project folder into:

```text
C:\xampp\htdocs\
```

3. Start:

   * Apache
   * MySQL

from the XAMPP Control Panel.

4. Create a database in phpMyAdmin.

5. Update database credentials in:

```php
connection.php
```

Example:

```php
$host = "localhost";
$user = "root";
$password = "";
$db = "travel_expense";
```

6. Open your browser and visit:

```text
http://localhost/travel_expense/
```

## 📖 How to Use

### Register

1. Open the application.
2. Create a new account using the registration page.
3. Login using your credentials.

### Manage Expenses

1. Add new travel expenses.
2. View all recorded expenses.
3. Edit existing expense details.
4. Delete expenses when required.

### Dashboard

1. Access the dashboard after login.
2. Monitor and manage expense records.
3. Navigate through available modules.

## 🔐 Security Features

* User Authentication
* Session-Based Access Control
* Secure Logout Mechanism
* Protected User Data Management

## 🎯 Project Objectives

* Simplify travel expense tracking
* Improve expense organization
* Provide easy access to spending records
* Reduce manual expense management efforts
* Demonstrate practical implementation of PHP and MySQL concepts

## 🚧 Future Enhancements

* [ ] Expense category management
* [ ] Expense reports and analytics
* [ ] PDF export functionality
* [ ] Expense charts and visualizations
* [ ] Multi-user administration
* [ ] Budget planning tools
* [ ] Mobile-responsive enhancements
* [ ] Email notifications and reminders



## 📚 Academic Project

This project was developed as part of a Database Management System (DBMS) academic project to demonstrate:

* PHP-MySQL Integration
* CRUD Operations
* Database Connectivity
* User Authentication
* Web Application Development

---

💡 A simple and effective solution for managing travel expenses digitally.

"Database schema is not included in this repository. Create the required tables manually before running the application."
