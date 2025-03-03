# Educational-Resource-Management-System
# Educational Resource Management System

## Project Overview
The **Educational Resource Management System** is a web-based platform designed to manage educational resources such as textbooks, digital materials, and library collections. It enables administrators to add and manage resources, while students can browse and download available materials.

## Features
- **User Authentication**: Secure login and signup functionality for students and admins.
- **Admin Dashboard**:
  - Add new resources.
  - Delete resources.
  - View all uploaded resources.
- **Student Dashboard**:
  - Browse and download resources.
- **Filter and Search**:
  - Resources can be filtered by institution, department, and type.
- **Responsive Design**: Works on desktops, tablets, and mobile devices.

## Technologies Used
- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP
- **Database**: MySQL (XAMPP used for local server)

## Installation Guide
### Prerequisites
- Install **XAMPP** (or any local server with PHP & MySQL support)
- Clone or download this project from GitHub

### Steps to Set Up
1. **Move Files**: Copy the project folder to `htdocs` (inside the XAMPP installation directory).
2. **Create Database**:
   - Open `phpMyAdmin` in your browser (`http://localhost/phpmyadmin/`).
   - Create a new database named **`resource_management`**.
   - Import `database.sql` file (if provided) to set up tables.
3. **Configure Database Connection**:
   - Open `db.php` and ensure the database credentials match your local setup.
4. **Start the Server**:
   - Open **XAMPP Control Panel** and start **Apache** and **MySQL**.
5. **Access the Application**:
   - Open `http://localhost/your_project_folder/` in your web browser.

## File Structure
```
├── index.php              # Main dashboard page
├── login.php              # User login page
├── signup.php             # User registration page
├── logout.php             # Logout functionality
├── add_resource.php       # Admin page to upload resources
├── view_resources.php     # Page to browse and download resources
├── db.php                 # Database connection file
├── uploads/               # Directory where uploaded resources are stored
├── styles.css             # CSS styles (if separated)
└── flowchart.xml          # Flowchart representation of system workflow
```

## Usage
- **Admin Login**: Use admin credentials to log in and add/delete resources.
- **Student Login**: Students can log in to browse and download resources.
- **Logout**: Secure logout functionality to end sessions.

## Future Enhancements
- Implement user roles with finer permissions.
- Add search functionality.
- Improve UI/UX with modern design frameworks.
- Implement API support for resource retrieval.

## License
This project is open-source and available for modification and use.

---
**Author**: [Harshvardhan Dhane]  
**GitHub Repository**: [Your Repo Link]

