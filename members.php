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

// Query to fetch members data for logged in user
$query = "SELECT * FROM members WHERE user = :username";
$stmt = $pdo->prepare($query);
$stmt->execute(['username' => $_SESSION['username']]);
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Members Area</title>
    <!-- Tailwind CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.15/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">

<div class="py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <h1 class="text-2xl font-semibold text-gray-600 mb-3">User: <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Members Area.</h1>

    <div class="mt-4 flex justify-between items-center">
        <hr class="text-gray-400">
        <a class="text-blue-600 hover:text-blue-500" href="logout.php">Sign Out of Your Account</a>
    </div>

    <div class="mt-8 bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between">
                <h2 class="text-lg leading-6 font-medium text-gray-900 mb-3">Member List:</h2>
                <a class="text-sm text-blue-600 hover:text-blue-500" href="add.php">Add New Record</a>
            </div>

            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Name
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Gender
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Email
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Address
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        TIN
                    </th>
                    <th scope="col" colspan="3"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Action
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($members as $member) : ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            <?php echo $member['name']; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo $member['gender']; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo $member['email']; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo $member['address']; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo $member['tin']; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="view.php?id=<?php echo $member['id']; ?>"
                               class="text-blue-600 hover:text-blue-500">View</a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="edit.php?id=<?php echo $member['id']; ?>"
                               class="text-blue-600 hover:text-blue-500">Edit</a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="delete.php?id=<?php echo $member['id']; ?>"
                               class="text-red-600 hover:text-red-500">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
