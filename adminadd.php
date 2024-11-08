<?php
// adminadd.php
session_start();
require 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Add new book
if (isset($_POST['add'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $publication_year = intval($_POST['publication_year']);
    $isbn = mysqli_real_escape_string($conn, $_POST['isbn']);
    $available = isset($_POST['available']) ? 1 : 0;

    $sql = "INSERT INTO book (title, author, category, publication_year, isbn, available) VALUES ('$title', '$author', '$category', '$publication_year', '$isbn', '$available')";
    mysqli_query($conn, $sql);
    header("Location: adminadd.php");
    exit;
}

// Delete book
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM book WHERE book_id = $id";
    mysqli_query($conn, $sql);
    header("Location: adminadd.php");
    exit;
}

// Edit book
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $publication_year = intval($_POST['publication_year']);
    $isbn = mysqli_real_escape_string($conn, $_POST['isbn']);
    $available = isset($_POST['available']) ? 1 : 0;

    $sql = "UPDATE book SET title='$title', author='$author', category='$category', publication_year='$publication_year', isbn='$isbn', available='$available' WHERE book_id=$id";
    mysqli_query($conn, $sql);
    header("Location: adminadd.php");
    exit;
}

// Fetch book for editing
$edit_book = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $result = mysqli_query($conn, "SELECT * FROM book WHERE book_id = $id");
    $edit_book = mysqli_fetch_assoc($result);
}

// Fetch book for display based on selection
$selected_book = null;
if (isset($_GET['select'])) {
    $id = intval($_GET['select']);
    $result = mysqli_query($conn, "SELECT * FROM book WHERE book_id = $id");
    $selected_book = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Library Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .book-form {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .book-form h3 {
            margin-bottom: 20px;
        }

        .book-form label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        .book-form input, .book-form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .book-form button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 4px;
        }

        .book-form button:hover {
            background-color: #45a049;
        }

        .cancel-button {
            display: inline-block;
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 10px;
        }

        .cancel-button:hover {
            background-color: #e53935;
        }

        .book {
            background-color: #fafafa;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
        }

        .book h4 {
            margin: 0;
            color: #333;
        }

        .book p {
            margin: 5px 0;
            color: #666;
        }

        .availability {
            font-weight: bold;
        }

        .edit-link, .delete-link, .select-link {
            text-decoration: none;
            color: #4CAF50;
        }

        .edit-link:hover, .delete-link:hover, .select-link:hover {
            color: #45a049;
        }

        .logout-button {
            display: block;
            background-color: #333;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 30px;
        }

        .logout-button:hover {
            background-color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Dashboard</h2>

        <!-- Add/Edit Book Form -->
        <form method="POST" class="book-form">
            <h3><?php echo $edit_book ? 'Edit Book' : 'Add New Book'; ?></h3>
            <input type="hidden" name="id" value="<?php echo $edit_book['book_id'] ?? ''; ?>">

            <label for="title">Title:</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($edit_book['title'] ?? ''); ?>" required> <br>

            <label for="author">Author:</label>
            <input type="text" name="author" value="<?php echo htmlspecialchars($edit_book['author'] ?? ''); ?>" required> <br>

            <label for="category">Category:</label>
            <input type="text" name="category" value="<?php echo htmlspecialchars($edit_book['category'] ?? ''); ?>"> <br>

            <label for="publication_year">Publication Year:</label>
            <input type="number" name="publication_year" value="<?php echo htmlspecialchars($edit_book['publication_year'] ?? ''); ?>"> <br>

            <label for="isbn">ISBN:</label>
            <input type="text" name="isbn" value="<?php echo htmlspecialchars($edit_book['isbn'] ?? ''); ?>" required> 

            <label for="available">Available:</label>
            <input type="checkbox" name="available" <?php echo isset($edit_book['available']) && $edit_book['available'] ? 'checked' : ''; ?>> 

            <button type="submit" name="<?php echo $edit_book ? 'update' : 'add'; ?>" class="form-button"> <br>
                <?php echo $edit_book ? 'Update Book' : 'Add Book'; ?>
            </button>
            <?php if ($edit_book): ?>
                <a href="adminadd.php" class="cancel-button">Cancel Edit</a>
            <?php endif; ?>
        </form>

        <h3>Existing Books</h3>

        <?php
        $result = mysqli_query($conn, "SELECT * FROM book ORDER BY title ASC");
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='book'>";
            echo "<h4>" . htmlspecialchars($row['title']) . "</h4>";
            echo "<p>Author: " . htmlspecialchars($row['author']) . "</p>";
            echo "<p>Category: " . htmlspecialchars($row['category']) . "</p>";
            echo "<p>Publication Year: " . htmlspecialchars($row['publication_year']) . "</p>";
            echo "<p>ISBN: " . htmlspecialchars($row['isbn']) . "</p>";
            echo "<p class='availability'>Available: " . ($row['available'] ? 'Yes' : 'No') . "</p>";
            echo "<a href='adminadd.php?edit=" . $row['book_id'] . "' class='edit-link'>Edit</a> | ";
            echo "<a href='adminadd.php?delete=" . $row['book_id'] . "' class='delete-link' onclick='return confirm(\"Are you sure?\")'>Delete</a>";
            echo "</div>";
        }
        ?>

        <a href="logout.php" class="logout-button">Logout</a>
    </div>
</body>
</html>
