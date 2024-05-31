

---

# Project Lagun - Admin Side

## Overview

**Project Lagun** is an online hotel reservation and billing system developed for a hotel business in Palawan. This repository contains the admin side of the project, which manages reservations, billing, and other administrative functions. This system is designed to streamline hotel operations and improve customer service efficiency.

## Features

- **Reservation Management**: View and manage hotel reservations with ease.
- **Billing System**: Automated billing for reservations and other hotel services.
- **User Management**: Add, edit, and remove administrative users.
- **Reports**: Generate reports on reservations, billing, and user activity.
- **Dashboard**: Overview of key metrics and system notifications.

## Technologies Used

- **Backend**: PHP
- **Frontend**: JavaScript, AJAX, jQuery
- **Database**: MySQL

## Installation

To set up and run the admin side of Project Lagun locally, follow these steps:

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/yourusername/project-lagun.git
   cd project-lagun
   ```

2. **Set Up the Database**:
   - Create a new MySQL database.
   - Import the SQL file located in the `sql` folder:
     ```sql
     source path/to/sql/project_lagun.sql;
     ```

3. **Configure the Database Connection**:
   - Update the database configuration in the project files (usually in a `config.php` or similar file) with your database credentials.

4. **Host the Project**:
   - Set up a local server environment (e.g., XAMPP, WAMP) and place the project files in the appropriate directory (e.g., `htdocs` for XAMPP).

5. **Access the Admin Panel**:
   - Open your web browser and navigate to `http://localhost/project-lagun/admin`.

## Usage

1. **Login**: Access the admin panel using your credentials.
2. **Manage Reservations**: View, approve, and cancel reservations from the dashboard.
3. **Billing**: Generate and manage invoices for reservations and other services.
4. **User Management**: Add new admin users, edit their details, or remove them as needed.
5. **Reports**: Generate and view detailed reports on various aspects of hotel management.

## Screenshots

![Dashboard](path/to/screenshot1.png)
*Dashboard overview*

![Reservation Management](path/to/screenshot2.png)
*Reservation management interface*

## Contributors

- **John Randel Carpio** - Lead Developer
- **Patricia Lopez** - Frontend 
- **Adarra Loreejane Fortin** - Documentation
- **Maverick Manahan** - Documentation
- **Mara Maduro** - Documentation

---
