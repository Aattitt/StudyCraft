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
include 'connect.php'; // Ensure this file exists and $conn is established
$email = $_SESSION['email'];
$sql = "SELECT * FROM users WHERE email='$email' AND userRole='teacher'";
$result = $conn->query($sql);

// If user is not a teacher, redirect
if($result->num_rows == 0){
    header("Location: index.php");
    exit();
}

$userData = $result->fetch_assoc();
$firstName = $userData['firstName'];
$lastName = $userData['lastName'];
$fullName = $firstName . ' ' . $lastName;
$userInitial = substr($firstName, 0, 1);
// Assuming other profile data might come from $userData or be placeholders
$userRoleDescription = "Instructor, ". ($userData['department'] ?? 'Education Department'); // Example, adjust as needed
$userPhone = $userData['phone'] ?? '(123) 456-7890'; // Example
$userOffice = $userData['office'] ?? 'Room TBA'; // Example

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudyCraft - Teacher Dashboard</title>
    <link rel="stylesheet" href="style.css"> <link rel="stylesheet" href="courses_assignments.css"> <link rel="stylesheet" href="profile.css"> </head>
<body>
    <header class="header">
        <span class="teacher-badge">Teacher</span>
        <h1>StudyCraft</h1>
        <p>Faculty Portal</p>
        <div class="user-info">
            <div class="user-avatar"><?php echo htmlspecialchars($userInitial); ?></div>
            <span>Welcome, Prof. <?php echo htmlspecialchars($lastName); ?></span>
        </div>
        <button class="theme-toggle" aria-label="Toggle dark mode"></button>
        <a href="logout.php" class="logout-btn">Logout</a>
    </header>
    
    <nav class="tabs">
        <button class="tab active" data-tab="dashboard">Dashboard</button>
        <button class="tab" data-tab="courses">My Courses</button>
        <button class="tab" data-tab="students">Students</button>
        <button class="tab" data-tab="assignments">Assignments</button>
        <button class="tab" data-tab="grades">Grades</button>
        <button class="tab" data-tab="calendar">Calendar</button>
        <button class="tab" data-tab="profile">Profile</button>
        <button class="tab" data-tab="analytics">Analytics</button>
    </nav>

<div id="dashboard" class="tab-content active">
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h2>Dashboard Overview</h2>
            <p class="dashboard-subtitle">Welcome back, Prof. <?php echo htmlspecialchars($lastName); ?>! Here's what's happening in your classes today.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-content">
                    <h3>5</h3>
                    <p>Active Courses</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-content">
                    <h3>124</h3>
                    <p>Total Students</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-content">
                    <h3>8</h3>
                    <p>Pending Assignments</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-content">
                    <h3>3</h3>
                    <p>Due This Week</p>
                </div>
            </div>
        </div>

        <div class="dashboard-main">
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Recent Activity</h3>
                    <a href="#" class="view-all">View All</a>
                </div>
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon submitted"></div>
                        <div class="activity-content">
                            <p><strong>15 students</strong> submitted "Database Design Project"</p>
                            <div class="activity-time">2 hours ago</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon new"></div>
                        <div class="activity-content">
                            <p><strong>Sarah Chen</strong> posted a question in CS101 forum</p>
                            <div class="activity-time">4 hours ago</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon graded"></div>
                        <div class="activity-content">
                            <p>Graded <strong>22 assignments</strong> for "Web Development Basics"</p>
                            <div class="activity-time">1 day ago</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon announcement"></div>
                        <div class="activity-content">
                            <p>Posted announcement about midterm schedule</p>
                            <div class="activity-time">2 days ago</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Today's Schedule</h3>
                    <span class="schedule-date"><?php echo date('M d, Y'); ?></span>
                </div>
                <div class="schedule-list">
                    <div class="schedule-item">
                        <div class="schedule-time">9:00 AM</div>
                        <div class="schedule-content">
                            <h4>CS101 - Introduction to Programming</h4>
                            <p>Room: Tech Lab 201 • 45 students</p>
                        </div>
                    </div>
                    <div class="schedule-item">
                        <div class="schedule-time">11:30 AM</div>
                        <div class="schedule-content">
                            <h4>CS201 - Data Structures</h4>
                            <p>Room: Tech Lab 305 • 32 students</p>
                        </div>
                    </div>
                    <div class="schedule-item">
                        <div class="schedule-time">2:00 PM</div>
                        <div class="schedule-content">
                            <h4>Office Hours</h4>
                            <p>Room: Faculty Office 412</p>
                        </div>
                    </div>
                    <div class="schedule-item upcoming">
                        <div class="schedule-time">4:00 PM</div>
                        <div class="schedule-content">
                            <h4>CS301 - Database Systems</h4>
                            <p>Room: Tech Lab 201 • 28 students</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Quick Actions</h3>
                </div>
                <div class="quick-actions">
                    <button class="action-btn primary">
                        <span class="action-icon">+</span>
                        Create Assignment
                    </button>
                    <button class="action-btn secondary">
                        <span class="action-icon">📊</span>
                        Grade Submissions
                    </button>
                    <button class="action-btn secondary">
                        <span class="action-icon">📢</span>
                        Post Announcement
                    </button>
                    <button class="action-btn secondary">
                        <span class="action-icon">📅</span>
                        Schedule Meeting
                    </button>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Need Your Attention</h3>
                    <span class="attention-count">6</span>
                </div>
                <div class="attention-list">
                    <div class="attention-item urgent">
                        <div class="attention-content">
                            <h4>Database Project - CS301</h4>
                            <p>12 submissions pending review</p>
                            <div class="due-date">Due: 2 days ago</div>
                        </div>
                        <button class="btn-small">Review</button>
                    </div>
                    <div class="attention-item warning">
                        <div class="attention-content">
                            <h4>Midterm Exam - CS201</h4>
                            <p>Exam scheduled for tomorrow</p>
                            <div class="due-date">Tomorrow 10:00 AM</div>
                        </div>
                        <button class="btn-small">Prepare</button>
                    </div>
                    <div class="attention-item info">
                        <div class="attention-content">
                            <h4>Weekly Quiz - CS101</h4>
                            <p>8 submissions to grade</p>
                            <div class="due-date">Due: Today</div>
                        </div>
                        <button class="btn-small">Grade</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="announcements-banner">
            <div class="announcement-item">
                <span class="announcement-icon">📢</span>
                <div class="announcement-content">
                    <strong>Reminder:</strong> Faculty meeting scheduled for Friday 3:00 PM in Conference Room A
                </div>
                <button class="close-announcement">×</button>
            </div>
        </div>
    </div>
