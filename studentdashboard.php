<?php
// Start output buffering to prevent "headers already sent" errors
ob_start();

// Start the session
session_start();

// Check if user is logged in
if(!isset($_SESSION['email'])){
    header("Location: index.php");
    exit();
}

// Get user data from database
include 'connect.php';
$email = $_SESSION['email'];
$sql = "SELECT * FROM users WHERE email='$email' AND userRole='student'";
$result = $conn->query($sql);

// If user is not a student, redirect
if($result->num_rows == 0){
    header("Location: index.php");
    exit();
}

$userData = $result->fetch_assoc();
$firstName = $userData['firstName'];
$lastName = $userData['lastName'];
$fullName = $firstName . ' ' . $lastName;
$userInitial = substr($firstName, 0, 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudyCraft - Student Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="courses_assignments.css">
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <header class="header">
        <span class="student-badge">Student</span>
        <h1>StudyCraft</h1>
        <p>Student Portal</p>
        <div class="user-info">
            <div class="user-avatar"><?php echo $userInitial; ?></div>
            <span>Welcome, <?php echo $fullName; ?></span>
        </div>
        <button class="theme-toggle" aria-label="Toggle dark mode"></button>
        <a href="logout.php" class="logout-btn">Logout</a>
    </header>
    
    <nav class="tabs">
        <button class="tab active" data-tab="dashboard">Dashboard</button>
        <button class="tab" data-tab="courses">My Courses</button>
        <button class="tab" data-tab="assignments">Assignments</button>
        <button class="tab" data-tab="grades">Grades</button>
        <button class="tab" data-tab="calendar">Calendar</button>
        <button class="tab" data-tab="profile">Profile</button>
        <button class="tab" data-tab="store">Store</button>
    </nav>
    
    <section id="dashboard" class="tab-content">
        <h2>Student Dashboard</h2>
        <div class="grid">
            <div class="card">
                <h3>Enrolled Courses</h3>
                <p>Active courses this semester</p>
                <div class="value">4</div>
            </div>
            
            <div class="card">
                <h3>Pending Assignments</h3>
                <p>Due within the next week</p>
                <div class="value">3</div>
            </div>
            
            <div class="card">
                <h3>Attendance Rate</h3>
                <p>Overall attendance percentage</p>
                <div class="value">95%</div>
            </div>
            
            <div class="card">
                <h3>Course Progress</h3>
                <p>Average completion across all courses</p>
                <div class="value">68%</div>
            </div>
            
            <div class="card">
                <h3>Discussion Posts</h3>
                <p>Your contributions this semester</p>
                <div class="value">12</div>
            </div>
        </div>
        
        <h2>Upcoming Deadlines</h2>
        <table>
            <thead>
                <tr>
                    <th>Assignment</th>
                    <th>Course</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Final Project</td>
                    <td>Web Development</td>
                    <td>May 10, 2025</td>
                    <td><span class="status status-warning">Pending</span></td>
                </tr>
                <tr>
                    <td>Research Paper</td>
                    <td>Database Design</td>
                    <td>May 12, 2025</td>
                    <td><span class="status status-warning">Pending</span></td>
                </tr>
                <tr>
                    <td>Midterm Exam</td>
                    <td>JavaScript Fundamentals</td>
                    <td>May 15, 2025</td>
                    <td><span class="status status-warning">Pending</span></td>
                </tr>
            </tbody>
        </table>
        
        <h2>Recent Announcements</h2>
        <div class="announcements">
            <div class="announcement">
                <h3>Important: Final Project Guidelines</h3>
                <p class="announcement-meta">Web Development • Posted by Md. Omar Faruq • May 3, 2025</p>
                <p>Please review the updated guidelines for the final project. I've added some clarifications about the required responsive design features.</p>
                <a href="#" class="read-more">Read more</a>
            </div>
            
            <div class="announcement">
                <h3>Reminder: Database Design Lab</h3>
                <p class="announcement-meta">Database Design • Posted by Md. Arafat Ibna Mizan • May 2, 2025</p>
                <p>Remember to bring your SQL cheat sheets to tomorrow's lab session. We'll be working on complex join operations.</p>
                <a href="#" class="read-more">Read more</a>
            </div>
        </div>
    </section>

    <!-- My Courses Tab -->
<section id="courses" class="tab-content">
    <h2>My Courses</h2>
    
    <div class="course-semester-selection">
        <h3>Spring 2025</h3>
        <select id="semester-selector">
            <option value="spring-2025">Spring 2025 (Current)</option>
            <option value="fall-2024">Fall 2024</option>
            <option value="summer-2024">Summer 2024</option>
        </select>
    </div>
    
    <div class="courses-grid">
        <!-- Course 1 -->
        <div class="course-card active-course">
            <div class="course-header">
                <img src="/api/placeholder/80/80" alt="Web Development" class="course-icon">
                <div class="course-header-info">
                    <h3>Web Development</h3>
                    <p class="course-code">CS301</p>
                </div>
                <span class="progress-indicator" title="75% Complete">
                    <svg viewBox="0 0 36 36" class="circular-chart">
                        <path class="circle-bg" d="M18 2.0845
                            a 15.9155 15.9155 0 0 1 0 31.831
                            a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                        <path class="circle" stroke-dasharray="75, 100" d="M18 2.0845
                            a 15.9155 15.9155 0 0 1 0 31.831
                            a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                        <text x="18" y="20.35" class="percentage">75%</text>
                    </svg>
                </span>
            </div>
            <div class="course-body">
                <p class="course-instructor">Md. Omar Faruq</p>
                <p class="course-schedule">Mon, Wed, Fri • 10:00 AM - 11:30 AM</p>
                <p class="course-location">Room 204, Technology Building</p>
                <div class="course-stats">
                    <div class="stat">
                        <span class="stat-value">A-</span>
                        <span class="stat-label">Current Grade</span>
                    </div>
                    <div class="stat">
                        <span class="stat-value">9/12</span>
                        <span class="stat-label">Classes Attended</span>
                    </div>
                    <div class="stat">
                        <span class="stat-value">2</span>
                        <span class="stat-label">Pending Assignments</span>
                    </div>
                </div>
            </div>
            <div class="course-footer">
                <a href="#" class="course-action view-materials">View Materials</a>
                <a href="#" class="course-action join-class">Join Class</a>
            </div>
        </div>
        
        <!-- Course 2 -->
        <div class="course-card active-course">
            <div class="course-header">
                <img src="/api/placeholder/80/80" alt="Database Design" class="course-icon">
                <div class="course-header-info">
                    <h3>Database Design</h3>
                    <p class="course-code">CS340</p>
                </div>
                <span class="progress-indicator" title="65% Complete">
                    <svg viewBox="0 0 36 36" class="circular-chart">
                        <path class="circle-bg" d="M18 2.0845
                            a 15.9155 15.9155 0 0 1 0 31.831
                            a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                        <path class="circle" stroke-dasharray="65, 100" d="M18 2.0845
                            a 15.9155 15.9155 0 0 1 0 31.831
                            a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                        <text x="18" y="20.35" class="percentage">65%</text>
                    </svg>
                </span>
            </div>
            <div class="course-body">
                <p class="course-instructor">Md. Arafat Ibna Mizan</p>
                <p class="course-schedule">Tue, Thu • 1:00 PM - 3:00 PM</p>
                <p class="course-location">Room 150, Computing Lab</p>
                <div class="course-stats">
                    <div class="stat">
                        <span class="stat-value">B+</span>
                        <span class="stat-label">Current Grade</span>
                    </div>
                    <div class="stat">
                        <span class="stat-value">7/8</span>
                        <span class="stat-label">Classes Attended</span>
                    </div>
                    <div class="stat">
                        <span class="stat-value">1</span>
                        <span class="stat-label">Pending Assignments</span>
                    </div>
                </div>
            </div>
            <div class="course-footer">
                <a href="#" class="course-action view-materials">View Materials</a>
                <a href="#" class="course-action join-class">Join Class</a>
            </div>
        </div>
        
        <!-- Course 3 -->
        <div class="course-card active-course">
            <div class="course-header">
                <img src="/api/placeholder/80/80" alt="JavaScript Fundamentals" class="course-icon">
                <div class="course-header-info">
                    <h3>JavaScript Fundamentals</h3>
                    <p class="course-code">CS215</p>
                </div>
                <span class="progress-indicator" title="80% Complete">
                    <svg viewBox="0 0 36 36" class="circular-chart">
                        <path class="circle-bg" d="M18 2.0845
                            a 15.9155 15.9155 0 0 1 0 31.831
                            a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                        <path class="circle" stroke-dasharray="80, 100" d="M18 2.0845
                            a 15.9155 15.9155 0 0 1 0 31.831
                            a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                        <text x="18" y="20.35" class="percentage">80%</text>
                    </svg>
                </span>
            </div>
            <div class="course-body">
                <p class="course-instructor">Aroni Saha Prapty</p>
                <p class="course-schedule">Tue, Thu • 9:30 AM - 11:00 AM</p>
                <p class="course-location">Room 302, Computer Science Building</p>
                <div class="course-stats">
                    <div class="stat">
                        <span class="stat-value">A</span>
                        <span class="stat-label">Current Grade</span>
                    </div>
                    <div class="stat">
                        <span class="stat-value">10/10</span>
                        <span class="stat-label">Classes Attended</span>
                    </div>
                    <div class="stat">
                        <span class="stat-value">0</span>
                        <span class="stat-label">Pending Assignments</span>
                    </div>
                </div>
            </div>
            <div class="course-footer">
                <a href="#" class="course-action view-materials">View Materials</a>
                <a href="#" class="course-action join-class">Join Class</a>
            </div>
        </div>
        
        <!-- Course 4 -->
        <div class="course-card active-course">
            <div class="course-header">
                <img src="/api/placeholder/80/80" alt="Mobile App Development" class="course-icon">
                <div class="course-header-info">
                    <h3>Mobile App Development</h3>
                    <p class="course-code">CS450</p>
                </div>
                <span class="progress-indicator" title="50% Complete">
                    <svg viewBox="0 0 36 36" class="circular-chart">
                        <path class="circle-bg" d="M18 2.0845
                            a 15.9155 15.9155 0 0 1 0 31.831
                            a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                        <path class="circle" stroke-dasharray="50, 100" d="M18 2.0845
                            a 15.9155 15.9155 0 0 1 0 31.831
                            a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                        <text x="18" y="20.35" class="percentage">50%</text>
                    </svg>
                </span>
            </div>
            <div class="course-body">
                <p class="course-instructor">Muhammad Muhtasim</p>
                <p class="course-schedule">Mon, Wed • 2:00 PM - 4:00 PM</p>
                <p class="course-location">Room 120, Technology Building</p>
                <div class="course-stats">
                    <div class="stat">
                        <span class="stat-value">B</span>
                        <span class="stat-label">Current Grade</span>
                    </div>
                    <div class="stat">
                        <span class="stat-value">8/9</span>
                        <span class="stat-label">Classes Attended</span>
                    </div>
                    <div class="stat">
                        <span class="stat-value">1</span>
                        <span class="stat-label">Pending Assignments</span>
                    </div>
                </div>
            </div>
            <div class="course-footer">
                <a href="#" class="course-action view-materials">View Materials</a>
                <a href="#" class="course-action join-class">Join Class</a>
            </div>
        </div>
    </div>
    
    <div class="courses-summary">
        <h3>Semester Summary</h3>
        <div class="summary-grid">
            <div class="summary-card">
                <h4>Credits</h4>
                <div class="summary-value">12</div>
                <p>Total Credits</p>
            </div>
            <div class="summary-card">
                <h4>GPA</h4>
                <div class="summary-value">3.75</div>
                <p>Current Semester</p>
            </div>
            <div class="summary-card">
                <h4>Completion</h4>
                <div class="summary-value">68%</div>
                <p>Average Progress</p>
            </div>
            <div class="summary-card">
                <h4>Attendance</h4>
                <div class="summary-value">95%</div>
                <p>Overall Rate</p>
            </div>
        </div>
    </div>
</section>

<section id="assignments" class="tab-content">
    <h2>Assignments</h2>
    
    <div class="assignments-filters">
        <div class="filter-group">
            <label for="assignment-status">Status:</label>
            <select id="assignment-status">
                <option value="all">All Assignments</option>
                <option value="pending" selected>Pending</option>
                <option value="submitted">Submitted</option>
                <option value="graded">Graded</option>
                <option value="missed">Missed</option>
            </select>
        </div>
        <div class="filter-group">
            <label for="assignment-course">Course:</label>
            <select id="assignment-course">
                <option value="all" selected>All Courses</option>
                <option value="cs301">CS301 - Web Development</option>
                <option value="cs340">CS340 - Database Design</option>
                <option value="cs215">CS215 - JavaScript Fundamentals</option>
                <option value="cs450">CS450 - Mobile App Development</option>
            </select>
        </div>
        <div class="filter-group">
            <label for="assignment-sort">Sort By:</label>
            <select id="assignment-sort">
                <option value="due-date-asc" selected>Due Date (Earliest)</option>
                <option value="due-date-desc">Due Date (Latest)</option>
                <option value="title-asc">Title (A-Z)</option>
                <option value="title-desc">Title (Z-A)</option>
                <option value="points-asc">Points (Low to High)</option>
                <option value="points-desc">Points (High to Low)</option>
            </select>
        </div>
    </div>
    
    <div class="assignments-list">
        <!-- Assignment 1 -->
        <div class="assignment-card pending">
            <div class="assignment-status-indicator">
                <span class="status-dot"></span>
                <span class="status-text">Pending</span>
            </div>
            <div class="assignment-header">
                <h3>Final Project</h3>
                <span class="course-badge">Web Development</span>
            </div>
            <div class="assignment-details">
                <div class="assignment-info">
                    <div class="info-item">
                        <span class="info-label">Due Date:</span>
                        <span class="info-value">May 10, 2025</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Points:</span>
                        <span class="info-value">100</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Weight:</span>
                        <span class="info-value">25%</span>
                    </div>
                </div>
                <p class="assignment-description">
                    Create a full-stack web application using the technologies learned throughout the semester. Your project should include a backend API, frontend user interface, and database integration.
                </p>
            </div>
            <div class="assignment-actions">
                <a href="#" class="assignment-action view-details">View Details</a>
                <a href="#" class="assignment-action submit-work">Submit Work</a>
                <a href="#" class="assignment-action get-help">Get Help</a>
            </div>
            <div class="assignment-time-remaining">
                <div class="countdown">
                    <span class="countdown-value">2</span>
                    <span class="countdown-label">days</span>
                </div>
                <div class="countdown">
                    <span class="countdown-value">8</span>
                    <span class="countdown-label">hours</span>
                </div>
                <div class="countdown">
                    <span class="countdown-value">45</span>
                    <span class="countdown-label">minutes</span>
                </div>
            </div>
        </div>
        
        <!-- Assignment 2 -->
        <div class="assignment-card pending">
            <div class="assignment-status-indicator">
                <span class="status-dot"></span>
                <span class="status-text">Pending</span>
            </div>
            <div class="assignment-header">
                <h3>Research Paper</h3>
                <span class="course-badge">Database Design</span>
            </div>
            <div class="assignment-details">
                <div class="assignment-info">
                    <div class="info-item">
                        <span class="info-label">Due Date:</span>
                        <span class="info-value">May 12, 2025</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Points:</span>
                        <span class="info-value">75</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Weight:</span>
                        <span class="info-value">20%</span>
                    </div>
                </div>
                <p class="assignment-description">
                    Write a research paper on a modern database technology. Compare and contrast with traditional relational databases and provide use cases for your chosen technology.
                </p>
            </div>
            <div class="assignment-actions">
                <a href="#" class="assignment-action view-details">View Details</a>
                <a href="#" class="assignment-action submit-work">Submit Work</a>
                <a href="#" class="assignment-action get-help">Get Help</a>
            </div>
            <div class="assignment-time-remaining">
                <div class="countdown">
                    <span class="countdown-value">4</span>
                    <span class="countdown-label">days</span>
                </div>
                <div class="countdown">
                    <span class="countdown-value">12</span>
                    <span class="countdown-label">hours</span>
                </div>
                <div class="countdown">
                    <span class="countdown-value">30</span>
                    <span class="countdown-label">minutes</span>
                </div>
            </div>
        </div>
        
        <!-- Assignment 3 -->
        <div class="assignment-card pending">
            <div class="assignment-status-indicator">
                <span class="status-dot"></span>
                <span class="status-text">Pending</span>
            </div>
            <div class="assignment-header">
                <h3>Midterm Exam</h3>
                <span class="course-badge">JavaScript Fundamentals</span>
            </div>
            <div class="assignment-details">
                <div class="assignment-info">
                    <div class="info-item">
                        <span class="info-label">Due Date:</span>
                        <span class="info-value">May 15, 2025</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Points:</span>
                        <span class="info-value">150</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Weight:</span>
                        <span class="info-value">30%</span>
                    </div>
                </div>
                <p class="assignment-description">
                    Online midterm exam covering JavaScript fundamentals, ES6 features, asynchronous programming, and DOM manipulation. The exam will include multiple-choice and coding problems.
                </p>
            </div>
            <div class="assignment-actions">
                <a href="#" class="assignment-action view-details">View Details</a>
                <a href="#" class="assignment-action take-exam">Take Exam</a>
                <a href="#" class="assignment-action get-help">Get Help</a>
            </div>
            <div class="assignment-time-remaining">
                <div class="countdown">
                    <span class="countdown-value">7</span>
                    <span class="countdown-label">days</span>
                </div>
                <div class="countdown">
                    <span class="countdown-value">5</span>
                    <span class="countdown-label">hours</span>
                </div>
                <div class="countdown">
                    <span class="countdown-value">15</span>
                    <span class="countdown-label">minutes</span>
                </div>
            </div>
        </div>
        
        <!-- Assignment 4 (Submitted) -->
        <div class="assignment-card submitted">
            <div class="assignment-status-indicator">
                <span class="status-dot"></span>
                <span class="status-text">Submitted</span>
            </div>
            <div class="assignment-header">
                <h3>UI Prototype</h3>
                <span class="course-badge">Mobile App Development</span>
            </div>
            <div class="assignment-details">
                <div class="assignment-info">
                    <div class="info-item">
                        <span class="info-label">Due Date:</span>
                        <span class="info-value">May 5, 2025</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Points:</span>
                        <span class="info-value">50</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Weight:</span>
                        <span class="info-value">15%</span>
                    </div>
                </div>
                <p class="assignment-description">
                    Create a high-fidelity UI prototype for a mobile application. Include wireframes, user flows, and interactive elements.
                </p>
            </div>
            <div class="assignment-actions">
                <a href="#" class="assignment-action view-details">View Details</a>
                <a href="#" class="assignment-action view-submission">View Submission</a>
                <a href="#" class="assignment-action track-grade">Track Grade</a>
            </div>
            <div class="assignment-submitted-info">
                <span class="submitted-date">Submitted on May 4, 2025</span>
                <span class="submitted-status">Pending Review</span>
            </div>
        </div>
        
        <!-- Assignment 5 (Graded) -->
        <div class="assignment-card graded">
            <div class="assignment-status-indicator">
                <span class="status-dot"></span>
                <span class="status-text">Graded</span>
            </div>
            <div class="assignment-header">
                <h3>Database Schema Design</h3>
                <span class="course-badge">Database Design</span>
            </div>
            <div class="assignment-details">
                <div class="assignment-info">
                    <div class="info-item">
                        <span class="info-label">Due Date:</span>
                        <span class="info-value">April 25, 2025</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Points:</span>
                        <span class="info-value">40</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Weight:</span>
                        <span class="info-value">10%</span>
                    </div>
                </div>
                <p class="assignment-description">
                    Design a normalized database schema for an e-commerce application. Include ER diagrams and implementation in SQL.
                </p>
            </div>
            <div class="assignment-actions">
                <a href="#" class="assignment-action view-details">View Details</a>
                <a href="#" class="assignment-action view-submission">View Submission</a>
                <a href="#" class="assignment-action view-feedback">View Feedback</a>
            </div>
            <div class="assignment-grade">
                <div class="grade-circle">
                    <span class="grade-value">A</span>
                    <span class="grade-points">38/40</span>
                </div>
                <div class="grade-details">
                    <div class="grade-comment">
                        <span class="comment-icon">💬</span>
                        <span class="comment-text">Excellent work! Your schema is well-normalized and your documentation is clear.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="assignments-metrics">
        <h3>Current Semester Progress</h3>
        <div class="metrics-grid">
            <div class="metric-card">
                <h4>Assignments</h4>
                <div class="metric-chart">
                    <svg viewBox="0 0 36 36" class="circular-chart">
                        <path class="circle-bg" d="M18 2.0845
                            a 15.9155 15.9155 0 0 1 0 31.831
                            a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                        <path class="circle" stroke-dasharray="60, 100" d="M18 2.0845
                            a 15.9155 15.9155 0 0 1 0 31.831
                            a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                        <text x="18" y="18.35" class="percentage">60%</text>
                        <text x="18" y="23" class="percentage-label"></text>
                    </svg>
                </div>
                <div class="metric-details">
                    <div class="metric-stat">
                        <span class="stat-label">Completed</span>
                        <span class="stat-value">6</span>
                    </div>
                    <div class="metric-stat">
                        <span class="stat-label">Pending</span>
                        <span class="stat-value">3</span>
                    </div>
                    <div class="metric-stat">
                        <span class="stat-label">Upcoming</span>
                        <span class="stat-value">1</span>
                    </div>
                </div>
            </div>
            
            <div class="metric-card">
                <h4>Performance</h4>
                <div class="performance-chart">
                    <div class="performance-bar">
                        <div class="performance-label">A</div>
                        <div class="performance-track">
                            <div class="performance-value" style="width: 40%"></div>
                        </div>
                        <div class="performance-percent">40%</div>
                    </div>
                    <div class="performance-bar">
                        <div class="performance-label">B</div>
                        <div class="performance-track">
                            <div class="performance-value" style="width: 30%"></div>
                        </div>
                        <div class="performance-percent">30%</div>
                    </div>
                    <div class="performance-bar">
                        <div class="performance-label">C</div>
                        <div class="performance-track">
                            <div class="performance-value" style="width: 20%"></div>
                        </div>
                        <div class="performance-percent">20%</div>
                    </div>
                    <div class="performance-bar">
                        <div class="performance-label">D</div>
                        <div class="performance-track">
                            <div class="performance-value" style="width: 10%"></div>
                        </div>
                        <div class="performance-percent">10%</div>
                    </div>
                    <div class="performance-bar">
                        <div class="performance-label">F</div>
                        <div class="performance-track">
                            <div class="performance-value" style="width: 0%"></div>
                        </div>
                        <div class="performance-percent">0%</div>
                    </div>
                </div>
            </div>
            
            <div class="metric-card">
                <h4>Time Management</h4>
                <div class="time-management-stats">
                    <div class="time-stat">
                        <div class="time-icon">⏱️</div>
                        <div class="time-values">
                            <div class="time-value">85%</div>
                            <div class="time-label">On-time Submissions</div>
                        </div>
                    </div>
                    <div class="time-stat">
                        <div class="time-icon">🔄</div>
                        <div class="time-values">
                            <div class="time-value">3.5</div>
                            <div class="time-label">Avg. Days Before Deadline</div>
                        </div>
                    </div>
                    <div class="time-stat">
                        <div class="time-icon">⚠️</div>
                        <div class="time-values">
                            <div class="time-value">2</div>
                            <div class="time-label">Late Submissions</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Grades Tab -->
<section id="grades" class="tab-content">
    <h2>Academic Performance</h2>
    
    <div class="semester-selection">
        <label for="grade-course">Course</label>
        <select id="grade-course">
            <option value="web-dev">Web Development</option>
            <option value="db-design">Database Design</option>
            <option value="js-fund">JavaScript Fundamentals</option>
            <option value="mad">Mobile App Development</option>
        </select>
    </div>
    
    <div class="grades-overview">
        <div class="grades-summary">
            <div class="summary-card">
                <h3>Total Classes</h3>
                <div class="gpa-display">
                    <div class="gpa-value">21</div>
                    <div class="gpa-scale">/ 28</div>
                </div>
                <div class="gpa-letter">Good</div>
            </div>
            
            <div class="summary-card">
                <h3>Total Assignments</h3>
                <div class="gpa-display">
                    <div class="gpa-value">4</div>
                    <div class="gpa-scale">/ 12</div>
                </div>
                <div class="gpa-letter">Need Improvement</div>
            </div>
            
            <div class="summary-card">
                <h3>Personal Score</h3>
                <div class="credits-display">
                    <div class="credits-current">67</div>
                    <div class="credits-total">/ 100</div>
                </div>
                <div class="credits-percent">Average</div>
            </div>
            
            <div class="summary-card">
                <h3>Ranking</h3>
                <div class="ranking-display">
                    <div class="ranking-value">Top 15%</div>
                </div>
                <div class="ranking-details">Class of 2026</div>
            </div>
        </div>
        
        <div class="grades-chart">
            <h3>GPA Trend</h3>
            <div class="chart-container">
                <canvas id="gpaChart"></canvas>
            </div>
        </div>
    </div>
    
    <h3>Course Grades</h3>
    <div class="course-grades-container">
        <table class="course-grades-table">
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Instructor</th>
                    <th>Assignments</th>
                    <th>Avg Grade</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Web Development</td>
                    <td>Md. Omar Faruq</td>
                    <td>90%</td>
                    <td><span class="grade-badge grade-a">A-</span></td>
                </tr>
                <tr>
                    <td>Database Design</td>
                    <td>Md. Arafat Ibna Mizan</td>
                    <td>86%</td>
                    <td><span class="grade-badge grade-b">B+</span></td>
                </tr>
                <tr>
                    <td>JavaScript Fundamentals</td>
                    <td>Aroni Saha Prapty</td>
                    <td>94%</td>
                    <td><span class="grade-badge grade-a">A</span></td>
                </tr>
                <tr>
                    <td>Mobile App Development</td>
                    <td>Muhammad Muhtasim</td>
                    <td>84%</td>
                    <td><span class="grade-badge grade-b">B</span></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">Average</td>
                    <td>88.5%</td>
                    <td colspan="4">A-</td>
                    </tr>
            </tfoot>
        </table>
    </div>
    
    <div class="grade-distribution">
        <h3>Grade Distribution</h3>
        <div class="distribution-container">
            <div class="distribution-chart">
                <canvas id="gradeDistributionChart"></canvas>
            </div>
            <div class="distribution-legend">
                <div class="legend-item">
                    <span class="legend-color a-color"></span>
                    <span class="legend-label">A Range (90-100%)</span>
                    <span class="legend-count">1 Course</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color b-color"></span>
                    <span class="legend-label">B Range (80-89%)</span>
                    <span class="legend-count">2 Courses</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color c-color"></span>
                    <span class="legend-label">C Range (70-79%)</span>
                    <span class="legend-count">0 Courses</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color d-color"></span>
                    <span class="legend-label">D Range (60-69%)</span>
                    <span class="legend-count">0 Courses</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color f-color"></span>
                    <span class="legend-label">F Range (0-59%)</span>
                    <span class="legend-count">0 Courses</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="academic-standing">
        <h3>Academic Standing</h3>
        <div class="standing-info">
            <div class="standing-status">
                <span class="standing-indicator good"></span>
                <span class="standing-text">Good Standing</span>
            </div>
            <p class="standing-description">
                You are currently in good academic standing. Keep up the excellent work!
            </p>
            <div class="standing-requirements">
                <h4>Academic Standing Requirements</h4>
                <ul>
                    <li>Maintain a minimum cumulative Assignments of 70%</li>
                    <li>Pass at least 67% of attempted classes</li>
                    </ul>
            </div>
        </div>
    </div>
</section>

<!-- Calender Tab -->
<section id="calendar" class="tab-content">
    <h2>Academic Calendar</h2>
    
    <div class="calendar-controls">
        <div class="calendar-navigation">
            <button id="prev-month" class="calendar-nav-btn">&lt; Previous</button>
            <h3 id="current-month">May 2025</h3>
            <button id="next-month" class="calendar-nav-btn">Next &gt;</button>
        </div>
        <div class="calendar-view-options">
            <button class="view-btn active" data-view="month">Month</button>
            <button class="view-btn" data-view="week">Week</button>
            <button class="view-btn" data-view="day">Day</button>
            <button class="view-btn" data-view="agenda">Agenda</button>
        </div>
    </div>
    
    <div class="calendar-container">
        <div class="month-view active-view">
            <div class="calendar-grid">
                <div class="calendar-header">
                    <div class="calendar-cell">Sunday</div>
                    <div class="calendar-cell">Monday</div>
                    <div class="calendar-cell">Tuesday</div>
                    <div class="calendar-cell">Wednesday</div>
                    <div class="calendar-cell">Thursday</div>
                    <div class="calendar-cell">Friday</div>
                    <div class="calendar-cell">Saturday</div>
                </div>
                <div class="calendar-body">
                    <!-- Week 1 -->
                    <div class="calendar-cell prev-month">27</div>
                    <div class="calendar-cell prev-month">28</div>
                    <div class="calendar-cell prev-month">29</div>
                    <div class="calendar-cell prev-month">30</div>
                    <div class="calendar-cell">1
                        <div class="event class-event">
                            DB Design, 1-3pm
                        </div>
                    </div>
                    <div class="calendar-cell">2
                        <div class="event assignment-event">
                            UI Prototype Due
                        </div>
                    </div>
                    <div class="calendar-cell">3</div>
                    
                    <!-- Week 2 -->
                    <div class="calendar-cell">4</div>
                    <div class="calendar-cell">5
                        <div class="event class-event">
                            Web Dev, 10-11:30am
                        </div>
                        <div class="event class-event">
                            Mobile App, 2-4pm
                        </div>
                    </div>
                    <div class="calendar-cell">6
                        <div class="event class-event">
                            JS Fund, 9:30-11am
                        </div>
                        <div class="event class-event">
                            DB Design, 1-3pm
                        </div>
                    </div>
                    <div class="calendar-cell">7
                        <div class="event class-event">
                            Web Dev, 10-11:30am
                        </div>
                        <div class="event class-event">
                            Mobile App, 2-4pm
                        </div>
                    </div>
                    <div class="calendar-cell">8
                        <div class="event class-event">
                            JS Fund, 9:30-11am
                        </div>
                        <div class="event class-event">
                            DB Design, 1-3pm
                        </div>
                    </div>
                    <div class="calendar-cell">9
                        <div class="event class-event">
                            Web Dev, 10-11:30am
                        </div>
                    </div>
                    <div class="calendar-cell">10
                        <div class="event assignment-event">
                            Final Project Due
                        </div>
                    </div>
                    
                    <!-- Week 3 -->
                    <div class="calendar-cell">11</div>
                    <div class="calendar-cell current-day">12
                        <div class="event class-event">
                            Web Dev, 10-11:30am
                        </div>
                        <div class="event class-event">
                            Mobile App, 2-4pm
                        </div>
                        <div class="event assignment-event">
                            Research Paper Due
                        </div>
                    </div>
                    <div class="calendar-cell">13
                        <div class="event class-event">
                            JS Fund, 9:30-11am
                        </div>
                        <div class="event class-event">
                            DB Design, 1-3pm
                        </div>
                    </div>
                    <div class="calendar-cell">14
                        <div class="event class-event">
                            Web Dev, 10-11:30am
                        </div>
                        <div class="event class-event">
                            Mobile App, 2-4pm
                        </div>
                    </div>
                    <div class="calendar-cell">15
                        <div class="event class-event">
                            JS Fund, 9:30-11am
                        </div>
                        <div class="event class-event">
                            DB Design, 1-3pm
                        </div>
                        <div class="event exam-event">
                            JS Midterm Exam
                        </div>
                    </div>
                    <div class="calendar-cell">16
                        <div class="event class-event">
                            Web Dev, 10-11:30am
                        </div>
                    </div>
                    <div class="calendar-cell">17</div>
                    
                    <!-- Week 4 -->
                    <div class="calendar-cell">18</div>
                    <div class="calendar-cell">19
                        <div class="event class-event">
                            Web Dev, 10-11:30am
                        </div>
                        <div class="event class-event">
                            Mobile App, 2-4pm
                        </div>
                    </div>
                    <div class="calendar-cell">20
                        <div class="event class-event">
                            JS Fund, 9:30-11am
                        </div>
                        <div class="event class-event">
                            DB Design, 1-3pm
                        </div>
                    </div>
                    <div class="calendar-cell">21
                        <div class="event class-event">
                            Web Dev, 10-11:30am
                        </div>
                        <div class="event class-event">
                            Mobile App, 2-4pm
                        </div>
                    </div>
                    <div class="calendar-cell">22
                        <div class="event class-event">
                            JS Fund, 9:30-11am
                        </div>
                        <div class="event class-event">
                            DB Design, 1-3pm
                        </div>
                    </div>
                    <div class="calendar-cell">23
                        <div class="event class-event">
                            Web Dev, 10-11:30am
                        </div>
                        <div class="event campus-event">
                            Tech Fair, 12-4pm
                        </div>
                    </div>
                    <div class="calendar-cell">24</div>
                    
                    <!-- Week 5 -->
                    <div class="calendar-cell">25</div>
                    <div class="calendar-cell">26
                        <div class="event class-event">
                            Web Dev, 10-11:30am
                        </div>
                        <div class="event class-event">
                            Mobile App, 2-4pm
                        </div>
                    </div>
                    <div class="calendar-cell">27
                        <div class="event class-event">
                            JS Fund, 9:30-11am
                        </div>
                        <div class="event class-event">
                            DB Design, 1-3pm
                        </div>
                        <div class="event assignment-event">
                            Mobile App Prototype Due
                        </div>
                    </div>
                    <div class="calendar-cell">28
                        <div class="event class-event">
                            Web Dev, 10-11:30am
                        </div>
                        <div class="event class-event">
                            Mobile App, 2-4pm
                        </div>
                    </div>
                    <div class="calendar-cell">29
                        <div class="event class-event">
                            JS Fund, 9:30-11am
                        </div>
                        <div class="event class-event">
                            DB Design, 1-3pm
                        </div>
                    </div>
                    <div class="calendar-cell">30
                        <div class="event class-event">
                            Web Dev, 10-11:30am
                        </div>
                    </div>
                    <div class="calendar-cell">31</div>
                </div>
            </div>
        </div>
        
        <div class="agenda-view">
            <!-- This would be shown when the Agenda button is clicked -->
            <div class="event-list">
                <h3>Upcoming Events</h3>
                
                <div class="event-group">
                    <h4 class="event-date">Today - May 12, 2025</h4>
                    <div class="event-item">
                        <div class="event-time">10:00 AM - 11:30 AM</div>
                        <div class="event-content">
                            <div class="event-title">Web Development Class</div>
                            <div class="event-location">Room 204, Technology Building</div>
                        </div>
                    </div>
                    <div class="event-item">
                        <div class="event-time">2:00 PM - 4:00 PM</div>
                        <div class="event-content">
                            <div class="event-title">Mobile App Development Class</div>
                            <div class="event-location">Room 120, Technology Building</div>
                        </div>
                    </div>
                    <div class="event-item deadline">
                        <div class="event-time">11:59 PM</div>
                        <div class="event-content">
                            <div class="event-title">Research Paper Due</div>
                            <div class="event-location">Database Design</div>
                        </div>
                    </div>
                </div>
                
                <div class="event-group">
                    <h4 class="event-date">Tomorrow - May 13, 2025</h4>
                    <div class="event-item">
                        <div class="event-time">9:30 AM - 11:00 AM</div>
                        <div class="event-content">
                            <div class="event-title">JavaScript Fundamentals Class</div>
                            <div class="event-location">Room 302, Computer Science Building</div>
                        </div>
                    </div>
                    <div class="event-item">
                        <div class="event-time">1:00 PM - 3:00 PM</div>
                        <div class="event-content">
                            <div class="event-title">Database Design Class</div>
                            <div class="event-location">Room 150, Computing Lab</div>
                        </div>
                    </div>
                </div>
                
                <div class="event-group">
                    <h4 class="event-date">Thursday - May 15, 2025</h4>
                    <div class="event-item">
                        <div class="event-time">9:30 AM - 11:00 AM</div>
                        <div class="event-content">
                            <div class="event-title">JavaScript Fundamentals Class</div>
                            <div class="event-location">Room 302, Computer Science Building</div>
                        </div>
                    </div>
                    <div class="event-item">
                        <div class="event-time">1:00 PM - 3:00 PM</div>
                        <div class="event-content">
                            <div class="event-title">Database Design Class</div>
                            <div class="event-location">Room 150, Computing Lab</div>
                        </div>
                    </div>
                    <div class="event-item exam">
                        <div class="event-time">3:30 PM - 5:30 PM</div>
                        <div class="event-content">
                            <div class="event-title">JavaScript Midterm Exam</div>
                            <div class="event-location">Room 302, Computer Science Building</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="event-legend">
        <h3>Event Types</h3>
        <div class="legend-container">
            <div class="legend-item">
                <span class="legend-color class-color"></span>
                <span class="legend-label">Classes</span>
            </div>
            <div class="legend-item">
                <span class="legend-color assignment-color"></span>
                <span class="legend-label">Assignments</span>
            </div>
            <div class="legend-item">
                <span class="legend-color exam-color"></span>
                <span class="legend-label">Exams</span>
            </div>
            <div class="legend-item">
                <span class="legend-color campus-color"></span>
                <span class="legend-label">Campus Events</span>
            </div>
        </div>
    </div>
    
    <div class="add-event-container">
        <button id="add-event-btn" class="add-event-btn">
            <span class="add-icon">+</span>
            Add Event
        </button>
    </div>
</section>
    
    <!-- Profile Section with User Data from Database -->
    <section id="profile" class="tab-content">
        <h2>Student Profile</h2>
        <div class="profile-container">
            <div class="profile-header">
                <div class="profile-avatar"><?php echo $userInitial; ?></div>
                <div class="profile-info">
                    <h3><?php echo $fullName; ?></h3>
                    <p>Student ID: ST<?php echo rand(10000, 99999); ?></p>
                    <p><?php echo $email; ?></p>
                </div>
                <button class="edit-profile-btn">Edit Profile</button>
            </div>
            
            <div class="profile-details">
                <h3>Personal Information</h3>
                <div class="detail-row">
                    <div class="detail-label">Full Name</div>
                    <div class="detail-value"><?php echo $fullName; ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Email</div>
                    <div class="detail-value"><?php echo $email; ?></div>
                </div>
                
                <!-- Additional sections remain the same -->
            </div>
        </div>
    </section>
    <!-- Add this button to the nav class="tabs" section -->
<button class="tab" data-tab="store"></button>

<!-- Add this entire section after the other tab-content sections -->
<section id="store" class="tab-content">
    <h2>Course Store</h2>
    <div class="store-filters">
        <div class="search-box">
            <input type="text" placeholder="Search courses...">
            <button type="button">Search</button>
        </div>
        <div class="filter-options">
            <select>
                <option value="">All Categories</option>
                <option value="programming">Programming</option>
                <option value="design">Design</option>
                <option value="business">Business</option>
                <option value="data-science">Data Science</option>
                <option value="mathematics">Mathematics</option>
            </select>
            <select>
                <option value="">Sort By</option>
                <option value="newest">Newest</option>
                <option value="popular">Most Popular</option>
                <option value="rating">Highest Rated</option>
                <option value="price-low">Price: Low to High</option>
                <option value="price-high">Price: High to Low</option>
            </select>
        </div>
    </div>
    
    <div class="course-grid">
        <!-- Course Card 1 -->
        <div class="course-card">
            <div class="course-image">
                <img src="/api/placeholder/300/200" alt="Advanced JavaScript">
                <span class="course-badge bestseller">Bestseller</span>
            </div>
            <div class="course-content">
                <h3>Advanced JavaScript</h3>
                <p class="course-instructor">Md. Arafat Ibna Mizan</p>
                <div class="course-rating">
                    <span class="stars">★★★★★</span>
                    <span class="rating-count">4.9 (128 reviews)</span>
                </div>
                <p class="course-description">Master advanced JavaScript concepts including closures, prototypes, async programming, and more.</p>
                <div class="course-meta">
                    <div class="course-duration">
                        <span>12 weeks</span>
                    </div>
                    <div class="course-level">
                        <span>Advanced</span>
                    </div>
                </div>
                <div class="course-footer">
                    <div class="course-price">BDT 7000</div>
                    <button class="add-to-cart-btn">Add to Cart</button>
                </div>
            </div>
        </div>
        
        <!-- Course Card 2 -->
        <div class="course-card">
            <div class="course-image">
                <img src="/api/placeholder/300/200" alt="React & Redux Masterclass">
                <span class="course-badge new">New</span>
            </div>
            <div class="course-content">
                <h3>React & Redux Masterclass</h3>
                <p class="course-instructor">Aroni Saha Prapty</p>
                <div class="course-rating">
                    <span class="stars">★★★★☆</span>
                    <span class="rating-count">4.7 (95 reviews)</span>
                </div>
                <p class="course-description">Build modern web applications with React, Redux, and the latest frontend technologies.</p>
                <div class="course-meta">
                    <div class="course-duration">
                        <span>10 weeks</span>
                    </div>
                    <div class="course-level">
                        <span>Intermediate</span>
                    </div>
                </div>
                <div class="course-footer">
                    <div class="course-price">BDT 6000</div>
                    <button class="add-to-cart-btn">Add to Cart</button>
                </div>
            </div>
        </div>
        
        <!-- Course Card 3 -->
        <div class="course-card">
            <div class="course-image">
                <img src="/api/placeholder/300/200" alt="Database Architecture">
            </div>
            <div class="course-content">
                <h3>Database Architecture & SQL</h3>
                <p class="course-instructor">Md. Omar Faruq</p>
                <div class="course-rating">
                    <span class="stars">★★★★☆</span>
                    <span class="rating-count">4.5 (112 reviews)</span>
                </div>
                <p class="course-description">Learn database design principles, SQL optimization, and data modeling techniques.</p>
                <div class="course-meta">
                    <div class="course-duration">
                        <span>8 weeks</span>
                    </div>
                    <div class="course-level">
                        <span>All Levels</span>
                    </div>
                </div>
                <div class="course-footer">
                    <div class="course-price">BDT 4500</div>
                    <button class="add-to-cart-btn">Add to Cart</button>
                </div>
            </div>
        </div>
        
        <!-- Course Card 4 -->
        <div class="course-card">
            <div class="course-image">
                <img src="/api/placeholder/300/200" alt="UI/UX Design Fundamentals">
                <span class="course-badge sale">25% Off</span>
            </div>
            <div class="course-content">
                <h3>UI/UX Design Fundamentals</h3>
                <p class="course-instructor">Sohag Sarker Saikat</p>
                <div class="course-rating">
                    <span class="stars">★★★★★</span>
                    <span class="rating-count">4.8 (156 reviews)</span>
                </div>
                <p class="course-description">Learn the principles of user-centered design and create intuitive, engaging user experiences.</p>
                <div class="course-meta">
                    <div class="course-duration">
                        <span>6 weeks</span>
                    </div>
                    <div class="course-level">
                        <span>Beginner</span>
                    </div>
                </div>
                <div class="course-footer">
                    <div class="course-price"><span class="original-price">BDT 5000</span> BDT 4000</div>
                    <button class="add-to-cart-btn">Add to Cart</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="pagination">
        <a href="#" class="pagination-prev disabled">&laquo; Previous</a>
        <a href="#" class="active">1</a>
        <a href="#">2</a>
        <a href="#">3</a>
        <a href="#">4</a>
        <a href="#">5</a>
        <a href="#" class="pagination-next">Next &raquo;</a>
    </div>
    
    <div class="cart-summary">
        <h3>Your Cart</h3>
        <div class="cart-empty">
            <p>Your cart is empty.</p>
            <p>Browse courses and add them to your cart to enroll.</p>
        </div>
        <!-- This div would be shown when items are added to cart
        <div class="cart-items">
            <div class="cart-item">
                <div class="cart-item-info">
                    <h4>Advanced JavaScript</h4>
                    <p>$199.99</p>
                </div>
                <button class="remove-item">Remove</button>
            </div>
            <div class="cart-total">
                <span>Total:</span>
                <span>$199.99</span>
            </div>
            <button class="checkout-btn">Proceed to Checkout</button>
        </div>
        -->
    </div>
</section>

<!-- Add this CSS to your stylesheet (style.css) -->
<style>
/* Store Styles */
.store-filters {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.search-box {
    display: flex;
    width: 100%;
    max-width: 500px;
}

.search-box input {
    flex: 1;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px 0 0 4px;
    outline: none;
}

.search-box button {
    padding: 0.75rem 1rem;
    background: #4169e1;
    color: white;
    border: none;
    border-radius: 0 4px 4px 0;
    cursor: pointer;
}

.filter-options {
    display: flex;
    gap: 0.5rem;
}

.filter-options select {
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    background-color: white;
    min-width: 150px;
}

.course-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    margin: 1.5rem 0;
}

.course-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
    background-color: white;
}

