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
    <!-- Add Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.1/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <h1 class="text-3xl font-bold my-3">View Record</h1>
    <p>
        <a href="members.php" class="inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Member List</a>
    </p>

    <hr class="my-4">

    <?php if ($id && $member) { ?>
        <table class="table-auto">
            <tr>
                <td class="font-bold">Name</td>
                <td><?php echo $member['name']; ?></td>
            </tr>
            <tr>
                <td class="font-bold">Gender</td>
                <td><?php echo $member['gender']; ?></td>
            </tr>
            <tr>
                <td class="font-bold">Email</td>
                <td><?php echo $member['email']; ?></td>
            </tr>
            <tr>
                <td class="font-bold">Address</td>
                <td><?php echo $member['address']; ?></td>
            </tr>
            <tr>
                <td class="font-bold">TIN</td>
                <td><?php echo $member['tin']; ?></td>
            </tr>
        </table>
    <?php } else { ?>
        <p>No record found.</p>
    <?php } ?>
    <a href="members.php" class="inline-block bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-700">Back</a>
</body>

</html>