</div>

<div id="courses" class="tab-content">
  <div class="tab-content-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 class="section-title" style="margin-bottom: 0;">My Courses</h2>
        <button class="action-btn primary"><span class="action-icon">+</span> Add New Course</button>
    </div>
    <div class="card-grid">
  <div class="card">
    <h3 class="card-title">Operating System</h3>
    <p class="card-desc">Covers core concepts of operating systems including processes, memory management, file systems, and scheduling algorithms.</p>
    <p class="card-meta" style="font-size: 0.8rem; color: #6b7280; margin-top: 1rem;"><strong>Students:</strong> 45 | <strong>Assignments:</strong> 12 | <strong>Next Deadline:</strong> June 3, 2025</p>
    <a href="#" class="btn-small" style="margin-top: 1rem; background-color: #4f46e5; color: white;">Go to Course</a>
  </div>
  <div class="card">
    <h3 class="card-title">Numerical Analysis</h3>
    <p class="card-desc">Introduction to numerical methods for solving mathematical problems, including interpolation, integration, and differential equations.</p>
    <p class="card-meta" style="font-size: 0.8rem; color: #6b7280; margin-top: 1rem;"><strong>Students:</strong> 32 | <strong>Assignments:</strong> 8 | <strong>Next Deadline:</strong> June 8, 2025</p>
    <a href="#" class="btn-small" style="margin-top: 1rem; background-color: #4f46e5; color: white;">Go to Course</a>
  </div>
  <div class="card">
    <h3 class="card-title">Software Desigining</h3>
    <p class="card-desc">Focuses on principles of software architecture, design patterns, and methodologies for building scalable software systems.</p>
    <p class="card-meta" style="font-size: 0.8rem; color: #6b7280; margin-top: 1rem;"><strong>Students:</strong> 28 | <strong>Assignments:</strong> 10 | <strong>Next Deadline:</strong> June 12, 2025</p>
    <a href="#" class="btn-small" style="margin-top: 1rem; background-color: #4f46e5; color: white;">Go to Course</a>
  </div>
  <div class="card">
    <h3 class="card-title">Digital Image Processing</h3>
    <p class="card-desc">Covers techniques for processing and analyzing digital images including filtering, segmentation, and image transformation.</p>
    <p class="card-meta" style="font-size: 0.8rem; color: #6b7280; margin-top: 1rem;"><strong>Students:</strong> 19 | <strong>Assignments:</strong> 7 | <strong>Next Deadline:</strong> June 15, 2025</p>
    <a href="#" class="btn-small" style="margin-top: 1rem; background-color: #4f46e5; color: white;">Go to Course</a>
  </div>
  <div class="card">
    <h3 class="card-title">Web Programming</h3>
    <p class="card-desc">Covers front-end and back-end web development, including HTML, CSS, JavaScript, server-side scripting, and databases.</p>
    <p class="card-meta" style="font-size: 0.8rem; color: #6b7280; margin-top: 1rem;"><strong>Students:</strong> 25 | <strong>Assignments:</strong> 6 | <strong>Next Deadline:</strong> June 10, 2025</p>
    <a href="#" class="btn-small" style="margin-top: 1rem; background-color: #4f46e5; color: white;">Go to Course</a>
  </div>
</div>

    </div>
  </div>
</div>

<div id="students" class="tab-content">
  <div class="tab-content-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 class="section-title" style="margin-bottom: 0;">Student Roster</h2>
        <div>
            <input type="text" placeholder="Search students..." style="padding: 0.5rem; border-radius: 6px; border: 1px solid #d1d5db; margin-right: 0.5rem;">
        </div>
    </div>
    <div class="table-container">
      <table class="dashboard-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Enrolled Courses</th>
            <th>Overall Grade</th>
            <th>Last Login</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
  <tr>
    <td>S001</td>
    <td>Akib Al Imran</td>
    <td>akib1998@gmail.com</td>
    <td>Operating System, Numerical Analysis</td>
    <td>89% (B+)</td>
    <td>May 25, 2025</td>
    <td><button class="btn-small view-profile">View Details</button></td>
  </tr>
  <tr>
    <td>S002</td>
    <td>Shahria Siddiq Durlov</td>
    <td>durlov01@gmail.com</td>
    <td>Web Programming, Software Desigining</td>
    <td>92% (A-)</td>
    <td>May 24, 2025</td>
    <td><button class="btn-small view-profile">View Details</button></td>
  </tr>
  <tr>
    <td>S003</td>
    <td>Shadman Shad Uttsaw</td>
    <td>uttsaw11@gmail.com</td>
    <td>Numerical Analysis</td>
    <td>85% (B)</td>
    <td>May 25, 2025</td>
    <td><button class="btn-small view-profile">View Details</button></td>
  </tr>
  <tr>
    <td>S004</td>
    <td>Atit Imtiaz</td>
    <td>atit15@gmail.com</td>
    <td>Digital Image Processing, Operating System</td>
    <td>91% (A-)</td>
    <td>May 23, 2025</td>
    <td><button class="btn-small view-profile">View Details</button></td>
  </tr>
  <tr>
    <td>S005</td>
    <td>Mehadi Hasan</td>
    <td>mehadi11@gmail.com</td>
    <td>Web Programming</td>
    <td>82% (B-)</td>
    <td>May 25, 2025</td>
    <td><button class="btn-small view-profile">View Details</button></td>
  </tr>
</tbody>

      </table>
    </div>
    <div style="margin-top: 1.5rem; text-align: right;">
        <button class="btn-small">Previous</button>
        <span style="padding: 0 1rem;">Page 1 of 5</span>
        <button class="btn-small">Next</button>
    </div>
  </div>
</div>