.course-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
}

.course-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.course-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.course-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 0.25rem 0.75rem;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: bold;
    color: white;
}

.bestseller {
    background-color: #ff9800;
}

.new {
    background-color: #4caf50;
}

.sale {
    background-color: #f44336;
}

.course-content {
    padding: 1rem;
}

.course-content h3 {
    margin: 0 0 0.5rem 0;
    font-size: 1.2rem;
}

.course-instructor {
    color: #666;
    margin: 0 0 0.5rem 0;
    font-size: 0.9rem;
}

.course-rating {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
}

.stars {
    color: #ffb400;
    margin-right: 0.5rem;
}

.rating-count {
    font-size: 0.85rem;
    color: #666;
}

.course-description {
    margin: 0.5rem 0;
    font-size: 0.9rem;
    line-height: 1.4;
    color: #444;
    height: 3.8rem;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
}

.course-meta {
    display: flex;
    justify-content: space-between;
    margin: 0.5rem 0;
    font-size: 0.85rem;
    color: #666;
}

.course-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #eee;
}

.course-price {
    font-weight: bold;
    font-size: 1.2rem;
    color: #333;
}

.original-price {
    text-decoration: line-through;
    color: #999;
    font-size: 0.9rem;
    margin-right: 0.5rem;
}

.add-to-cart-btn {
    padding: 0.5rem 1rem;
    background-color: #4169e1;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 500;
    transition: background-color 0.2s;
}

