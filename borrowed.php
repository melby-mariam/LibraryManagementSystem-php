<?php
// borrowed.php
session_start();
require 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['type'] !== 'user') {
    header("Location: login.php");
    exit;
}

// Fetch user ID based on the logged-in username
$username = $_SESSION['username'];
$user_query = "SELECT id FROM users WHERE username = '$username'";
$user_result = mysqli_query($conn, $user_query);
if ($user_result) {
    $user_row = mysqli_fetch_assoc($user_result);
    $user_id = $user_row['id'];
} else {
    echo "Error fetching user data.";
    exit;
}

// Fetch borrowed books based on user_id
$query = "SELECT borrow.book_id, book.title, book.author, borrow.borrow_date, borrow.due_date 
          FROM borrow 
          JOIN book ON borrow.book_id = book.book_id 
          WHERE borrow.user_id = $user_id AND borrow.status = 'borrowed'";
$result = mysqli_query($conn, $query);

// Handle the return book action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['borrow_id'])) {
    $borrow_id = intval($_POST['borrow_id']);

    // Check if the book is overdue and calculate fine if needed
    $check_due_query = "SELECT due_date FROM borrow WHERE borrow_id = $borrow_id";
    $check_due_result = mysqli_query($conn, $check_due_query);
    if ($check_due_result) {
        $due_row = mysqli_fetch_assoc($check_due_result);
        $due_date = strtotime($due_row['due_date']);
        $current_date = strtotime(date('Y-m-d'));
        $fine = 0;

        if ($current_date > $due_date) {
            // Calculate fine for overdue books (e.g., $1 per day)
            $days_overdue = ceil(($current_date - $due_date) / (60 * 60 * 24));
            $fine = $days_overdue * 1; // 1 unit of currency per day of overdue
            echo "<script>alert('You are returning the book after " . $days_overdue . " days. A fine of $" . $fine . " will be charged.');</script>";
        }

        // Update borrow table to mark the book as returned and update status
        $return_query = "UPDATE borrow SET status = 'returned', return_date = NOW() WHERE borrow_id = '$borrow_id'";
        if (mysqli_query($conn, $return_query)) {
            // Update book availability
            $book_id_query = "SELECT book_id FROM borrow WHERE borrow_id = '$borrow_id'";
            $book_id_result = mysqli_query($conn, $book_id_query);
            if ($book_id_result) {
                $book_row = mysqli_fetch_assoc($book_id_result);
                $book_id = $book_row['book_id'];
                $update_book_query = "UPDATE book SET available = 1 WHERE book_id = '$book_id'";
                mysqli_query($conn, $update_book_query);
            }
            echo "<script>alert('Book returned successfully.');</script>";
        } else {
            echo "<script>alert('Error returning the book. Please try again.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrowed Books - STUDY MATE</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        nav {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            text-align: center;
        }

        nav h2 {
            margin: 0;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h3 {
            text-align: center;
            color: #333;
        }

        .borrowed-book {
            background-color: #f9f9f9;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .borrowed-book h4 {
            margin: 0;
            color: #4CAF50;
        }

        .borrowed-book p {
            color: #555;
        }

        .borrowed-book form {
            margin-top: 10px;
        }

        .borrowed-book button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .borrowed-book button:hover {
            background-color: #45a049;
        }

        .logout-button {
            display: inline-block;
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            margin-top: 20px;
        }

        .logout-button:hover {
            background-color: #e53935;
        }
    </style>
</head>
<body>

    <nav>
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
        <a href="userhome.php" style="color: white;">Back to Home</a>
    </nav>

    <div class="container">
        <h3>Your Borrowed Books:</h3>

        <?php
        // Display borrowed books
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $borrow_id = $row['book_id'];
                $title = htmlspecialchars($row['title']);
                $author = htmlspecialchars($row['author']);
                $borrow_date = date('Y-m-d', strtotime($row['borrow_date']));
                $due_date = date('Y-m-d', strtotime($row['due_date']));

                echo "<div class='borrowed-book'>";
                echo "<h4>Title: $title</h4>";
                echo "<p>Author: $author</p>";
                echo "<p>Borrowed on: $borrow_date</p>";
                echo "<p>Due Date: $due_date</p>";

                echo "<form method='POST'>";
                echo "<input type='hidden' name='borrow_id' value='$borrow_id'>";
                echo "<button type='submit'>Return Book</button>";
                echo "</form>";

                echo "</div>";
            }
        } else {
            echo "<p>You have not borrowed any books yet.</p>";
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