<div id="assignments" class="tab-content">
  <div class="tab-content-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 class="section-title" style="margin-bottom: 0;">Assignments Overview</h2>
        <button class="action-btn primary btn-create-assignment"><span class="action-icon">+</span> Create New Assignment</button>
    </div>
    <div style="margin-bottom: 1.5rem;">
        <select style="padding: 0.5rem; border-radius: 6px; border: 1px solid #d1d5db; margin-right: 0.5rem;">
            <option value="">Filter by Course</option>
            <option value="math101">MATH101</option>
            <option value="hist202">HIST202</option>
            <option value="phys301">PHYS301</option>
            <option value="cs405">CS405</option>
        </select>
        <select style="padding: 0.5rem; border-radius: 6px; border: 1px solid #d1d5db;">
            <option value="">Filter by Status</option>
            <option value="upcoming">Upcoming</option>
            <option value="pastdue">Past Due</option>
            <option value="graded">Graded</option>
        </select>
    </div>
    <div class="card-grid">
      <div class="card">
        <h3 class="card-title">Operating System</h3>
        <p class="card-desc"><strong></strong><br><strong>Type:</strong> Homework<br><strong>Due:</strong> June 5, 2025</p>
        <p class="card-meta" style="font-size: 0.8rem; color: #6b7280; margin-top: 1rem;"><strong>Submissions:</strong> 30/45 | <strong>Graded:</strong> 10/30</p>
        <div style="margin-top: 1rem;">
            <a href="#" class="btn-small" style="margin-right:0.5rem;">View Submissions</a>
            <a href="#" class="btn-small" style="background-color:#ef4444; color:white;">Edit</a>
        </div>
      </div>
      <div class="card">
        <h3 class="card-title">Numerical Analysis</h3>
        <p class="card-desc"><strong></strong><br><strong>Type:</strong> Essay<br><strong>Due:</strong> June 8, 2025</p>
        <p class="card-meta" style="font-size: 0.8rem; color: #6b7280; margin-top: 1rem;"><strong>Submissions:</strong> 22/32 | <strong>Graded:</strong> 5/22</p>
        <div style="margin-top: 1rem;">
            <a href="#" class="btn-small" style="margin-right:0.5rem;">View Submissions</a>
            <a href="#" class="btn-small" style="background-color:#ef4444; color:white;">Edit</a>
        </div>
      </div>
      <div class="card">
        <h3 class="card-title">Software Desigining</h3>
        <p class="card-desc"><strong>:</strong><br><strong>Type:</strong> Lab Report<br><strong>Due:</strong> June 12, 2025</p>
        <p class="card-meta" style="font-size: 0.8rem; color: #6b7280; margin-top: 1rem;"><strong>Submissions:</strong> 15/28 | <strong>Graded:</strong> 0/15</p>
        <div style="margin-top: 1rem;">
            <a href="#" class="btn-small" style="margin-right:0.5rem;">View Submissions</a>
            <a href="#" class="btn-small" style="background-color:#ef4444; color:white;">Edit</a>
        </div>
      </div>
      <div class="card">
        <h3 class="card-title">Digital Image Processing</h3>
        <p class="card-desc"><strong></strong><br><strong>Type:</strong> Project<br><strong>Due:</strong> June 15, 2025</p>
        <p class="card-meta" style="font-size: 0.8rem; color: #6b7280; margin-top: 1rem;"><strong>Submissions:</strong> 10/19 | <strong>Graded:</strong> 0/10</p>
        <div style="margin-top: 1rem;">
            <a href="#" class="btn-small" style="margin-right:0.5rem;">View Submissions</a>
            <a href="#" class="btn-small" style="background-color:#ef4444; color:white;">Edit</a>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="grades" class="tab-content">
  <div class="tab-content-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 class="section-title" style="margin-bottom: 0;">Gradebook</h2>
        <div>
            <input type="text" placeholder="Search by student or assignment..." style="padding: 0.5rem; border-radius: 6px; border: 1px solid #d1d5db;">
        </div>
    </div>
    <div class="table-container">
      <table class="dashboard-table">
        <thead>
          <tr>
            <th>Student</th>
            <th>Assignment</th>
            <th>Submission Date</th>
            <th>Status</th>
            <th>Grade</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Akib Al Imran</td>
            <td>Web Programming</td>
            <td>June 4, 2025</td>
            <td><span style="color: green;">Graded</span></td>
            <td>88%</td>
            <td><button class="btn-small">Edit Grade</button></td>
          </tr>
          <tr>
            <td>Shahria Siddiq Durlov</td>
            <td>Digital Image Processing</td>
            <td>June 7, 2025</td>
            <td><span style="color: orange;">Pending</span></td>
            <td>--</td>
            <td><button class="btn-small">Grade Now</button></td>
          </tr>
          <tr>
            <td>Shadman Shad Uttsaw</td>
            <td>Software Desigining</td>
            <td>June 11, 2025</td>
            <td><span style="color: green;">Graded</span></td>
            <td>85%</td>
            <td><button class="btn-small">Edit Grade</button></td>
          </tr>
          <tr>
            <td>Atit Imtiaz</td>
            <td>Web Project Book</td>
            <td>June 14, 2025</td>
            <td><span style="color: orange;">Pending</span></td>
            <td>--</td>
            <td><button class="btn-small">Grade Now</button></td>
          </tr>
           <tr>
            <td>AMehadi Hasan</td>
            <td>Numerical Analysis</td>
            <td>May 28, 2025</td>
            <td><span style="color: green;">Graded</span></td>
            <td>95%</td>
            <td><button class="btn-small">Edit Grade</button></td>
          </tr>
           <tr>
            <td>Mirajul Nahim</td>
            <td>Operating System</td>
            <td>June 2, 2025</td>
            <td><span style="color: red;">Late</span> / <span style="color: green;">Graded</span></td>
            <td>70%</td>
            <td><button class="btn-small">Edit Grade</button></td>
          </tr>
        </tbody>
      </table>
       <div style="margin-top: 1.5rem; text-align: right;">
            <button class="btn-small">Export Grades (CSV)</button>
       </div>
    </div>
  </div>
</div>

