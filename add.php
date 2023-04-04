<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit();
}

$dsn = 'mysql:host=localhost;dbname=PhpMidterm';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

// Insert new member record
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $gender = $_POST["gender"];
    $email = $_POST["email"];
    $address = $_POST["address"];
    $tin = $_POST["tin"];

    $query = "INSERT INTO members (name, gender, email, address, tin, user) VALUES (:name, :gender, :email, :address, :tin, :user)";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['name' => $name, 'gender' => $gender, 'email' => $email, 'address' => $address, 'tin' => $tin, 'user' => $_SESSION['username']]);

    header("location: members.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Record</title>
    <style>
        body {
            font: 14px sans-serif;
            text-align: center;
        }
        .custom-margin{
            margin-left: -50px;
        }
    </style>
</head>

<body>
    <h1>User: <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Add Record:</h1>
    <p>
        <a href="members.php">Back to Members Area</a>
    </p>

    <hr>

    <form method="post">
        <div>
            <label for="name">Name:</label>
            <input type="text" name="name" required>
        </div>
        <div>
            <label for="gender">Gender:</label>
            <select name="gender" required>
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" required>
        </div>
        <div>
            <label for="address">Address:</label>
            <textarea name="address" required></textarea>
        </div>
        <div>
            <label for="tin">TIN:</label>
            <input type="text" name="tin" required>
        </div>
        <div>
            <input type="submit" value="Submit">
        </div>
    </form>

</body>
</html>
