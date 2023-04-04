<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.15/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 text-gray-900">
    <div class="mx-auto container py-10">
    <h1 class="text-3xl mb-5 text-center">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
        <div class="flex justify-center items-center">
            <a href="reset-password.php"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Reset Your Password</a>

            <a href="members.php"
            class="ml-5 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Continue to Members Area</a>

            <a href="logout.php"
            class="ml-5 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Sign Out of Your Account</a>
        </div>
    </div>
</body>
</html>