<div id="calendar" class="tab-content">
  <div class="tab-content-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 class="section-title" style="margin-bottom: 0;">Academic Calendar</h2>
        <div>
            <button class="btn-small">Month</button>
            <button class="btn-small" style="background-color: #e5e7eb;">Week</button>
            <button class="btn-small" style="background-color: #e5e7eb;">Day</button>
        </div>
    </div>
    
    <div class="dashboard-card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
            <h3>June 2025</h3>
            <div class="calendar-nav">
                <button class="btn-small">&lt; Prev</button>
                <button class="btn-small">Next &gt;</button>
            </div>
        </div>
        <div style="padding:1rem; display:grid; grid-template-columns: repeat(7, 1fr); gap: 5px; text-align: center;">
            <div style="font-weight:bold; padding: 0.5rem;">Sun</div>
            <div style="font-weight:bold; padding: 0.5rem;">Mon</div>
            <div style="font-weight:bold; padding: 0.5rem;">Tue</div>
            <div style="font-weight:bold; padding: 0.5rem;">Wed</div>
            <div style="font-weight:bold; padding: 0.5rem;">Thu</div>
            <div style="font-weight:bold; padding: 0.5rem;">Fri</div>
            <div style="font-weight:bold; padding: 0.5rem;">Sat</div>
            
            <div style="border:1px solid #eee; padding: 0.5rem; min-height: 60px;">1 <br><small style="color:#3b82f6;">Staff Meeting</small></div>
            <div style="border:1px solid #eee; padding: 0.5rem; min-height: 60px;">2</div>
            <div style="border:1px solid #eee; padding: 0.5rem; min-height: 60px; background-color: #eff6ff;">3 <br><small style="color:#ef4444;">MATH101 Quiz</small></div>
            <div style="border:1px solid #eee; padding: 0.5rem; min-height: 60px;">4</div>
            <div style="border:1px solid #eee; padding: 0.5rem; min-height: 60px; background-color: #eff6ff;">5 <br><small style="color:#ef4444;">Alg. Hw Due</small></div>
            <div style="border:1px solid #eee; padding: 0.5rem; min-height: 60px;">6</div>
            <div style="border:1px solid #eee; padding: 0.5rem; min-height: 60px;">7 <br><small style="color:#3b82f6;">P-T Conf.</small></div>
            <div style="border:1px solid #eee; padding: 0.5rem; min-height: 60px; background-color: #eff6ff;">8 <br><small style="color:#ef4444;">Hist. Essay Due</small></div>
            <div style="border:1px solid #eee; padding: 0.5rem; min-height: 60px;">9</div>
            <div style="border:1px solid #eee; padding: 0.5rem; min-height: 60px;">10</div>
            <div style="border:1px solid #eee; padding: 0.5rem; min-height: 60px;">11</div>
            <div style="border:1px solid #eee; padding: 0.5rem; min-height: 60px; background-color: #eff6ff;">12 <br><small style="color:#ef4444;">Phys. Lab Due</small></div>
            <div style="border:1px solid #eee; padding: 0.5rem; min-height: 60px;">13 <br><small style="color:#3b82f6;">Dept. Meeting</small></div>
            <div style="border:1px solid #eee; padding: 0.5rem; min-height: 60px;">14</div>
            <div style="border:1px solid #eee; padding: 0.5rem; min-height: 60px;">...</div>
        </div>
    </div>

    <div class="calendar-card">
      <div class="card-header"><h3>Upcoming Events & Deadlines</h3> <button class="btn-small primary">Add Event</button></div>
      <ul class="calendar-events">
        <li><strong>June 1, 2025 (10:00 AM):</strong> Staff Meeting - Conference Room A</li>
        <li><strong>June 3, 2025:</strong> MATH101 Quiz 2 Due</li>
        <li><strong>June 5, 2025:</strong> MATH101 - Algebra Homework 3 Due</li>
        <li><strong>June 7, 2025 (All Day):</strong> Parent-Teacher Conference Day</li>
        <li><strong>June 8, 2025:</strong> HIST202 - Essay: The Silk Road Due</li>
        <li><strong>June 12, 2025:</strong> PHYS301 - Lab Report: Projectile Motion Due</li>
        <li><strong>June 13, 2025 (2:00 PM):</strong> Departmental Meeting - Faculty Lounge</li>
        <li><strong>June 15, 2025:</strong> CS405 - AI Project Proposal Due</li>
        <li><strong>June 20-24, 2025:</strong> Midterm Exam Week</li>
      </ul>
    </div>
  </div>
</div>

<div id="profile" class="tab-content">
  <div class="tab-content-container">
     <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 class="section-title" style="margin-bottom: 0;">Your Profile</h2>
        <button class="action-btn secondary"><span class="action-icon" style="font-size:0.8em">✏️</span> Edit Profile</button>
    </div>
    <div class="profile-card">
      <div class="profile-header">
        <img src="https://via.placeholder.com/100" alt="Profile Photo" class="profile-avatar"> <div>
          <h3 class="profile-name"><?php echo htmlspecialchars($fullName); ?></h3>
          <p class="profile-role"><?php echo htmlspecialchars($userRoleDescription); ?></p>
        </div>
      </div>
      <div class="profile-details">
        <p><strong>Full Name:</strong> <?php echo htmlspecialchars($fullName); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($userPhone); ?></p>
        <p><strong>Office:</strong> <?php echo htmlspecialchars($userOffice); ?></p>
        <p><strong>Department:</strong> <?php echo htmlspecialchars($userData['department'] ?? 'Education Department'); ?></p>
        <p><strong>Joined:</strong> <?php echo htmlspecialchars($userData['joinDate'] ?? 'August 15, 2018'); ?></p>
        <p><strong>Specialization:</strong> <?php echo htmlspecialchars($userData['specialization'] ?? 'Curriculum Development, Educational Technology'); ?></p>
        <div>
            <p><strong>Bio:</strong></p>
            <p style="margin-left: 80px; margin-top: -1.4rem;"><?php echo htmlspecialchars($userData['bio'] ?? 'Dedicated and passionate educator with over 10 years of experience in fostering student growth and academic excellence. Committed to creating an inclusive and stimulating learning environment.'); ?></p>
        </div>
         <p><strong>Office Hours:</strong> <?php echo htmlspecialchars($userData['officeHours'] ?? 'Mon, Wed 2:00 PM - 4:00 PM'); ?></p>
      </div>
      <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #f3f4f6;">
          <h4 style="font-size: 1.1rem; color: #1f2937; margin-bottom: 1rem;">Settings</h4>
          <p><strong>Change Password:</strong> <a href="#" class="view-all">Update Password</a></p>
          <p><strong>Notification Preferences:</strong> <a href="#" class="view-all">Manage Notifications</a></p>
      </div>
    </div>
  </div>
