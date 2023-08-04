<?php
session_start();

if (!isset($_SESSION["member_id"])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "username", "password", "library_management");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$memberId = $_SESSION["member_id"];

// Get rentals for the member
$rentals = [];
$sql = "SELECT books.title, books.author, rentals.return_date FROM rentals
        JOIN books ON rentals.book_id = books.id
        WHERE rentals.member_id = $memberId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rentals[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Library Management System - Dashboard</title>
</head>
<body>
    <h1>Welcome to the Library</h1>
    <h2>Your Rentals</h2>
    <ul>
        <?php foreach ($rentals as $rental) { ?>
            <li><?php echo $rental["title"]; ?> by <?php echo $rental["author"]; ?> - Return by <?php echo $rental["return_date"]; ?></li>
        <?php } ?>
    </ul>
    <a href="books.php">Browse Books</a>
</body>
</html>
