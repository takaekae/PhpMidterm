<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$dsn = 'mysql:host=localhost;dbname=PhpMidterm';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    $query = "SELECT * FROM members WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);
    $member = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["confirm"] == "Yes") {
        $query = "DELETE FROM members WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id]);
        header("location: members.php");
        exit;
    } else {
        header("location: members.php");
        exit;
    }
}

// Close database connection
$pdo = null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Member</title>

</head>
<style>
    body {
        font: 14px sans-serif;
        text-align: center;
    }
</style>

<body>
<h1 style="margin-bottom: 0px;"><b>Delete Record</b></h1>
    <p>
        <a href="members.php">Member List</a>
    </p>

    <hr>

    <table>
            <tr>
                <td><strong>Name</strong></td>
                <td><?php echo $member['name']; ?></td>
            </tr>
            <tr>
                <td><strong>Gender</strong></td>
                <td><?php echo $member['gender']; ?></td>
            </tr>
            <tr>
                <td><strong>Email</strong></td>
                <td><?php echo $member['email']; ?></td>
            </tr>
            <tr>
                <td><strong>Address</strong></td>
                <td><?php echo $member['address']; ?></td>
            </tr>
            <tr>
                <td><strong>Birth Date</strong></td>
                <td><?php echo $member['tin']; ?></td>
            </tr>
        </table>
        <br>
        <h1>Are you sure you want to delete this record?</h1>
    <form method="POST">
        <input type="submit" name="confirm" value="Yes">
        <input type="submit" name="confirm" value="No">
    </form>
</body>

</html>