</div>

<div id="analytics" class="tab-content">
  <div class="tab-content-container">
    <h2 class="section-title">Analytics Overview</h2>
    <div style="margin-bottom: 1.5rem;">
        <select style="padding: 0.5rem; border-radius: 6px; border: 1px solid #d1d5db; margin-right: 0.5rem;">
            <option value="all">All Courses</option>
            <option value="math101">MATH101</option>
            <option value="hist202">HIST202</option>
            <option value="phys301">PHYS301</option>
             <option value="cs405">CS405</option>
        </select>
        <select style="padding: 0.5rem; border-radius: 6px; border: 1px solid #d1d5db;">
            <option value="current_semester">Current Semester</option>
            <option value="last_semester">Last Semester</option>
            <option value="academic_year">Full Academic Year</option>
        </select>
    </div>
    <div class="analytics-grid">
      <div class="analytics-card">
        <h3 class="analytics-metric">124</h3>
        <p class="analytics-label">Total Students (Selected)</p>
      </div>
      <div class="analytics-card">
        <h3 class="analytics-metric">87%</h3>
        <p class="analytics-label">Avg. Assignment Completion</p>
      </div>
      <div class="analytics-card">
        <h3 class="analytics-metric">4.6 <span style="font-size: 0.6em; color: #6b7280;">/ 5.0</span></h3>
        <p class="analytics-label">Average Course Rating</p>
      </div>
       <div class="analytics-card">
        <h3 class="analytics-metric">78%</h3>
        <p class="analytics-label">Overall Student Pass Rate</p>
      </div>
      <div class="analytics-card">
        <h3 class="analytics-metric">B+ (88%)</h3>
        <p class="analytics-label">Average Student Grade</p>
      </div>
      <div class="analytics-card">
        <h3 class="analytics-metric">92%</h3>
        <p class="analytics-label">Student Engagement (Online)</p>
      </div>
      <div class="analytics-card">
        <h3 class="analytics-metric">3.2 days</h3>
        <p class="analytics-label">Avg. Grading Turnaround</p>
      </div>
      <div class="analytics-card">
        <h3 class="analytics-metric">5</h3>
        <p class="analytics-label">Forum Posts This Week</p>
      </div>
    </div>
    <div class="dashboard-card" style="margin-top: 2rem;">
        <div class="card-header"><h3>Grade Distribution (All Courses)</h3></div>
        <div style="padding: 1.5rem; text-align: center; color: #6b7280;">
            <img src="https://via.placeholder.com/600x300.png?text=Chart:+Grade+Distribution" alt="Grade Distribution Chart Placeholder" style="max-width:100%; height:auto; border: 1px solid #e5e7eb; border-radius: 8px;">
            <p style="margin-top:1rem;">A visual representation of grade distribution will appear here.</p>
        </div>
    </div>
    <div class="dashboard-card" style="margin-top: 1.5rem;">
        <div class="card-header"><h3>Assignment Completion Rate Over Time</h3></div>
        <div style="padding: 1.5rem; text-align: center; color: #6b7280;">
            <img src="https://via.placeholder.com/600x300.png?text=Chart:+Assignment+Completion+Rate" alt="Assignment Completion Rate Chart Placeholder" style="max-width:100%; height:auto; border: 1px solid #e5e7eb; border-radius: 8px;">
            <p style="margin-top:1rem;">A trend graph of assignment completion rates will appear here.</p>
        </div>
    </div>
  </div>
</div>


<style>
/* Dashboard Styles - Clean Modern Design */
body {
    font-family: 'Inter', sans-serif; /* Recommended to add a font-family */
    background-color: #f9fafb; /* Light gray background for the page */
    color: #374151;
    margin: 0;
}

.header {
    background-color: #ffffff;
    padding: 1rem 2rem;
    display: flex;
    align-items: center;
    border-bottom: 1px solid #e5e7eb;
    gap: 1rem; /* Spacing between header items */
}
.header h1 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}
.header p { /* Faculty Portal */
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0 0 0 0.5rem; /* Space after h1 */
}
.teacher-badge {
    background-color: #d1fae5;
    color: #065f46;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px; /* pill shape */
    font-size: 0.75rem;
    font-weight: 500;
    margin-right: 1rem;
}
.user-info {
    margin-left: auto; /* Pushes user info and logout to the right */
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background-color: #4f46e5; /* Indigo background */
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 500;
    font-size: 0.875rem;
}
.user-info span {
    font-size: 0.875rem;
    color: #374151;
}
.logout-btn {
    background-color: #ef4444; /* Red */
    color: white;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    transition: background-color 0.2s;
}
.logout-btn:hover {
    background-color: #dc2626; /* Darker red */
}
.theme-toggle { /* Basic styling for theme toggle button */
    background: none;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    padding: 0.5rem;
    cursor: pointer;
    /* Add icon via CSS content or SVG later */
}


.tabs {
    background-color: #ffffff;
    border-bottom: 1px solid #e5e7eb;
    padding: 0 2rem;
    display: flex;
    gap: 0.5rem; /* Small gap between tab buttons */
}
.tab {
    padding: 1rem 1.25rem;
    border: none;
    background: none;
    cursor: pointer;
    font-size: 0.875rem;
    font-weight: 500;
    color: #6b7280; /* Default tab color */
    border-bottom: 2px solid transparent; /* For active state indicator */
    transition: color 0.2s, border-color 0.2s;
}
.tab:hover {
    color: #1f2937;
}
.tab.active {
    color: #4f46e5; /* Indigo for active tab */
    border-bottom-color: #4f46e5;
}


.dashboard-container, .tab-content-container { /* Unified container for padding */
    padding: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.dashboard-header {
    margin-bottom: 2rem;
}

.dashboard-header h2 {
    font-size: 1.75rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 0.5rem 0;
}

.dashboard-subtitle {
    color: #6b7280;
    font-size: 1rem;
    margin: 0;
    font-weight: 400;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); /* Adjusted minmax */
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    padding: 1.5rem; /* Slightly reduced padding */
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    text-align: left;
}

