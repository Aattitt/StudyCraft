<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is already logged in
if(isset($_SESSION['email'])) {
    // Redirect based on user role if already logged in
    if(isset($_SESSION['userRole'])) {
        switch($_SESSION['userRole']) {
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
                // Do nothing, show homepage
                break;
        }
    }
}

// Check for theme preference
$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?php echo $theme; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudyCraft - Your Learning Journey</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4763E4;
            --secondary-color: #8A94FF;
            --text-color: #333;
            --bg-color: #fff;
            --card-bg: #fff;
            --shadow-color: rgba(0, 0, 0, 0.1);
            --gradient-start: #4763E4;
            --gradient-end: #8A94FF;
        }
        
        [data-theme="dark"] {
            --primary-color: #5D76F7;
            --secondary-color: #8A94FF;
            --text-color: #f0f0f0;
            --bg-color: #222;
            --card-bg: #333;
            --shadow-color: rgba(0, 0, 0, 0.3);
            --gradient-start: #3A4DB3;
            --gradient-end: #6A78D8;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: all 0.3s ease;
        }
        
        .hero {
            background: linear-gradient(to right bottom, var(--gradient-start), var(--gradient-end));
            color: white;
            text-align: center;
            padding: 100px 20px;
            position: relative;
        }
        
        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
        }
        
        .hero p {
            font-size: 1.2rem;
            margin-bottom: 40px;
        }
        
        .theme-toggle {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.3s ease;
        }
        
        .theme-toggle:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .card {
            background-color: var(--card-bg);
            border-radius: 10px;
            box-shadow: 0 5px 15px var(--shadow-color);
            padding: 30px;
            margin: 20px 0;
            width: 100%;
            max-width: 600px;
            text-align: center;
        }
        
        .btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        
        .btn:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 50px;
            width: 100%;
        }
        
        .feature-card {
            background-color: var(--card-bg);
            border-radius: 10px;
            box-shadow: 0 5px 15px var(--shadow-color);
            padding: 25px;
            text-align: center;
            transition: 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
        }
        
        .feature-icon {
            background-color: var(--primary-color);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 1.5rem;
        }
        
        footer {
            background-color: var(--card-bg);
            text-align: center;
            padding: 20px;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="hero">
        <h1>StudyCraft</h1>
        <p>Your learning journey begins here</p>
        <div class="theme-toggle" id="themeToggle">
            <i class="fas <?php echo ($theme === 'dark') ? 'fa-sun' : 'fa-moon'; ?>"></i>
        </div>
    </div>
    
    <div class="container">
        <div class="card">
            <h2>Welcome to StudyCraft</h2>
            <p>The ultimate platform for your educational needs. Join our community of learners today!</p>
            <a href="RegLog.php" class="btn">Operation Successful! Click here to login</a>
        </div>
        
        <div class="features">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3>Learn Anywhere</h3>
                <p>Access your courses from any device, anytime, anywhere.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Collaborate</h3>
                <p>Connect with teachers and fellow students in interactive sessions.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Track Progress</h3>
                <p>Monitor your improvement with detailed analytics and feedback.</p>
            </div>
        </div>
    </div>
    
    <footer>
        <p>&copy; <?php echo date('Y'); ?> StudyCraft. All rights reserved.</p>
    </footer>
    
    <script>
        // Toggle theme function
        document.getElementById('themeToggle').addEventListener('click', function() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            
            // Save preference in cookie for 30 days
            const date = new Date();
            date.setTime(date.getTime() + (30 * 24 * 60 * 60 * 1000));
            document.cookie = `theme=${newTheme}; expires=${date.toUTCString()}; path=/`;
            
            // Update icon
            const icon = this.querySelector('i');
            if (newTheme === 'dark') {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            } else {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
            }
        });
    </script>
</body>
</html>