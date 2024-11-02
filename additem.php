<?php

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$success_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to the database
    $conn = mysqli_connect("localhost", "root", "", "project");
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    // Get form data (no sanitization)
    $itemname = $_POST['itemname'];
    $itemdescription = $_POST['itemdescription'];
    $yearold = (int)$_POST['yearold'];
    $user = $_SESSION['username'];
    $userid = $_SESSION['password'];  // Using password as userid as per requirement
    
    // SQL statement to insert data
    $query = "INSERT INTO items (itemname, itemdescription, yearold, user, userid, ratingcount, ratingavg) 
              VALUES ('$itemname', '$itemdescription', $yearold, '$user', '$userid', 0, 0.0)";
    
    // Execute the query
    if (mysqli_query($conn, $query)) {
        $success_message = "Item added successfully!";
    } else {
        $error_message = "Error: " . mysqli_error($conn);
    }
    
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Useless Item</title>
    <style>
        .form-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        textarea {
            height: 150px;
            resize: vertical;
        }

        .submit-btn {
            background-color: #974de1;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .submit-btn:hover {
            background-color: #45a049;
        }

        .success-message {
            color: green;
            padding: 10px;
            margin-bottom: 20px;
            background-color: #e8f5e9;
            border-radius: 4px;
        }

        .error-message {
            color: red;
            padding: 10px;
            margin-bottom: 20px;
            background-color: #ffebee;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add New Useless Item</h2>
        
        <?php if ($success_message): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="itemname">Item Name (max 50 characters):</label>
                <input type="text" id="itemname" name="itemname" maxlength="50" required>
            </div>

            <div class="form-group">
                <label for="itemdescription">Item Description (max 500 characters):</label>
                <textarea id="itemdescription" name="itemdescription" maxlength="500" required></textarea>
            </div>

            <div class="form-group">
                <label for="yearold">Age of Item (years):</label>
                <input type="number" id="yearold" name="yearold" min="0" max="99999" required>
            </div>

            <button type="submit" class="submit-btn">Add Item</button>
        </form>
    </div>
</body>
</html>
