<?php
require_once "connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $title = $_POST['title'];
    $author = $_POST['author'];
    $description = $_POST['description'];
    $publisher = $_POST['publisher'];
    $application_date = $_POST['application_date'];
    $age = $_POST['age'];
    $book_types = isset($_POST['book_types']) ? implode(',', $_POST['book_types']) : '';

    $stmt = $pdo->prepare("INSERT INTO books (title, author, description, publisher, application_date, age, book_type) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $author, $description, $publisher, $application_date, $age, $book_types]);

    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Management System - Data Entry Form</title>
</head>
<body>
    <h1>Book Management System - Data Entry Form</h1>
    <form action="info_entry.php" method="POST">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" required><br><br>
        
        <label for="author">Author:</label>
        <input type="text" name="author" id="author" required><br><br>
        
        <label for="description">Description:</label><br>
        <textarea name="description" id="description" rows="2" required></textarea><br><br>
        
        <label for="publisher">Publisher:</label>
        <input type="text" name="publisher" id="publisher" required><br><br>
        
        <label for="application_date">Application Date:</label>
        <input type="date" name="application_date" id="application_date" required><br><br>
        
        <label for="age">Age:</label>
        <select name="age" id="age">
            <option value="6-18">6-18</option>
            <option value="19-35">19-35</option>
            <option value="36-60">36-60</option>
        </select><br><br>
        
        <label for="book_types">Book Types:</label><br>
        <input type="checkbox" name="book_types[]" value="Scientific"> Scientific<br>
        <input type="checkbox" name="book_types[]" value="Drama"> Drama<br>
        <input type="checkbox" name="book_types[]" value="Novel"> Novel<br><br>
        
        <input type="submit" value="Submit">
    </form>
</body>
</html>