.add-to-cart-btn:hover {
    background-color: #3050b5;
}

.pagination {
    display: flex;
    justify-content: center;
    margin: 2rem 0;
}

.pagination a {
    padding: 0.5rem 0.75rem;
    margin: 0 0.25rem;
    border: 1px solid #ddd;
    color: #333;
    text-decoration: none;
    border-radius: 4px;
}

.pagination a.active {
    background-color: #4169e1;
    color: white;
    border-color: #4169e1;
}

.pagination a.disabled {
    color: #ccc;
    pointer-events: none;
}

.cart-summary {
    margin-top: 2rem;
    padding: 1.5rem;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #f9f9f9;
}

.cart-empty {
    text-align: center;
    padding: 2rem 0;
    color: #666;
}

.cart-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid #eee;
}

.cart-total {
    display: flex;
    justify-content: space-between;
    font-weight: bold;
    margin: 1rem 0;
    padding-top: 1rem;
}

.checkout-btn {
    width: 100%;
    padding: 0.75rem;
    background-color: #4caf50;
    color: white;
    border: none;
    border-radius: 4px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.2s;
}

.checkout-btn:hover {
    background-color: #3d8b40;
}

.remove-item {
    background-color: transparent;
    color: #f44336;
    border: none;
    cursor: pointer;
    font-size: 0.9rem;
}

