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

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Record</title>
</head>
<style>
    body {
        font: 14px sans-serif;
        text-align: center;
    }
</style>

<body>
    <h1 style="margin-bottom: 0px;"><b>View Record</b></h1>
    <p>
        <a href="members.php">Member List</a>
    </p>

    <hr>

    <?php if ($id && $member) { ?>
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
                <td><strong>TIN</strong></td>
                <td><?php echo $member['tin']; ?></td>
            </tr>
        </table>
    <?php } else { ?>
        <p>No record found.</p>
    <?php } ?>
    <a href="members.php">Back</a>
</body>
</html>
