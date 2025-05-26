<?php
// Start session
session_start();

// Include database connection
include 'connect.php';

// Check if user is logged in
if(!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Check if logged in user is an admin
$email = $_SESSION['email'];
// Use prepared statement to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND userRole='admin'");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0) {
    // User is not an admin, redirect to homepage
    header("Location: homepage.php");
    exit();
}

// Fetch admin info
$adminInfo = $result->fetch_assoc();
$userInitial = strtoupper(substr($adminInfo['firstName'], 0, 1));
$firstName = $adminInfo['firstName'];

// Initialize variables for user management
$userMessage = "";
$users = [];

// Handle user deletion
if(isset($_POST['deleteUser'])) {
    $userId = $_POST['userId'];
    // Use prepared statement for deletion
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    
    if($stmt->execute()) {
        $userMessage = "User deleted successfully";
    } else {
        $userMessage = "Error deleting user: " . $conn->error;
    }
}

// Handle user role update
if(isset($_POST['updateRole'])) {
    $userId = $_POST['userId'];
    $newRole = $_POST['newRole'];
    
    // Validate role input
    $validRoles = ['student', 'teacher', 'admin'];
    if(!in_array($newRole, $validRoles)) {
        $userMessage = "Invalid role specified";
    } else {
        // Use prepared statement for update
        $stmt = $conn->prepare("UPDATE users SET userRole = ? WHERE id = ?");
        $stmt->bind_param("si", $newRole, $userId);
        
        if($stmt->execute()) {
            $userMessage = "User role updated successfully";
        } else {
            $userMessage = "Error updating user role: " . $conn->error;
        }
    }
}

// Fetch all users - keep only essential data that needs DB sync
$stmt = $conn->prepare("SELECT id, firstName, lastName, email, userRole, createdAt FROM users ORDER BY createdAt DESC");
$stmt->execute();
$userResult = $stmt->get_result();

if($userResult->num_rows > 0) {
    while($row = $userResult->fetch_assoc()) {
        $users[] = $row;
    }
}

// Get role counts from placeholder data instead of direct DB query
$roleCounts = [
    'student' => 85,
    'teacher' => 12,
    'admin' => 3
];

// Recent registrations - placeholder value
$recentCount = 7;

// Additional stats - placeholders
$userCount = count($users);
$activeCourses = 24;
$pendingAssignments = 37;

// Placeholder for course data
$coursesList = [];
for($i = 1; $i <= 5; $i++) {
    $coursesList[] = [
        'id' => $i,
        'title' => "Course Title $i",
        'student_count' => rand(10, 50),
        'status' => ($i % 3 == 0) ? 'archived' : 'active'
    ];
}

// Placeholder for instructors data - keeping real names synced with DB
$instructors = [];
$stmt = $conn->prepare("SELECT id, firstName, lastName FROM users WHERE userRole = 'teacher'");
$stmt->execute();
$instructorResult = $stmt->get_result();
if($instructorResult->num_rows > 0) {
    while($row = $instructorResult->fetch_assoc()) {
        $instructors[] = $row;
    }
}

// Placeholder content data
$contentList = [];
for($i = 1; $i <= 5; $i++) {
    $contentList[] = [
        'id' => $i,
        'title' => "Content Title $i",
        'status' => ($i % 2 == 0) ? 'draft' : 'published'
    ];
}

// Placeholder assignments data
$assignmentsList = [];
for($i = 1; $i <= 5; $i++) {
    $assignmentsList[] = [
        'id' => $i,
        'title' => "Assignment Title $i",
        'type' => ($i % 3 == 0) ? 'exam' : 'assignment',
        'due_date' => date('Y-m-d', strtotime("+$i days"))
    ];
}

// Placeholder for recent activities
$activities = [];
for($i = 1; $i <= 3; $i++) {
    $activities[] = [
        'description' => "Activity $i",
        'activity_date' => date('Y-m-d H:i:s', strtotime("-$i hours"))
    ];
}

// Placeholder for notifications
$notifications = [];
$notificationTypes = ['info', 'warning', 'success', 'error'];
for($i = 1; $i <= 3; $i++) {
    $notifications[] = [
        'message' => "Notification $i",
        'type' => $notificationTypes[array_rand($notificationTypes)],
        'created_at' => date('Y-m-d H:i:s', strtotime("-$i hours"))
    ];
}

