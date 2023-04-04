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
<h1>User: <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Members Area.</h1>
    <p>
        <a href="logout.php">Sign Out of Your Account</a>
    </p>

    <hr>


    <div>
        <div>
            <h2>Member List:</h2>
        </div>
        <div>
            <p><a href="add.php">Add New Record</a></p>
        </div>
    </div>

    <table>
      <thead>
        <tr>
          <th scope="col">Name</th>
          <th scope="col">Gender</th>
          <th scope="col">Email</th>
          <th scope="col">Address</th>
          <th scope="col">TIN</th>
          <th scope="col" colspan="3">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($members as $member) : ?>
        <tr>
          <td><?php echo $member['name']; ?></td>
          <td><?php echo $member['gender']; ?></td>
          <td><?php echo $member['email']; ?></td>
          <td><?php echo $member['address']; ?></td>
          <td><?php echo $member['tin']; ?></td>
          <td><a href="view.php?id=<?php echo $member['id']; ?>">View</a></td>
            <td><a href="edit.php?id=<?php echo $member['id']; ?>">Edit</a></td>
            <td><a href="delete.php?id=<?php echo $member['id']; ?>">Delete</a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

</body>
</html>
