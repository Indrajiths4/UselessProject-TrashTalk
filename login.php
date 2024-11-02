<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "", "project");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize action
$error = '';

// Handle Sign Up
if(isset($_POST['signup'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if($password != $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        $check_query = "SELECT * FROM users WHERE username = '$username'";
        $check_result = mysqli_query($conn, $check_query);

        if(mysqli_num_rows($check_result) > 0) {
            $error = "Username already exists!";
        } else {
            $insert_query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
            if(mysqli_query($conn, $insert_query)) {
                $_SESSION['username'] = $username;
                $_SESSION['password'] = $password;
                $_SESSION['action'] = "home";
                header('location: home.php');
                exit();
            } else {
                $error = "Error occurred while registering!";
            }
        }
    }
}

// Handle Login
if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $login_query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $login_query);

    if(mysqli_num_rows($result) == 1) {
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        $_SESSION['action'] = "home";
        header('location: home.php');
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}

// Only show login form if not logged in
if (!isset($_SESSION['username'])):
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Sign Up</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }

        .container {
            display: flex;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 900px;
            min-height: 500px;
        }

        .split-section {
            flex: 1;
            padding: 40px;
        }

        .split-section:first-child {
            border-right: 1px solid #eee;
        }

        .section-title {
            font-size: 24px;
            color: #333;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 20px;
        }

        .submit-btn:hover {
            background: #5a6fd6;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                max-width: 400px;
            }

            .split-section:first-child {
                border-right: none;
                border-bottom: 1px solid #eee;
            }

            .split-section {
                padding: 30px;
            }
        }

        .error-message {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #ff6b6b;
            color: white;
            padding: 12px 24px;
            border-radius: 6px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
    </style>
</head>
<body>
    <?php if($error): ?>
    <div class="error-message">
        <?php echo $error; ?>
    </div>
    <?php endif; ?>

    <div class="container">
        <!-- Login Section -->
        <div class="split-section">
            <h2 class="section-title">Login</h2>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="login-username">Username</label>
                    <input type="text" id="login-username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="login-password">Password</label>
                    <input type="password" id="login-password" name="password" required>
                </div>
                <button type="submit" class="submit-btn" name="login">Login</button>
            </form>
        </div>

        <!-- Sign Up Section -->
        <div class="split-section">
            <h2 class="section-title">Sign Up</h2>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="signup-username">Username</label>
                    <input type="text" id="signup-username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="signup-password">Password</label>
                    <input type="password" id="signup-password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="signup-confirm">Confirm Password</label>
                    <input type="password" id="signup-confirm" name="confirm_password" required>
                </div>
                <button type="submit" class="submit-btn" name="signup">Sign Up</button>
            </form>
        </div>
    </div>
</body>
</html>
<?php endif; ?>