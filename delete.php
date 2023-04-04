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

    <!-- Importing Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.7/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">

    <h1 class="text-2xl font-bold pt-6 pb-2">Delete Record</h1>

    <div class="flex justify-center mb-4">
        <a href="members.php" class="text-blue-600 underline">Member List</a>
    </div>

    <hr class="my-4 border-gray-500">

    <table class="mx-auto max-w-3xl bg-white shadow-md rounded-lg overflow-hidden">
        <tbody class="text-gray-800">
            <tr class="border-b border-gray-400 py-4">
                <td class="text-left px-4 py-2 font-semibold">Name</td>
                <td class="prise-td text-left px-4 py-2"><?php echo $member['name']; ?></td>
            </tr>
            <tr class="border-b border-gray-400 py-4">
                <td class="text-left px-4 py-2 font-semibold">Gender</td>
                <td class="prise-td text-left px-4 py-2"><?php echo $member['gender']; ?></td>
            </tr>
            <tr class="border-b border-gray-400 py-4">
                <td class="text-left px-4 py-2 font-semibold">Email</td>
                <td class="prise-td text-left px-4 py-2"><?php echo $member['email']; ?></td>
            </tr>
            <tr class="border-b border-gray-400 py-4">
                <td class="text-left px-4 py-2 font-semibold">Address</td>
                <td class="prise-td text-left px-4 py-2"><?php echo $member['address']; ?></td>
            </tr>
            <tr class="border-b border-gray-400 py-4">
                <td class="text-left px-4 py-2 font-semibold">Birth Date</td>
                <td class="prise-td text-left px-4 py-2"><?php echo $member['tin']; ?></td>
            </tr>
        </tbody>
    </table>

    <div class="flex justify-center mt-8">
        <h1 class="text-2xl font-bold">Are you sure you want to delete this record?</h1>
    </div>

    <form class="flex justify-center mt-4 space-x-4" method="POST">
        <button type="submit" name="confirm" value="Yes" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Yes</button>
        <button type="submit" name="confirm" value="No" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">No</button>
    </form>
</body>
</html>
