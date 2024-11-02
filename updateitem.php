<?php


if (!isset($_SESSION['username'])) {
    header("Location: home.php");
    exit();
}

$success_message = '';
$error_message = '';

// Handle update request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_item'])) {
    $conn = mysqli_connect("localhost", "root", "", "project");
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    $itemid = (int)$_POST['itemid'];
    $itemname = $_POST['itemname'];
    $itemdescription = $_POST['itemdescription'];
    $yearold = (int)$_POST['yearold'];
    $username = $_SESSION['username'];
    
    // Only allow updating if the item belongs to the user
    $query = "UPDATE items 
              SET itemname = '$itemname', 
                  itemdescription = '$itemdescription', 
                  yearold = $yearold 
              WHERE itemid = $itemid AND user = '$username'";
    
    if (mysqli_query($conn, $query)) {
        $success_message = "Item updated successfully!";
    } else {
        $error_message = "Error updating item: " . mysqli_error($conn);
    }
    
    mysqli_close($conn);
}

// Fetch user's items
$conn = mysqli_connect("localhost", "root", "", "project");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$username = $_SESSION['username'];
$query = "SELECT * FROM items WHERE user = '$username' ORDER BY itemid DESC";
$result = mysqli_query($conn, $query);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Items</title>
    <style>
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }

        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .success-message {
            color: green;
            background-color: #e8f5e9;
        }

        .error-message {
            color: red;
            background-color: #ffebee;
        }

        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .item-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-group textarea {
            height: 100px;
            resize: vertical;
        }

        .update-btn {
            background-color: #974de1;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        .update-btn:hover {
            background-color: #7e41bd;
        }

        .item-meta {
            color: #888;
            font-size: 0.9em;
            margin-bottom: 15px;
        }

        .no-items {
            text-align: center;
            padding: 40px;
            color: #666;
            grid-column: 1 / -1;
        }

        .ratings-info {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Update Items</h2>
        
        <?php if ($success_message): ?>
            <div class="message success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="message error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="items-grid">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($item = mysqli_fetch_assoc($result)): ?>
                    <div class="item-card">
                        <form method="POST" action="">
                            <input type="hidden" name="itemid" value="<?php echo $item['itemid']; ?>">
                            
                            <div class="form-group">
                                <label for="itemname_<?php echo $item['itemid']; ?>">Item Name:</label>
                                <input 
                                    type="text" 
                                    id="itemname_<?php echo $item['itemid']; ?>" 
                                    name="itemname" 
                                    value="<?php echo htmlspecialchars($item['itemname']); ?>"
                                    maxlength="50" 
                                    required
                                >
                            </div>

                            <div class="form-group">
                                <label for="itemdescription_<?php echo $item['itemid']; ?>">Description:</label>
                                <textarea 
                                    id="itemdescription_<?php echo $item['itemid']; ?>" 
                                    name="itemdescription" 
                                    maxlength="500" 
                                    required
                                ><?php echo htmlspecialchars($item['itemdescription']); ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="yearold_<?php echo $item['itemid']; ?>">Age (years):</label>
                                <input 
                                    type="number" 
                                    id="yearold_<?php echo $item['itemid']; ?>" 
                                    name="yearold" 
                                    value="<?php echo htmlspecialchars($item['yearold']); ?>"
                                    min="0" 
                                    max="99999" 
                                    required
                                >
                            </div>

                            <div class="ratings-info">
                                <strong>Current Ratings:</strong><br>
                                Average Rating: <?php echo number_format($item['ratingavg'], 1); ?>/5<br>
                                Total Ratings: <?php echo $item['ratingcount']; ?>
                            </div>

                            <button type="submit" name="update_item" class="update-btn">Update Item</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-items">
                    <h3>No items found</h3>
                    <p>You haven't added any items yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>