.stat-content h3 {
    font-size: 2.5rem; /* Slightly reduced size */
    font-weight: 600; /* Bolder for numbers */
    color: #4f46e5;
    margin: 0 0 0.25rem 0; /* Reduced bottom margin */
    line-height: 1;
}

.stat-content p {
    color: #6b7280;
    margin: 0;
    font-size: 0.875rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.025em; /* Subtle letter spacing */
}

.dashboard-main {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 1.5rem;
}

.dashboard-card {
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    overflow: hidden; /* Important if child elements have margins */
}

.card-header {
    padding: 1.25rem 1.5rem; /* Consistent padding */
    border-bottom: 1px solid #f3f4f6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h3 {
    margin: 0;
    color: #1f2937;
    font-size: 1.125rem;
    font-weight: 600;
}

.view-all {
    color: #4f46e5;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
}

.view-all:hover {
    color: #3730a3;
}

.activity-list, .schedule-list, .attention-list, .calendar-events {
    padding: 0;
    margin:0; /* Remove default ul margin */
    list-style-type: none; /* Remove default list bullets */
}

.activity-item, .schedule-item, .attention-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #f3f4f6;
}
.calendar-events li {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #f3f4f6;
    font-size: 0.875rem;
    color: #374151;
}
.calendar-events li strong {
    color: #1f2937;
    font-weight: 500;
}


.activity-item:last-child, .schedule-item:last-child, .attention-item:last-child, .calendar-events li:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-top: 0.5rem; /* Adjusted for visual alignment */
    flex-shrink: 0;
}

