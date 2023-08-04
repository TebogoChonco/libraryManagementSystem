<?php
session_start();

if (!isset($_SESSION["member_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["book_id"])) {
    $bookId = $_GET["book_id"];

    // Database connection
    $conn = new mysqli("localhost", "username", "password", "library_management");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $memberId = $_SESSION["member_id"];

    // Check if member has reached the rental limit
    $sql = "SELECT COUNT(*) as rental_count FROM rentals WHERE member_id = $memberId";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $rentalCount = $row["rental_count"];

    if ($rentalCount >= 5) {
        echo "You have reached the rental limit of 5 books.";
    } else {
        // Check if the book is available
        $sql = "SELECT * FROM books WHERE id = $bookId AND status = 'available'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $rentalDate = date("Y-m-d");
            $returnDate = date("Y-m-d", strtotime("+14 days")); // 2 weeks rental period

            // Update book status to 'rented'
            $sql = "UPDATE books SET status = 'rented' WHERE id = $bookId";
            $conn->query($sql);

            // Create rental record
            $sql = "INSERT INTO rentals (member_id, book_id, rental_date, return_date) VALUES ($memberId, $bookId, '$rentalDate', '$returnDate')";
            if ($conn->query($sql) === TRUE) {
                echo "Book rented successfully!";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Book is not available for rent.";
        }
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Library Management System - Rent Book</title>
</head>
<body>
    <h1>Rent Book</h1>
    <a href="books.php">Back to Browse Books</a>
</body>
</html>