/* Dark mode adjustments */
.dark-mode .course-card {
    background-color: #2a2a2a;
    border-color: #444;
}

.dark-mode .course-content h3 {
    color: #fff;
}

.dark-mode .course-description,
.dark-mode .course-price {
    color: #eee;
}

.dark-mode .course-instructor,
.dark-mode .course-meta,
.dark-mode .rating-count {
    color: #bbb;
}

.dark-mode .search-box input,
.dark-mode .filter-options select {
    background-color: #333;
    border-color: #555;
    color: #fff;
}

.dark-mode .cart-summary {
    background-color: #2a2a2a;
    border-color: #444;
}

.dark-mode .cart-empty {
    color: #bbb;
}

.dark-mode .pagination a {
    background-color: #333;
    border-color: #555;
    color: #eee;
}

.dark-mode .pagination a.active {
    background-color: #4169e1;
    border-color: #4169e1;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .store-filters {
        flex-direction: column;
    }
    
    .search-box {
        max-width: 100%;
    }
    
    .filter-options {
        width: 100%;
    }
    
    .filter-options select {
        flex: 1;
    }
    
    .course-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    }
}

@media (max-width: 480px) {
    .course-grid {
        grid-template-columns: 1fr;
    }
    
    .course-footer {
        flex-direction: column;
        gap: 1rem;
    }
    
    .add-to-cart-btn {
        width: 100%;
    }
}
</style>
</script>
    
    <!-- Other sections removed for brevity -->
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab switching functionality
            const tabs = document.querySelectorAll('.tab');
            const tabContents = document.querySelectorAll('.tab-content');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all tabs
                    tabs.forEach(t => t.classList.remove('active'));
                    
                    // Hide all tab contents
                    tabContents.forEach(content => {
                        content.style.display = 'none';
                    });
                    
                    // Add active class to clicked tab
                    this.classList.add('active');
                    
                    // Show corresponding tab content
                    const tabId = this.getAttribute('data-tab');
                    document.getElementById(tabId).style.display = 'block';
                });
            });
            
            // Show default tab (dashboard)
            document.getElementById('dashboard').style.display = 'block';
            
            // Theme toggle functionality
            const themeToggle = document.querySelector('.theme-toggle');
            if(themeToggle) {
                themeToggle.addEventListener('click', function() {
                    document.body.classList.toggle('dark-mode');
                    localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
                });
                
                // Check for saved theme preference
                if(localStorage.getItem('darkMode') === 'true') {
                    document.body.classList.add('dark-mode');
                }
            }
        });
        // Store functionality
document.addEventListener('DOMContentLoaded', function() {
    // Add to cart functionality
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Get course info
            const courseCard = this.closest('.course-card');
            const courseTitle = courseCard.querySelector('h3').textContent;
            const coursePrice = courseCard.querySelector('.course-price').textContent;
            
            // Show a notification
            alert(`Added to cart: ${courseTitle} - ${coursePrice}`);
            
            // Here you would update the cart in a real application
            // For now, just show a simple alert
        });
    });
});

        
    </script>
    
    <footer>
        <p>&copy; 2025 StudyCraft Learning Management System</p>
        <p>Version 2.5.0</p>
    </footer>
</body>
</html>
<?php
// Flush the output buffer and send content to browser
ob_end_flush();
?>