.activity-icon.submitted { background: #10b981; }
.activity-icon.new { background: #3b82f6; }
.activity-icon.graded { background: #8b5cf6; }
.activity-icon.announcement { background: #f59e0b; }

.activity-content {
    flex: 1;
    min-width: 0;
}

.activity-content p {
    margin: 0 0 0.25rem 0;
    color: #1f2937;
    font-size: 0.875rem;
    line-height: 1.5;
}

.activity-time {
    color: #6b7280;
    font-size: 0.75rem;
    font-weight: 500;
}

.schedule-time {
    font-weight: 600;
    color: #1f2937;
    min-width: 70px; /* Adjusted */
    font-size: 0.875rem;
    flex-shrink: 0;
}

.schedule-content {
    flex: 1;
    min-width: 0;
}

.schedule-content h4 {
    margin: 0 0 0.25rem 0;
    color: #1f2937;
    font-size: 0.875rem;
    font-weight: 600;
}

.schedule-content p {
    margin: 0;
    color: #6b7280;
    font-size: 0.75rem;
}

.schedule-item.upcoming {
    background: #f8fafc; /* Very light blue/gray for upcoming */
}

.quick-actions {
    padding: 1.5rem;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); /* Responsive buttons */
    gap: 1rem;
}

.action-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem; /* Slightly smaller buttons */
    border: 1px solid #d1d5db;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    background: white;
    color: #374151;
}

.action-btn.primary {
    background: #4f46e5;
    color: white;
    border-color: #4f46e5;
}

.action-btn:hover {
    border-color: #4f46e5;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); /* Softer shadow */
}

.action-btn.primary:hover {
    background: #4338ca;
}

.action-icon {
    font-size: 1rem;
}

.attention-count {
    background: #ef4444;
    color: white;
    padding: 0.2rem 0.4rem; /* Smaller padding */
    border-radius: 12px;
    font-size: 0.7rem; /* Smaller font */
    font-weight: 600;
    min-width: 20px; /* Smaller min-width */
    text-align: center;
    line-height: 1; /* Ensure text is centered vertically */
}

.attention-item {
    justify-content: space-between;
    align-items: center;
}

.attention-content {
    flex: 1;
    min-width: 0;
}

.attention-content h4 {
    margin: 0 0 0.25rem 0;
    color: #1f2937;
    font-size: 0.875rem;
    font-weight: 600;
}

.attention-content p {
    margin: 0 0 0.25rem 0;
    color: #6b7280;
    font-size: 0.75rem;
}

.due-date {
    color: #ef4444;
    font-size: 0.75rem;
    font-weight: 600;
}
.attention-item.urgent .due-date { color: #ef4444; }
.attention-item.warning .due-date { color: #f59e0b; }
.attention-item.info .due-date { color: #3b82f6; }


.btn-small {
    padding: 0.5rem 1rem;
    border: 1px solid #d1d5db;
    background: white;
    color: #4f46e5;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.75rem;
    font-weight: 500;
    flex-shrink: 0;
}

.btn-small:hover {
    background: #f9fafb;
    border-color: #4f46e5;
}
.btn-small.primary {
     background: #4f46e5;
    color: white;
    border-color: #4f46e5;
}
.btn-small.primary:hover {
    background: #4338ca;
}


.schedule-date {
    color: #6b7280;
    font-size: 0.875rem;
    font-weight: 500;
}

.announcements-banner {
    margin-top: 2rem;
}

.announcement-item {
    background: #eff6ff; /* Light blue */
    border: 1px solid #dbeafe; /* Lighter blue border */
    border-radius: 8px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.announcement-icon {
    font-size: 1rem;
    color: #3b82f6; /* Blue icon */
}

.announcement-content {
    flex: 1;
    font-size: 0.875rem;
    color: #1f2937;
}

.close-announcement {
    background: none;
    border: none;
    font-size: 1.25rem;
    cursor: pointer;
    color: #6b7280;
    padding: 0.25rem;
    border-radius: 4px;
}

.close-announcement:hover {
    background: #e5e7eb; /* Light gray hover */
    color: #374151;
}

/* Tab content visibility */
.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

/* General Styles for other tabs (Courses, Students, Assignments, Grades, Calendar, Profile, Analytics) */
.section-title {
  font-size: 1.5rem; /* Slightly smaller than dashboard h2 */
  font-weight: 600;
  margin-bottom: 1.5rem; /* Consistent margin */
  color: #1f2937;
  /* Removed padding here, will be handled by .tab-content-container */
}

.card-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); /* Adjusted minmax for better fit */
  gap: 1.5rem; /* Consistent gap */
  /* Removed padding, handled by .tab-content-container */
}

.card { /* General card style for courses, assignments etc. */
  background-color: #fff;
  border-radius: 12px;
  padding: 1.5rem; /* Consistent padding */
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06); /* Softer shadow */
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  border: 1px solid #e5e7eb; 
  display: flex; /* Added for better internal layout control */
  flex-direction: column; /* Stack content vertically */
}

.card:hover {
  transform: translateY(-3px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
}

.card-title {
  font-size: 1.125rem; /* Consistent with .card-header h3 */
  font-weight: 600;
  margin-top: 0;
  margin-bottom: 0.5rem; /* Space below title */
  color: #1f2937;
}

.card-desc {
  font-size: 0.875rem;
  color: #6b7280;
  line-height: 1.5;
  flex-grow: 1; /* Allow description to take available space */
}
.card-meta { /* For additional small info in cards */
    font-size: 0.8rem;
    color: #6b7280;
    margin-top: auto; /* Pushes meta to bottom if card height varies */
    padding-top: 0.75rem; /* Space above meta */
}


.table-container {
  overflow-x: auto;
  background: #fff;
  padding: 1.5rem; /* Consistent padding */
  border-radius: 12px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);
  border: 1px solid #e5e7eb;
}

.dashboard-table { /* Table style for students, grades */
  width: 100%;
  border-collapse: collapse;
}

.dashboard-table th,
.dashboard-table td {
  padding: 1rem 1.25rem; /* Generous padding */
  text-align: left;
  border-bottom: 1px solid #f3f4f6; /* Lighter border for rows */
  font-size: 0.875rem;
  vertical-align: middle; /* Align content vertically */
}

.dashboard-table th {
  background-color: #f9fafb; /* Very light gray for header */
  font-weight: 600;
  color: #374151; /* Darker gray for header text */
  text-transform: uppercase;
  letter-spacing: 0.025em;
  font-size: 0.75rem;
}
.dashboard-table tbody tr:hover {
    background-color: #f8fafc; /* Slight hover effect for rows */
}


/* Styles for Calendar Tab */
.calendar-card {
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    overflow: hidden; /* For list inside */
    box-shadow: 0 2px 4px rgba(0,0,0,0.06);
}
/* .calendar-events already styled with .activity-list group */

/* Styles for Profile Tab */
.profile-card {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 2px 4px rgba(0,0,0,0.06);
    max-width: 700px; /* Limit width for better readability */
    margin-left: auto; /* Centering if max-width is applied */
    margin-right: auto;
}
.profile-header {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #f3f4f6;
}
.profile-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover; /* Ensure image covers space without distortion */
    border: 3px solid #e5e7eb;
}
.profile-name {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 0.25rem 0;
}
.profile-role {
    font-size: 1rem;
    color: #6b7280;
    margin: 0;
}
.profile-details p {
    font-size: 0.9rem; /* Consistent font size for details */
    color: #374151;
    margin-bottom: 0.75rem; /* Space between detail lines */
    line-height: 1.6;
}
.profile-details p strong {
    color: #1f2937;
    font-weight: 500;
    min-width: 120px; /* Align detail labels somewhat, increased from 80px */
    display: inline-block;
}

/* Styles for Analytics Tab */
.analytics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1.5rem;
}
.analytics-card { /* Similar to stat-card but can be distinct if needed */
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    text-align: left; /* Or center if preferred for metrics */
    box-shadow: 0 2px 4px rgba(0,0,0,0.06);
}
.analytics-metric {
    font-size: 2.25rem; /* Large metric numbers */
    font-weight: 600;
    color: #4f46e5; /* Use accent color */
    margin: 0 0 0.25rem 0;
    line-height: 1;
}
.analytics-label {
    color: #6b7280;
    margin: 0;
    font-size: 0.875rem;
    font-weight: 500;
}


/* Responsive Design Adjustments */
@media (max-width: 768px) {
    .header {
        padding: 1rem;
        flex-wrap: wrap; /* Allow header items to wrap on small screens */
    }
    .header h1 { font-size: 1.25rem; }
    .user-info { margin-left: 0; margin-top: 0.5rem; width: 100%; justify-content: space-between;} /* Adjust user info on small screens */
    
    .tabs { padding: 0 1rem; flex-wrap: wrap; } /* Allow tabs to wrap */
    .tab { padding: 0.75rem 1rem; font-size: 0.8rem; }

    .dashboard-container, .tab-content-container {
        padding: 1rem;
    }
    
    .stats-grid, .analytics-grid {
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); /* Smaller cards for mobile */
        gap: 1rem;
    }
    
    .dashboard-main {
        grid-template-columns: 1fr; /* Stack cards in dashboard main section */
    }
    
    .quick-actions {
        grid-template-columns: 1fr; /* Stack quick action buttons */
    }
    
    .stat-card, .analytics-card, .card {
        padding: 1.25rem;
    }
    
    .stat-content h3, .analytics-metric {
        font-size: 2rem;
    }
    .profile-header { flex-direction: column; text-align: center; }
    .profile-avatar { width: 80px; height: 80px; }

    /* Responsive filter/search bars */
    .tab-content-container > div:first-child { /* Target the div containing title and filters */
        flex-direction: column;
        align-items: stretch; /* Make children full width */
    }
    .tab-content-container > div:first-child > div { /* Target filter container */
        display: flex;
        flex-direction: column;
        gap: 0.5rem; /* Space between filter elements */
        margin-top: 1rem;
    }
     .tab-content-container > div:first-child input[type="text"],
    .tab-content-container > div:first-child select {
        width: 100%; /* Make form elements full width */
        margin-right: 0; /* Reset margin for stacked layout */
    }


}

@media (max-width: 480px) {
    .stats-grid, .analytics-grid, .card-grid {
        grid-template-columns: 1fr; /* Single column for very small screens */
    }
    
    .dashboard-header h2, .section-title {
        font-size: 1.25rem;
    }
    
    .activity-item, .schedule-item, .attention-item, .calendar-events li {
        padding: 1rem;
        gap: 0.75rem;
    }
    .dashboard-table th, .dashboard-table td { padding: 0.75rem; font-size: 0.8rem;}
}