// Placeholder for settings
$settings = [
    'site_title' => 'StudyCraft Learning Management',
    'site_description' => 'Advanced learning platform for programming courses',
    'maintenance_mode' => '0',
    'default_language' => 'en',
    'allow_registration' => '1',
    'email_verification' => '1',
    'default_role' => 'student',
    'two_factor_auth' => '0',
    'password_policy' => 'medium',
    'login_attempts' => '5',
    'theme' => 'default'
];

// Placeholder for recent logins
$recentLogins = [];
for($i = 1; $i <= 5; $i++) {
    $recentLogins[] = [
        'first_name' => "User",
        'last_name' => "$i",
        'role' => ($i % 3 == 0) ? 'teacher' : 'student',
        'login_time' => date('Y-m-d H:i:s', strtotime("-$i hours"))
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudyCraft - Admin Dashboard</title>
</head>
<link rel="stylesheet" href="style.css">
<body>
    <header class="header">
        <span class="admin-badge">Administrator</span>
        <h1>StudyCraft</h1>
        <p>Administration Dashboard</p>
        <div class="user-info">
            <div class="user-avatar"><?php echo $userInitial; ?></div>
            <span>Welcome, <?php echo htmlspecialchars($adminInfo['firstName'] . ' ' . $adminInfo['lastName']); ?></span>
        </div>
        <button class="theme-toggle" aria-label="Toggle dark mode"></button>
        <a href="logout.php" class="logout-btn">Logout</a>
    </header>
    
    <nav class="tabs">
        <button class="tab active" data-tab="dashboard">Dashboard</button>
        <button class="tab" data-tab="users">User Management</button>
        <button class="tab" data-tab="courses">Course Management</button>
        <button class="tab" data-tab="content">Content Management</button>
        <button class="tab" data-tab="settings">Site Settings</button>
        <button class="tab" data-tab="reports">Analytics</button>
    </nav>
    
    <section id="dashboard" class="tab-content">
        <h2>Admin Overview</h2>
        <div class="grid">
            <div class="card">
                <h3>Total Users</h3>
                <p>All registered users in the system</p>
                <div class="value"><?php echo count($users); ?></div>
            </div>
            
            <div class="card">
                <h3>Students</h3>
                <p>Currently enrolled students</p>
                <div class="value"><?php echo $roleCounts['student']; ?></div>
            </div>
            
            <div class="card">
                <h3>Teachers</h3>
                <p>Active teachers</p>
                <div class="value"><?php echo $roleCounts['teacher']; ?></div>
            </div>
            
            <div class="card">
                <h3>Admins</h3>
                <p>System administrators</p>
                <div class="value"><?php echo $roleCounts['admin']; ?></div>
            </div>
            
            <div class="card">
                <h3>Active Courses</h3>
                <p>Currently running courses</p>
                <div class="value"><?php echo $activeCourses; ?></div>
            </div>
            
            <div class="card">
                <h3>New Users (Last 7 Days)</h3>
                <p>Recent registrations</p>
                <div class="value"><?php echo $recentCount; ?></div>
            </div>
        </div>
        
        <div class="recent-activity">
            <h2>Recent Registrations</h2>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Registration Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $count = 0;
                    foreach($users as $user): 
                        if($count < 5): // Show only 5 most recent
                            $count++;
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['firstName'] . ' ' . $user['lastName']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo ucfirst($user['userRole']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($user['createdAt'])); ?></td>
                    </tr>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </tbody>
            </table>
        </div>
        
        <h2>Recent Activities</h2>
        <ul>
            <?php if (count($activities) > 0): ?>
                <?php foreach ($activities as $activity): ?>
                    <li>
                        <span><?php echo htmlspecialchars($activity['description']); ?></span>
                        <span><?php echo date('F j, g:i A', strtotime($activity['activity_date'])); ?></span>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>
                    <span>No recent activities</span>
                </li>
            <?php endif; ?>
        </ul>
        
        <h2>System Notifications</h2>
        <ul>
            <?php if (count($notifications) > 0): ?>
                <?php foreach ($notifications as $notification): ?>
                    <li>
                        <span><?php echo htmlspecialchars($notification['message']); ?></span>
                        <span class="status status-<?php echo $notification['type']; ?>"><?php echo ucfirst($notification['type']); ?></span>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>
                    <span>No system notifications</span>
                </li>
            <?php endif; ?>
        </ul>
    </section>
    
    <section id="users" class="tab-content">
        <h2>User Management</h2>
        
        <?php if(!empty($userMessage)): ?>
        <div class="message"><?php echo htmlspecialchars($userMessage); ?></div>
        <?php endif; ?>
        
        <div class="search-container">
            <input type="text" id="userSearch" placeholder="Search users by name, email, or role...">
            <button class="search-btn"></button>
        </div>
        <button id="addUserBtn">Add New User</button>
        
        <table class="users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Registration Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['firstName'] . ' ' . $user['lastName']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td>
                        <form method="POST" class="inline-form">
                            <input type="hidden" name="userId" value="<?php echo $user['id']; ?>">
                            <select name="newRole" onchange="this.form.submit()" <?php echo ($user['id'] == $adminInfo['id']) ? 'disabled' : ''; ?>>
                                <option value="student" <?php echo ($user['userRole'] == 'student') ? 'selected' : ''; ?>>Student</option>
                                <option value="teacher" <?php echo ($user['userRole'] == 'teacher') ? 'selected' : ''; ?>>Teacher</option>
                                <option value="admin" <?php echo ($user['userRole'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                            </select>
                            <input type="hidden" name="updateRole" value="1">
                        </form>
                    </td>
                    <td><?php echo date('M d, Y', strtotime($user['createdAt'])); ?></td>
                    <td>
                        <?php if($user['id'] != $adminInfo['id']): ?>
                        <form method="POST" class="inline-form" onsubmit="return confirm('Are you sure you want to delete this user?');">
                            <input type="hidden" name="userId" value="<?php echo $user['id']; ?>">
                            <button type="submit" name="deleteUser" class="delete-btn">Delete</button>
                        </form>
                        <button class="action-btn edit" data-id="<?php echo $user['id']; ?>">Edit</button>
                        <?php else: ?>
                        <span class="current-user">Current User</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
    
    <section id="courses" class="tab-content">
        <h2>Course Management</h2>
        <div class="search-container">
            <input type="text" id="courseSearch" placeholder="Search courses...">
            <button class="search-btn"></button>
        </div>
        <button id="addCourseBtn">Add New Course</button>
        <ul>
            <?php if (count($coursesList) > 0): ?>
                <?php foreach ($coursesList as $course): ?>
                    <li>
                        <span><?php echo htmlspecialchars($course['title']); ?></span>
                        <div>
                            <span><?php echo $course['student_count']; ?> students</span>
                            <button class="action-btn edit" data-id="<?php echo $course['id']; ?>">Edit</button>
                            <button class="action-btn delete" data-id="<?php echo $course['id']; ?>">Delete</button>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>
                    <span>No courses found</span>
                </li>
            <?php endif; ?>
        </ul>
    </section>
    
    <section id="content" class="tab-content">
        <h2>Content Management</h2>
        <div class="search-container">
            <input type="text" id="contentSearch" placeholder="Search content...">
            <button class="search-btn"></button>
        </div>
        <button id="addContentBtn">Add New Content</button>
        <ul>
            <?php if (count($contentList) > 0): ?>
                <?php foreach ($contentList as $content): ?>
                    <li>
                        <span><?php echo htmlspecialchars($content['title']); ?></span>
                        <div>
                            <span class="status status-<?php echo $content['status'] == 'published' ? 'active' : 'inactive'; ?>"><?php echo ucfirst($content['status']); ?></span>
                            <button class="action-btn edit" data-id="<?php echo $content['id']; ?>">Edit</button>
                            <button class="action-btn delete" data-id="<?php echo $content['id']; ?>">Delete</button>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>
                    <span>No content found</span>
                </li>
            <?php endif; ?>
        </ul>
        
        <h2>Assignments & Exams</h2>
        <ul>
            <?php if (count($assignmentsList) > 0): ?>
                <?php foreach ($assignmentsList as $assignment): ?>
                    <li>
                        <span><?php echo htmlspecialchars($assignment['title']); ?></span>
                        <div>
                            <span><?php echo $assignment['type'] == 'exam' ? 'Date: ' : 'Due: '; ?><?php echo date('Y-m-d', strtotime($assignment['due_date'])); ?></span>
                            <button class="action-btn edit" data-id="<?php echo $assignment['id']; ?>">Edit</button>
                            <button class="action-btn delete" data-id="<?php echo $assignment['id']; ?>">Delete</button>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>
                    <span>No assignments or exams found</span>
                </li>
            <?php endif; ?>
        </ul>
    </section>

    <section id="settings" class="tab-content">
        <h2>Site Settings</h2>
        <form id="settingsForm" method="post" action="save_settings.php">
            <h3 style="margin-top: 1rem; font-size: 1.3rem; color: #2c3e50;">General Settings</h3>
            <div class="setting-item">
                <div class="setting-label">
                    <span>Site Title</span>
                    <span class="setting-description">The name of your StudyCraft instance</span>
                </div>
                <input type="text" name="site_title" value="<?php echo htmlspecialchars($settings['site_title']); ?>" style="max-width: 300px; margin-bottom: 0;">
            </div>
            <div class="setting-item">
                <div class="setting-label">
                    <span>Site Description</span>
                    <span class="setting-description">Brief description displayed in search results</span>
                </div>
                <input type="text" name="site_description" value="<?php echo htmlspecialchars($settings['site_description']); ?>" style="max-width: 300px; margin-bottom: 0;">
            </div>
            <div class="setting-item">
                <div class="setting-label">
                    <span>Maintenance Mode</span>
                    <span class="setting-description">Enable to display maintenance page to non-admin users</span>
                </div>
                <label class="switch">
                    <input type="checkbox" name="maintenance_mode" <?php echo ($settings['maintenance_mode'] == '1') ? 'checked' : ''; ?>>
                    <span class="slider"></span>
                </label>
            </div>
            <div class="setting-item">
                <div class="setting-label">
                    <span>Default Language</span>
                    <span class="setting-description">System-wide default language setting</span>
                </div>
                <select name="default_language" style="max-width: 300px; margin-bottom: 0;">
                    <option value="en" <?php echo ($settings['default_language'] == 'en') ? 'selected' : ''; ?>>English</option>
                    <option value="es" <?php echo ($settings['default_language'] == 'es') ? 'selected' : ''; ?>>Spanish</option>
                    <option value="fr" <?php echo ($settings['default_language'] == 'fr') ? 'selected' : ''; ?>>French</option>
                    <option value="de" <?php echo ($settings['default_language'] == 'de') ? 'selected' : ''; ?>>German</option>
                    <option value="zh" <?php echo ($settings['default_language'] == 'zh') ? 'selected' : ''; ?>>Chinese</option>
                </select>
            </div>
            
            <h3 style="margin-top: 2rem; font-size: 1.3rem; color: #2c3e50;">Registration Settings</h3>
            <div class="setting-item">
                <div class="setting-label">
                    <span>Allow New Registrations</span>
                    <span class="setting-description">Enable user registration on the site</span>
                </div>
                <label class="switch">
                    <input type="checkbox" name="allow_registration" <?php echo ($settings['allow_registration'] == '1') ? 'checked' : ''; ?>>
                    <span class="slider"></span>
                </label>
            </div>
            <div class="setting-item">
                <div class="setting-label">
                    <span>Email Verification</span>
                    <span class="setting-description">Require email verification for new accounts</span>
                </div>
                <label class="switch">
                    <input type="checkbox" name="email_verification" <?php echo ($settings['email_verification'] == '1') ? 'checked' : ''; ?>>
                    <span class="slider"></span>
                </label>
            </div>
            <div class="setting-item">
                <div class="setting-label">
                    <span>Default User Role</span>
                    <span class="setting-description">Role assigned to newly registered users</span>
                </div>
                <select name="default_role" style="max-width: 300px; margin-bottom: 0;">
                    <option value="student" <?php echo ($settings['default_role'] == 'student') ? 'selected' : ''; ?>>Student</option>
                    <option value="teacher" <?php echo ($settings['default_role'] == 'teacher') ? 'selected' : ''; ?>>Teacher</option>
                    <option value="admin" <?php echo ($settings['default_role'] == 'admin') ? 'selected' : ''; ?>>Administrator</option>
                </select>
            </div>
            
            <h3 style="margin-top: 2rem; font-size: 1.3rem; color: #2c3e50;">Security Settings</h3>
            <div class="setting-item">
                <div class="setting-label">
                    <span>Two-Factor Authentication</span>
                    <span class="setting-description">Require 2FA for administrator accounts</span>
                </div>
                <label class="switch">
                    <input type="checkbox" name="two_factor_auth" <?php echo ($settings['two_factor_auth'] == '1') ? 'checked' : ''; ?>>
                    <span class="slider"></span>
                </label>
            </div>
            <div class="setting-item">
                <div class="setting-label">
                    <span>Password Policy</span>
                    <span class="setting-description">Minimum requirements for user passwords</span>
                </div>
                <select name="password_policy" style="max-width: 300px; margin-bottom: 0;">
                    <option value="basic" <?php echo ($settings['password_policy'] == 'basic') ? 'selected' : ''; ?>>Basic (8+ characters)</option>
                    <option value="medium" <?php echo ($settings['password_policy'] == 'medium') ? 'selected' : ''; ?>>Medium (8+ chars with numbers)</option>
                    <option value="strong" <?php echo ($settings['password_policy'] == 'strong') ? 'selected' : ''; ?>>Strong (12+ chars, mixed case, numbers, symbols)</option>
                </select>
            </div>
            <div class="setting-item">
                <div class="setting-label">
                    <span>Login Attempts</span>
                    <span class="setting-description">Maximum failed login attempts before lockout</span>
                </div>
                <select name="login_attempts" style="max-width: 300px; margin-bottom: 0;">
                    <option value="3" <?php echo ($settings['login_attempts'] == '3') ? 'selected' : ''; ?>>3 attempts</option>
                    <option value="5" <?php echo ($settings['login_attempts'] == '5') ? 'selected' : ''; ?>>5 attempts</option>
                    <option value="10" <?php echo ($settings['login_attempts'] == '10') ? 'selected' : ''; ?>>10 attempts</option>
                </select>
            </div>
            
            <h3 style="margin-top: 2rem; font-size: 1.3rem; color: #2c3e50;">Appearance Settings</h3>
            <div class="setting-item">
                <div class="setting-label">
                    <span>Theme</span>
                    <span class="setting-description">Visual theme for the StudyCraft platform</span>
                </div>
                <select name="theme" style="max-width: 300px; margin-bottom: 0;">
                    <option value="default" <?php echo ($settings['theme'] == 'default') ? 'selected' : ''; ?>>Default</option>
                    <option value="modern" <?php echo ($settings['theme'] == 'modern') ? 'selected' : ''; ?>>Modern</option>
                    <option value="classic" <?php echo ($settings['theme'] == 'classic') ? 'selected' : ''; ?>>Classic</option>
                    <option value="dark" <?php echo ($settings['theme'] == 'dark') ? 'selected' : ''; ?>>Dark</option>
                </select>
            </div>
            <div class="setting-item">
                <div class="setting-label">
                    <span>Custom Logo</span>
                    <span class="setting-description">Upload your organization's logo</span>
                </div>
                <button type="button" class="action-btn edit" id="uploadLogoBtn" style="margin-bottom: 0;">Upload</button>
            </div>
            
            <button type="submit" class="btn-save" style="margin-top: 2rem;">Save Settings</button>
        </form>
    </section>
    
    <section id="reports" class="tab-content">
        <h2>Analytics & Reports</h2>
        
        <div class="grid">
            <div class="card">
                <h3>User Registration</h3>
                <p>New users in the last 30 days</p>
                <div class="value"><?php echo rand(10, 50); ?></div>
            </div>
            <div class="card">
                <h3>Course Completion</h3>
                <p>Average completion rate</p>
                <div class="value"><?php echo rand(50, 95); ?>%</div>
            </div>
            <div class="card">
                <h3>Assignment Submissions</h3>
                <p>Submissions in the last week</p>
                <div class="value"><?php echo rand(50, 200); ?></div>
            </div>
            <div class="card">
                <h3>Active Sessions</h3>
                <p>Current active users</p>
                <div class="value"><?php echo rand(10, 60); ?></div>
            </div>
        </div>
        
        <h2>Generate Reports</h2>
        <form method="post" action="generate_report.php" class="report-form">
            <input type="hidden" name="report_type" id="report_type" value="">
            <button type="button" class="no-icon report-btn" data-report="user">User Activity Report</button>
            <button type="button" class="no-icon report-btn" data-report="course">Course Engagement Report</button>
            <button type="button" class="no-icon report-btn" data-report="assignment">Assignment Completion Report</button>
            <button type="button" class="no-icon report-btn" data-report="system">System Performance Report</button>
        </form>
        
        <h2>Recent Logins</h2>
        <ul>
            <?php if (count($recentLogins) > 0): ?>
                <?php foreach ($recentLogins as $login): ?>
                    <li>
                        <span><?php echo htmlspecialchars($login['first_name'] . ' ' . $login['last_name'] . ' (' . ucfirst($login['role']) . ')'); ?></span>
                        <span><?php echo date('F j, g:i A', strtotime($login['login_time'])); ?></span>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>
                    <span>No recent logins</span>
                </li>
            <?php endif; ?>
        </ul>
    </section>
    
    <!-- Modal for adding/editing users -->
    <div class="modal-overlay" id="userModal">
        <div class="modal">
            <h3>Add New User</h3>
            <button class="close-btn" id="closeUserModal"></button>
            
            <form id="userForm" method="post" action="save_user.php">
                <input type="hidden" id="userId" name="user_id" value="">
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <input type="text" id="firstName" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" name="last_name" required>
                    </div>
                    </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password">
                    <small>Leave blank to keep existing password when editing</small>
                </div>
                <div class="form-group">
                    <label for="role">User Role</label>
                    <select id="role" name="role" required>
                        <option value="student">Student</option>
                        <option value="teacher">Teacher</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>
                <button type="submit" class="btn-save">Save User</button>
            </form>
        </div>
    </div>
    
    <!-- Modal for adding/editing courses -->
    <div class="modal-overlay" id="courseModal">
        <div class="modal">
            <h3>Add New Course</h3>
            <button class="close-btn" id="closeCourseModal"></button>
            
            <form id="courseForm" method="post" action="save_course.php">
                <input type="hidden" id="courseId" name="course_id" value="">
                <div class="form-group">
                    <label for="courseTitle">Course Title</label>
                    <input type="text" id="courseTitle" name="course_title" required>
                </div>
                <div class="form-group">
                    <label for="courseDescription">Description</label>
                    <textarea id="courseDescription" name="course_description" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="courseInstructor">Instructor</label>
                    <select id="courseInstructor" name="instructor_id" required>
                        <option value="">-- Select Instructor --</option>
                        <?php foreach($instructors as $instructor): ?>
                            <option value="<?php echo $instructor['id']; ?>"><?php echo htmlspecialchars($instructor['firstName'] . ' ' . $instructor['lastName']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="courseStatus">Status</label>
                    <select id="courseStatus" name="course_status">
                        <option value="active">Active</option>
                        <option value="draft">Draft</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
                <button type="submit" class="btn-save">Save Course</button>
            </form>
        </div>
    </div>
    
    <!-- Modal for adding/editing content -->
    <div class="modal-overlay" id="contentModal">
        <div class="modal">
            <h3>Add New Content</h3>
            <button class="close-btn" id="closeContentModal"></button>
            
            <form id="contentForm" method="post" action="save_content.php">
                <input type="hidden" id="contentId" name="content_id" value="">
                <div class="form-group">
                    <label for="contentTitle">Content Title</label>
                    <input type="text" id="contentTitle" name="content_title" required>
                </div>
                <div class="form-group">
                    <label for="contentType">Content Type</label>
                    <select id="contentType" name="content_type">
                        <option value="lesson">Lesson</option>
                        <option value="lecture">Lecture</option>
                        <option value="resource">Resource</option>
                        <option value="assessment">Assessment</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="contentCourse">Associated Course</label>
                    <select id="contentCourse" name="course_id">
                        <option value="">-- Select Course --</option>
                        <?php foreach($coursesList as $course): ?>
                            <option value="<?php echo $course['id']; ?>"><?php echo htmlspecialchars($course['title']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="contentDescription">Description</label>
                    <textarea id="contentDescription" name="content_description" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="contentStatus">Status</label>
                    <select id="contentStatus" name="status">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>
                </div>
                <button type="submit" class="btn-save">Save Content</button>
            </form>
        </div>
    </div>
    
    <!-- JavaScript for interactive functionality -->
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
            
            // User modal functionality
            const userModal = document.getElementById('userModal');
            const userForm = document.getElementById('userForm');
            const addUserBtn = document.getElementById('addUserBtn');
            const closeUserModal = document.getElementById('closeUserModal');
            
            if(addUserBtn) {
                addUserBtn.addEventListener('click', function() {
                    // Reset form for new user
                    userForm.reset();
                    document.getElementById('userId').value = '';
                    document.querySelector('#userModal h3').textContent = 'Add New User';
                    userModal.classList.add('active');
                });
            }
            
            if(closeUserModal) {
                closeUserModal.addEventListener('click', function() {
                    userModal.classList.remove('active');
                });
            }
            
            // Edit user buttons
            const editUserBtns = document.querySelectorAll('.users-table .edit');
            editUserBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const userId = this.getAttribute('data-id');
                    // In a real app, you would fetch user data here
                    document.getElementById('userId').value = userId;
                    document.querySelector('#userModal h3').textContent = 'Edit User';
                    userModal.classList.add('active');
                    
                    // This would be replaced with actual AJAX to get user data
                    // For demo, we'll just populate with placeholder values
                    const userRow = this.closest('tr');
                    const name = userRow.cells[1].textContent.split(' ');
                    document.getElementById('firstName').value = name[0];
                    document.getElementById('lastName').value = name[1] || '';
                    document.getElementById('email').value = userRow.cells[2].textContent;
                    document.getElementById('password').value = '';
                    
                    // Set the role
                    const roleSelect = userRow.cells[3].querySelector('select');
                    document.getElementById('role').value = roleSelect.value;
                });
            });
            
            // Course modal functionality
            const courseModal = document.getElementById('courseModal');
            const courseForm = document.getElementById('courseForm');
            const addCourseBtn = document.getElementById('addCourseBtn');
            const closeCourseModal = document.getElementById('closeCourseModal');
            
            if(addCourseBtn) {
                addCourseBtn.addEventListener('click', function() {
                    courseForm.reset();
                    document.getElementById('courseId').value = '';
                    document.querySelector('#courseModal h3').textContent = 'Add New Course';
                    courseModal.classList.add('active');
                });
            }
            
            if(closeCourseModal) {
                closeCourseModal.addEventListener('click', function() {
                    courseModal.classList.remove('active');
                });
            }
            
            // Edit course buttons
            const editCourseBtns = document.querySelectorAll('#courses .edit');
            editCourseBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const courseId = this.getAttribute('data-id');
                    document.getElementById('courseId').value = courseId;
                    document.querySelector('#courseModal h3').textContent = 'Edit Course';
                    courseModal.classList.add('active');
                    
                    // In a real app, you would fetch course data here
                });
            });
            
            // Content modal functionality
            const contentModal = document.getElementById('contentModal');
            const contentForm = document.getElementById('contentForm');
            const addContentBtn = document.getElementById('addContentBtn');
            const closeContentModal = document.getElementById('closeContentModal');
            
            if(addContentBtn) {
                addContentBtn.addEventListener('click', function() {
                    contentForm.reset();
                    document.getElementById('contentId').value = '';
                    document.querySelector('#contentModal h3').textContent = 'Add New Content';
                    contentModal.classList.add('active');
                });
            }
            
            if(closeContentModal) {
                closeContentModal.addEventListener('click', function() {
                    contentModal.classList.remove('active');
                });
            }
            
            // Edit content buttons
            const editContentBtns = document.querySelectorAll('#content .edit');
            editContentBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const contentId = this.getAttribute('data-id');
                    document.getElementById('contentId').value = contentId;
                    document.querySelector('#contentModal h3').textContent = 'Edit Content';
                    contentModal.classList.add('active');
                    
                    // In a real app, you would fetch content data here
                });
            });
            
            // Search functionality for users
            const userSearch = document.getElementById('userSearch');
            if(userSearch) {
                userSearch.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const userRows = document.querySelectorAll('.users-table tbody tr');
                    
                    userRows.forEach(row => {
                        const name = row.cells[1].textContent.toLowerCase();
                        const email = row.cells[2].textContent.toLowerCase();
                        const role = row.cells[3].querySelector('select').value.toLowerCase();
                        
                        if(name.includes(searchTerm) || email.includes(searchTerm) || role.includes(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
            
            // Delete confirmations for all delete buttons
            const deleteBtns = document.querySelectorAll('.delete');
            deleteBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    if(!confirm('Are you sure you want to delete this item?')) {
                        e.preventDefault();
                    }
                });
            });
            
            // Report generation buttons
            const reportBtns = document.querySelectorAll('.report-btn');
            reportBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const reportType = this.getAttribute('data-report');
                    document.getElementById('report_type').value = reportType;
                    this.form.submit();
                });
            });
            
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
    </script>
    
    <footer>
        <p>&copy; <?php echo date('Y'); ?> StudyCraft Learning Management System</p>
        <p>Version 2.5.0</p>
    </footer>
</body>
</html>