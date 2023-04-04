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
    <!-- Tailwind CSS CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.15/dist/tailwind.min.css">
    <style>
        body {
            font: 14px sans-serif;
            text-align: center;
            background-color: #f7fafc;
        }
        .custom-margin{
            margin-left: -50px;
        }
    </style>
</head>
<body class="bg-gray-100">
    <h1 class="text-3xl font-bold mt-10 mb-6">User: <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Add Record:</h1>
    <p class="mb-4">
        <a href="members.php" class="text-blue-600 hover:underline">Back to Members Area</a>
    </p>

    <hr class="my-8">

    <form method="post" class="mx-auto w-2/3 p-6 bg-white shadow-sm rounded-lg">
        <div class="mb-4">
            <label for="name" class="block font-medium text-gray-700">Name:</label>
            <input type="text" name="name" required class="border border-gray-400 p-2 rounded-md w-full focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <div class="mb-4">
            <label for="gender" class="block font-medium text-gray-700">Gender:</label>
            <select name="gender" required class="border border-gray-400 p-2 rounded-md w-full focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="email" class="block font-medium text-gray-700">Email:</label>
            <input type="email" name="email" required class="border border-gray-400 p-2 rounded-md w-full focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <div class="mb-4">
            <label for="address" class="block font-medium text-gray-700">Address:</label>
            <textarea name="address" required class="border border-gray-400 p-2 rounded-md w-full h-24 resize-none focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
        </div>
        <div class="mb-4">
            <label for="tin" class="block font-medium text-gray-700">TIN:</label>
            <input type="text" name="tin" required class="border border-gray-400 p-2 rounded-md w-full focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <div class="mb-4">
            <input type="submit" value="Submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md">
            <input type="reset" value="Reset" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 ml-4 rounded-md">
        </div>
    </form>

</body>
</html>
