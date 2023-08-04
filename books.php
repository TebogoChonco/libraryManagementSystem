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

// Get available books
$books = [];
$sql = "SELECT * FROM books WHERE status = 'available'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Library Management System - Browse Books</title>
</head>
<body>
    <h1>Browse Books</h1>
    <ul>
        <?php foreach ($books as $book) { ?>
            <li><?php echo $book["title"]; ?> by <?php echo $book["author"]; ?> <a href="rent.php?book_id=<?php echo $book["id"]; ?>">Rent</a></li>
        <?php } ?>
    </ul>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
