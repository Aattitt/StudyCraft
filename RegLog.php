<?php
// Start output buffering to prevent "headers already sent" errors
ob_start();

// Include database connection
include 'connect.php';

// Initialize error message variable
$errorMessage = "";

// Handle sign up form submission
if(isset($_POST['signUp'])){
    $firstName = $_POST['fName'];
    $lastName = $_POST['lName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $userRole = $_POST['userRole'];
    $password = md5($password);

    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($checkEmail);
    if($result->num_rows > 0){
        $errorMessage = "Email Address Already Exists!";
    }
    else{
        $insertQuery = "INSERT INTO users(firstName, lastName, email, password, userRole)
                      VALUES ('$firstName', '$lastName', '$email', '$password', '$userRole')";
        if($conn->query($insertQuery) == TRUE){
            header("location: index.php");
            exit();
        }
        else{
            $errorMessage = "Error: " . $conn->error;
        }
    }
}

// Handle sign in form submission
if(isset($_POST['signIn'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = md5($password);
     
    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
         session_start();
         $row = $result->fetch_assoc();
         $_SESSION['email'] = $row['email'];
         
         // Redirect based on user role
         switch($row['userRole']) {
             case 'admin':
                 header("Location: admindashboard.php");
                 break;
             case 'teacher':
                 header("Location: teacherdashboard.php");
                 break;
             case 'student':
                 header("Location: studentdashboard.php");
                 break;
             default:
                 header("Location: index.php");
                 break;
         }
         exit();
    }
    else{
         $errorMessage = "Not Found, Incorrect Email or Password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudyCraft - Login & Register</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', 'Segoe UI', Roboto, -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background: linear-gradient(135deg, #4568dc, #b06ab3);
            color: white;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .tabs {
            display: flex;
            justify-content: center;
            background-color: white;
            padding: 0.5rem;
            border-bottom: 1px solid #e0e0e0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .tab {
            background: none;
            border: none;
            padding: 1rem 1.5rem;
            margin: 0 0.2rem;
            font-size: 1rem;
            font-weight: 500;
            color: #555;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 4px;
            position: relative;
        }

        .tab:hover {
            background-color: #f0f0f0;
            color: #4568dc;
        }

        .tab.active {
            background-color: #4568dc;
            color: white;
        }

        .tab.active::after {
            content: '';
            position: absolute;
            bottom: -0.5rem;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 8px solid transparent;
            border-right: 8px solid transparent;
            border-top: 8px solid #4568dc;
        }

        .auth-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }

        .auth-form {
            display: none;
            max-width: 450px;
            width: 100%;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
            animation: fadeIn 0.3s ease forwards;
        }

        .auth-form.active {
            display: block;
        }

        .form-title {
            color: #2c3e50;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            font-weight: 600;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }

        .form-title::before {
            content: '';
            display: inline-block;
            width: 6px;
            height: 24px;
            background-color: #4568dc;
            margin-right: 12px;
            border-radius: 3px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #555;
        }

        .form-control {
            padding: 0.8rem;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #4568dc;
            box-shadow: 0 0 0 2px rgba(69, 104, 220, 0.2);
            transform: translateY(-2px);
        }

        .btn {
            background-color: #4568dc;
            color: white;
            border: none;
            padding: 0.9rem 1.5rem;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 1rem;
            font-size: 1rem;
        }

        .btn:hover {
            background-color: #3a57c4;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(69, 104, 220, 0.2);
        }

        .form-footer {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.9rem;
            color: #666;
        }

        .form-footer a {
            color: #4568dc;
            text-decoration: none;
            font-weight: 500;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        .forgot-password {
            text-align: right;
            margin-top: 0.5rem;
        }

        .forgot-password a {
            color: #4568dc;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .remember-me {
            display: flex;
            align-items: center;
            margin-top: 1rem;
        }

        .remember-me input {
            margin-right: 0.5rem;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: #888;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background-color: #e0e0e0;
        }

        .divider span {
            padding: 0 1rem;
            font-size: 0.9rem;
        }

        .social-login {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
        }

        .social-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.8rem;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid #e0e0e0;
            background-color: white;
            color: #555;
        }

        .social-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .google-btn {
            color: #ea4335;
        }

        .facebook-btn {
            color: #3b5998;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        body.dark-theme {
            background-color: #1a1a2e;
            color: #f0f0f0;
        }

        body.dark-theme .header {
            background: linear-gradient(135deg, #303a52, #574b90);
        }

        body.dark-theme .tabs {
            background-color: #16213e;
            border-bottom: 1px solid #26294b;
        }

        body.dark-theme .tab {
            color: #f0f0f0;
        }

        body.dark-theme .tab:hover {
            background-color: #26294b;
        }

        body.dark-theme .tab.active {
            background-color: #574b90;
        }

        body.dark-theme .tab.active::after {
            border-top: 8px solid #574b90;
        }

        body.dark-theme .auth-form {
            background-color: #16213e;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.3);
        }

        body.dark-theme .form-title {
            color: #f0f0f0;
            border-bottom: 2px solid #26294b;
        }

        body.dark-theme .form-title::before {
            background-color: #574b90;
        }

        body.dark-theme .form-control {
            background-color: #26294b;
            color: #f0f0f0;
            border: 1px solid #303a52;
        }

        body.dark-theme .form-control:focus {
            border-color: #574b90;
            box-shadow: 0 0 0 2px rgba(90, 75, 144, 0.3);
        }

        body.dark-theme .btn {
            background-color: #574b90;
        }

        body.dark-theme .btn:hover {
            background-color: #6a5acd;
        }

        body.dark-theme .form-footer,
        body.dark-theme label {
            color: #c5c5c5;
        }

        body.dark-theme .form-footer a,
        body.dark-theme .forgot-password a {
            color: #a29bfe;
        }

        body.dark-theme .divider {
            color: #c5c5c5;
        }

        body.dark-theme .divider::before,
        body.dark-theme .divider::after {
            background-color: #26294b;
        }

        body.dark-theme .social-btn {
            background-color: #26294b;
            border: 1px solid #303a52;
            color: #f0f0f0;
        }

        .theme-toggle {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.2);
        }

        .theme-toggle:focus {
            outline: none;
        }

        .theme-toggle::before {
            content: '☀️';
        }

        body.dark-theme .theme-toggle::before {
            content: '🌙';
        }
        
        .error-message {
            color: #dc3545;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            padding: 0.5rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            display: none;
        }

        .error-message.show {
            display: block;
        }
        
        body.dark-theme .error-message {
            background-color: #2c1a1a;
            border-color: #442222;
            color: #ff8080;
        }

        @media (max-width: 768px) {
            .auth-container {
                padding: 1rem;
            }
            
            .auth-form {
                padding: 1.5rem;
            }
            
            .tabs {
                flex-wrap: wrap;
            }
            
            .tab {
                flex: 1 1 auto;
                text-align: center;
                padding: 0.8rem;
                margin: 0.2rem;
                font-size: 0.9rem;
            }
            
            .social-login {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>StudyCraft</h1>
        <p>Your learning journey begins here</p>
        <button class="theme-toggle" aria-label="Toggle dark mode"></button>
    </header>
    
    <nav class="tabs">
        <button class="tab active" data-tab="login">Login</button>
        <button class="tab" data-tab="register">Register</button>
    </nav>
    
    <div class="auth-container">
        <!-- Login Form -->
        <form id="login" class="auth-form active" method="POST" action="">
            <h2 class="form-title">Welcome Back</h2>
            
            <?php if(!empty($errorMessage) && isset($_POST['signIn'])): ?>
            <div class="error-message show"><?php echo $errorMessage; ?></div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="login-email">Email Address</label>
                <input type="email" id="login-email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>
            
            <div class="form-group">
                <label for="login-password">Password</label>
                <input type="password" id="login-password" name="password" class="form-control" placeholder="Enter your password" required>
                <div class="forgot-password">
                    <a href="#">Forgot password?</a>
                </div>
            </div>
            
            <div class="remember-me">
                <input type="checkbox" id="remember-me" name="remember">
                <label for="remember-me">Remember me</label>
            </div>
            
            <button type="submit" name="signIn" class="btn">Login</button>
            
            <div class="divider">
                <span>OR</span>
            </div>
            
            <div class="social-login">
                <button type="button" class="social-btn google-btn">
                    Google
                </button>
                <button type="button" class="social-btn facebook-btn">
                    Facebook
                </button>
            </div>
            
            <div class="form-footer">
                Don't have an account? <a href="#" class="tab-link" data-tab="register">Register</a>
            </div>
        </form>
        
        <!-- Register Form -->
        <form id="register" class="auth-form" method="POST" action="">
            <h2 class="form-title">Create Account</h2>
            
            <?php if(!empty($errorMessage) && isset($_POST['signUp'])): ?>
            <div class="error-message show"><?php echo $errorMessage; ?></div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="register-fname">First Name</label>
                <input type="text" id="register-fname" name="fName" class="form-control" placeholder="Enter your first name" required>
            </div>
            
            <div class="form-group">
                <label for="register-lname">Last Name</label>
                <input type="text" id="register-lname" name="lName" class="form-control" placeholder="Enter your last name" required>
            </div>
            
            <div class="form-group">
                <label for="register-email">Email Address</label>
                <input type="email" id="register-email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>
            
            <div class="form-group">
                <label for="register-password">Password</label>
                <input type="password" id="register-password" name="password" class="form-control" placeholder="Create a password" required>
            </div>
            
            <div class="form-group">
                <label for="register-confirm-password">Confirm Password</label>
                <input type="password" id="register-confirm-password" class="form-control" placeholder="Confirm your password" required>
            </div>
            
            <!-- Dropdown menu added here -->
            <div class="form-group">
                <label for="user-role">Account Type</label>
                <select id="user-role" name="userRole" class="form-control" required>
                    <option value="" disabled selected>Account Type</option>
                    <option value="student">Student</option>
                    <option value="teacher">Teacher</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            
            <div class="remember-me">
                <input type="checkbox" id="terms" required>
                <label for="terms">I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></label>
            </div>
            
            <button type="submit" name="signUp" class="btn">Create Account</button>
            
            <div class="divider">
                <span>OR</span>
            </div>
            
            <div class="social-login">
                <button type="button" class="social-btn google-btn">
                    Google
                </button>
                <button type="button" class="social-btn facebook-btn">
                    Facebook
                </button>
            </div>
            
            <div class="form-footer">
                Already have an account? <a href="#" class="tab-link" data-tab="login">Login</a>
            </div>
        </form>
    </div>
    
    <script>
        // Tab switching functionality
        document.querySelectorAll('.tab, .tab-link').forEach(tab => {
            tab.addEventListener('click', (e) => {
                e.preventDefault();
                const tabId = tab.getAttribute('data-tab');
                showTab(tabId);
            });
        });

        function showTab(tabId) {
            // Remove active class from all tabs
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Hide all forms
            document.querySelectorAll('.auth-form').forEach(form => {
                form.classList.remove('active');
            });
            
            // Show the selected form
            document.getElementById(tabId).classList.add('active');
            
            // Add active class to the selected tab
            document.querySelector(`.tab[data-tab="${tabId}"]`).classList.add('active');
            
            // Store the active tab in localStorage
            localStorage.setItem('activeAuthTab', tabId);
        }
        
        // Theme toggle functionality
        const themeToggle = document.querySelector('.theme-toggle');
        themeToggle.addEventListener('click', () => {
            document.body.classList.toggle('dark-theme');
            const isDarkMode = document.body.classList.contains('dark-theme');
            localStorage.setItem('darkMode', isDarkMode);
        });
        
        // Password validation
        document.getElementById('register').addEventListener('submit', function(e) {
            const password = document.getElementById('register-password').value;
            const confirmPassword = document.getElementById('register-confirm-password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
            }
        });
        
        // On page load
        document.addEventListener('DOMContentLoaded', () => {
            // Set active tab from localStorage
            const savedTab = localStorage.getItem('activeAuthTab') || 'login';
            showTab(savedTab);
            
            // Set theme from localStorage
            const darkMode = localStorage.getItem('darkMode') === 'true';
            if (darkMode) {
                document.body.classList.add('dark-theme');
            }
            
            // Show error message if PHP set one
            const errorMessage = document.querySelector('.error-message');
            if (errorMessage && errorMessage.innerHTML.trim() !== '') {
                errorMessage.classList.add('show');
            }
        });
    </script>
</body>
</html>
<?php
// Flush the output buffer and send content to browser
ob_end_flush();
?>