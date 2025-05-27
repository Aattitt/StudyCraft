# StudyCraft
A Course Automation System

**StudyCraft** is a lightweight, PHP-based educational dashboard built to manage users, assignments, and dashboards for students, teachers, and admins. It’s simple, straightforward, and built for small-scale deployment.

---

## Features

- Admin, Teacher, and Student Dashboards
- User Registration & Login
- Course and Assignment Management
- Responsive CSS Styling
- Modular PHP structure for easy expansion

---

## Getting Started

Follow these steps to get the project up and running locally.

### Prerequisites

- PHP >= 7.4
- MySQL or MariaDB
- Apache Server (XAMPP/LAMP recommended)
- Git (optional, for cloning the repo)

---

### Installation

1. **Clone or download the repo**

bash

git clone 

https://github.com/Aattitt/StudyCraft.git



Or download the ZIP and extract it.

2. **Move project to server root**

If you're using XAMPP, move the folder to:


C:/xampp/htdocs/StudyCraft


3. **Start Apache & MySQL from XAMPP**

4. **Create a database named \`studycraft_db\` in phpMyAdmin**

5. **Import the SQL script below to set up the users table**

---

### SQL Setup

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `password` varchar(32) NOT NULL,
  `userRole` varchar(20) DEFAULT 'student',
  `createdAt` timestamp DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

---

### File Structure


StudyCraft/

├── admindashboard.php

├── connect.php

├── courses_assignments.css

├── index.php

├── logout.php

├── profile.css

├── RegLog.php

├── studentdashboard.php

├── style.css

└── teacherdashboard.php



---

## Usage

- Go to \`http://localhost/StudyCraft/RegLog.php` in your browser
- Register/Login as a user
- Use dashboard features based on your role

---

## License

Apache 2.0 License. See [LICENSE](LICENSE) for more details.

---

## Author

**Atit Imtiaz**  
Built with grit, chanachur, and probably a few sleepless nights.

---

## Contributing

Feel free to fork this repo, make improvements, and submit pull requests. Just don’t summon anything you can’t banish, alright?

And also, credit na dile uput kore falaye chudbo kintu!