</style>



    <script>
        // Tab switching functionality
        const tabs = document.querySelectorAll('.tab');
        const tabContents = document.querySelectorAll('.tab-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const targetTabId = tab.getAttribute('data-tab');
                const targetTabContent = document.getElementById(targetTabId);
                
                // Remove active class from all tabs and contents
                tabs.forEach(t => t.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));
                
                // Add active class to clicked tab and corresponding content
                tab.classList.add('active');
                if (targetTabContent) {
                    targetTabContent.classList.add('active');
                }
            });
        });

        // Profile section switching (if you have sub-sections within profile)
        const profileMenuItems = document.querySelectorAll('.profile-menu-item'); // Ensure these classes exist if used
        const profileSections = document.querySelectorAll('.profile-section');  // Ensure these classes exist if used

        profileMenuItems.forEach(item => {
            item.addEventListener('click', () => {
                const targetSectionId = item.getAttribute('data-section');
                const targetSection = document.getElementById(targetSectionId);
                
                profileMenuItems.forEach(i => i.classList.remove('active'));
                profileSections.forEach(section => section.classList.remove('active'));
                
                item.classList.add('active');
                if (targetSection) {
                    targetSection.classList.add('active');
                }
            });
        });

        // Modal functionality
        const modals = document.querySelectorAll('.modal, .student-details-modal, .create-assignment-modal'); // Ensure these classes exist if used
        const closeButtons = document.querySelectorAll('.close-modal'); // Ensure these classes exist if used

        closeButtons.forEach(button => {
            button.addEventListener('click', () => {
                const modal = button.closest('.modal, .student-details-modal, .create-assignment-modal');
                if (modal) {
                    modal.style.display = 'none';
                }
            });
        });

        // Close modal when clicking outside
        window.addEventListener('click', (e) => {
            modals.forEach(modal => {
                if (e.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });

        // Student details modal
        const viewProfileButtons = document.querySelectorAll('.view-profile'); // Ensure these classes exist if used
        const studentModal = document.getElementById('studentModal'); // Ensure this ID exists if used

        if (studentModal) {
            viewProfileButtons.forEach(button => {
                button.addEventListener('click', () => {
                    // Placeholder: Log to console or show an alert.
                    // In a real app, you'd populate and display the modal here.
                    console.log('View student details for:', button.closest('tr').querySelector('td:nth-child(2)').textContent);
                    alert('Student details modal would show here (not implemented in this placeholder).');
                    // studentModal.style.display = 'block'; 
                });
            });
        }

        // Create assignment modal
        const createAssignmentBtn = document.querySelector('.btn-create-assignment'); // Ensure this class exists if used
        const createAssignmentModal = document.getElementById('createAssignmentModal'); // Ensure this ID exists if used

        if (createAssignmentBtn /*&& createAssignmentModal*/) { // Modal HTML not provided, so commented out check
            createAssignmentBtn.addEventListener('click', () => {
                 // Placeholder: Log to console or show an alert.
                console.log('Create new assignment button clicked.');
                alert('Create assignment modal would show here (not implemented in this placeholder).');
                // createAssignmentModal.style.display = 'block';
            });
        }


        // Student tabs in modal (if you have tabs within a student modal)
        const studentTabs = document.querySelectorAll('.student-tab'); // Ensure these classes exist if used
        const studentTabContents = document.querySelectorAll('.student-tab-content'); // Ensure these classes exist if used

        studentTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const targetTabId = tab.getAttribute('data-tab');
                const targetStudentTabContent = document.getElementById(targetTabId);
                
                studentTabs.forEach(t => t.classList.remove('active'));
                studentTabContents.forEach(content => content.classList.remove('active'));
                
                tab.classList.add('active');
                if (targetStudentTabContent) {
                    targetStudentTabContent.classList.add('active');
                }
            });
        });

        // Theme toggle functionality
        const themeToggle = document.querySelector('.theme-toggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                document.body.classList.toggle('dark-theme'); // Define .dark-theme styles in CSS
                localStorage.setItem('theme', document.body.classList.contains('dark-theme') ? 'dark' : 'light');
                // Simple visual feedback for the button itself if it contains an icon or text
                themeToggle.textContent = document.body.classList.contains('dark-theme') ? '☀️' : '🌙';

            });
             // Set initial icon based on theme
            themeToggle.textContent = document.body.classList.contains('dark-theme') ? '☀️' : '🌙';
        }


        // Load saved theme
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark' && document.body.classList) { // Check if classList is supported
            document.body.classList.add('dark-theme');
        }


        // Calendar functionality (view switching, if any)
        const calendarViewButtons = document.querySelectorAll('.view-btn'); // Ensure these classes exist if used
        calendarViewButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                calendarViewButtons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                // Add calendar view switching logic here
                console.log('Calendar view switched to:', btn.textContent);
            });
        });

        // Form submission handlers (generic, can be specified)
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                // Add specific form submission logic here, e.g., using FormData and fetch
                console.log('Form submitted:', form.id || 'Unnamed Form');
                alert('Form submitted successfully (placeholder)!');
            });
        });
        
        // Close announcement banner
        const closeAnnouncementButton = document.querySelector('.close-announcement');
        if (closeAnnouncementButton) {
            closeAnnouncementButton.addEventListener('click', () => {
                const banner = closeAnnouncementButton.closest('.announcements-banner');
                if (banner) {
                    banner.style.display = 'none';
                }
            });
        }


        // Initialize charts (placeholder for Chart.js integration)
        function initializeCharts() {
            // This would integrate with Chart.js or similar library
            // Example: if (document.getElementById('myPerformanceChart')) { new Chart(...) }
            console.log('Charts initialized (placeholder)');
        }

        // Call initialization functions
        document.addEventListener('DOMContentLoaded', () => {
            initializeCharts();
            // Activate the first tab if no other is active (e.g., from URL hash)
            if (!document.querySelector('.tab.active') && tabs.length > 0) {
                tabs[0].click(); // Programmatically click the first tab
            }

            // Set initial theme toggle icon if not already set by click listener
            const themeToggleButton = document.querySelector('.theme-toggle');
            if (themeToggleButton && !themeToggleButton.textContent) {
                 themeToggleButton.textContent = document.body.classList.contains('dark-theme') ? '☀️' : '🌙';
            }
        });
    </script>
</body>
</html>