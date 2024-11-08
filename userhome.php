<?php
// student_home.php
session_start();
require 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['type'] !== 'user') {
    header("Location: login.php");
    exit;
}

// Handle borrowing action if a borrow request is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'])) {
    $book_id = intval($_POST['book_id']);
    $username = $_SESSION['username'];

    // Fetch user ID based on username
    $user_query = "SELECT id FROM users WHERE username = '$username'";
    $user_result = mysqli_query($conn, $user_query);
    if ($user_result) {
        $user_row = mysqli_fetch_assoc($user_result);
        $id = $user_row['id'];

        if ($id) {
            // Insert into borrow table
            $borrow_query = "INSERT INTO borrow (book_id, user_id, borrow_date, due_date, status) 
                             VALUES ($book_id, $id, NOW(), DATE_ADD(NOW(), INTERVAL 14 DAY), 'borrowed')";
            if (mysqli_query($conn, $borrow_query)) {
                // Update book availability
                $update_query = "UPDATE book SET available = 0 WHERE book_id = $book_id";
                mysqli_query($conn, $update_query);
                // Set success message to display after borrowing
                $message = "Successfully borrowed the book.";
            } else {
                $message = "Failed to borrow the book. Please try again.";
            }
        } else {
            $message = "User not found.";
        }
    } else {
        $message = "Error fetching user data.";
    }
}

// Fetch books to display in the dashboard
$query = "SELECT * FROM book ORDER BY publication_year DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - STUDY MATE</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        
        nav {
            background-color: #4CAF50;
            color: white;
            padding: 15px 30px;
            text-align: center;
        }

        nav h2 {
            margin: 0;
            font-size: 24px;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 16px;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .container p {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .book {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .book h3 {
            margin: 0 0 10px;
            font-size: 20px;
            color: #333;
        }

        .book p {
            font-size: 16px;
            color: #555;
            margin: 5px 0;
        }

        .book button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .book button:hover {
            background-color: #45a049;
        }

        .no-books {
            color: #999;
            font-size: 18px;
        }

        .logout-button {
            display: block;
            text-align: center;
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            text-decoration: none;
            width: 200px;
            margin: 30px auto;
            transition: background-color 0.3s;
        }

        .logout-button:hover {
            background-color: #e53935;
        }

        @media (max-width: 768px) {
            .container {
                width: 95%;
            }

            nav h2 {
                font-size: 20px;
            }

            .book h3 {
                font-size: 18px;
            }

            .book p {
                font-size: 14px;
            }

            .book button {
                padding: 8px 16px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <nav>
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
        <a href="borrowed.php">Borrowed Books</a>
    </nav>

    <div class="container">
        <p>Here are the available books:</p>

        <?php
        // Display success message if a book has been successfully borrowed
        if (isset($message)) {
            echo "<script>alert('" . htmlspecialchars($message) . "');</script>";
        }

        // Display the list of books
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='book'>";
                echo "<h3>" . htmlspecialchars($row['title']) . " by " . htmlspecialchars($row['author']) . "</h3>";
                echo "<p>Category: " . htmlspecialchars($row['category']) . "</p>";
                echo "<p>Publication Year: " . htmlspecialchars($row['publication_year']) . "</p>";
                echo "<p>ISBN: " . htmlspecialchars($row['isbn']) . "</p>";
                echo "<p>Status: " . ($row['available'] ? "Available" : "Not Available") . "</p>";
                
                if ($row['available']) {
                    // Borrow button only shows if the book is available
                    echo "<form method='POST' style='display: inline;'>";
                    echo "<input type='hidden' name='book_id' value='" . $row['book_id'] . "'>";
                    echo "<button type='submit'>Borrow</button>";
                    echo "</form>";
                }

                echo "</div>";
            }
        } else {
            echo "<p class='no-books'>No books available at the moment.</p>";
        }
        ?>

        <a href="logout.php" class="logout-button">Logout</a>
    </div>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
