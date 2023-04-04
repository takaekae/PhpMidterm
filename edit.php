<?php
// Connect to database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "PhpMidterm";
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $id = $_POST["id"];
    $name = $_POST["name"];
    $gender = $_POST["gender"];
    $email = $_POST["email"];
    $address = $_POST["address"];
    $tin = $_POST["tin"];

    // Update data record
    $sql = "UPDATE members SET name='$name', gender='$gender', email='$email', address='$address', tin='$tin' WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

// Close database connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Edit Member</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        body {
            font: 14px sans-serif;
            text-align: center;
        }
    </style>
</head>

<body>
    <div>
    <h1 style="margin-bottom: 0px;"><b>Edit Record</b></h1>
    <p>
        <a href="members.php">Member List</a>
    </p>

    <hr"mt-4">
        <?php
        // Include config file
        require_once "config.php";

        // Define variables and initialize with empty values
        $name = $gender = $email = $address = $tin = "";
        $name_err = $gender_err = $email_err = $address_err = $tin_err = "";

        // Processing form data when form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Validate name
            $input_name = trim($_POST["name"]);
            if (empty($input_name)) {
                $name_err = "Please enter a name.";
            } elseif (!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
                $name_err = "Please enter a valid name.";
            } else {
                $name = $input_name;
            }

            // Validate gender
            $input_gender = trim($_POST["gender"]);
            if (empty($input_gender)) {
                $gender_err = "Please select a gender.";
            } else {
                $gender = $input_gender;
            }

            // Validate email
            $input_email = trim($_POST["email"]);
            if (empty($input_email)) {
                $email_err = "Please enter an email.";
            } elseif (!filter_var($input_email, FILTER_VALIDATE_EMAIL)) {
                $email_err = "Please enter a valid email.";
            } else {
                $email = $input_email;
            }

            // Validate address
            $input_address = trim($_POST["address"]);
            if (empty($input_address)) {
                $address_err = "Please enter an address.";
            } else {
                $address = $input_address;
            }

            // Validate tin
            $input_tin = trim($_POST["tin"]);
            if (empty($input_tin)) {
                $tin_err = "Please enter TIN number.";
            } else {
                $tin = $input_tin;
            }

            // Check input errors before inserting in database
            if (empty($name_err) && empty($gender_err) && empty($email_err) && empty($address_err) && empty($tin_err)) {
                // Prepare an update statement
                $sql = "UPDATE members SET name=?, gender=?, email=?, address=?, tin=? WHERE id=?";

                if ($stmt = mysqli_prepare($link, $sql)) {
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "sssssi", $param_name, $param_gender, $param_email, $param_address, $param_tin, $param_id);

                    // Set parameters
                    $param_name = $name;
                    $param_gender = $gender;
                    $param_email = $email;
                    $param_address = $address;
                    $param_tin = $tin;
                    $param_id = $id;

                    // Attempt to execute the prepared statement
                    if (mysqli_stmt_execute($stmt)) {
                        // Records updated successfully. Redirect to landing page
                        header("location: members.php");
                        exit();
                    } else {
                        echo "Something went wrong. Please try again later.";
                    }
                }

                // Close statement
                mysqli_stmt_close($stmt);
            }

            // Close connection
            mysqli_close($link);
        } else {
            // Check existence of id parameter before processing further
            if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
                // Get URL parameter
                $id =  trim($_GET["id"]);

                // Prepare a select statement
                $sql = "SELECT * FROM members WHERE id = ?";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "i", $param_id);

                    // Set parameters
                    $param_id = $id;

                    // Attempt to execute the prepared statement
                    if (mysqli_stmt_execute($stmt)) {
                        $result = mysqli_stmt_get_result($stmt);

                        if (mysqli_num_rows($result) == 1) {
                            /* Fetch result row as an associative array. Since the result set
                        contains only one row, we don't need to use while loop */
                            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                            // Retrieve individual field value
                            $name = $row["name"];
                            $gender = $row["gender"];
                            $email = $row["email"];
                            $address = $row["address"];
                            $tin = $row["tin"];
                        } else {
                            // URL doesn't contain valid id parameter. Redirect to error page
                            header("location: error.php");
                            exit();
                        }
                    } else {
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                }

                // Close statement
                mysqli_stmt_close($stmt);

                // Close connection
                mysqli_close($link);
            } else {
                // URL doesn't contain id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }
        }
        ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <title>Edit Member</title>
            <!-- Add CSS file link -->
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
            <style>
        body {
            font: 14px sans-serif;
            text-align: center;
        }

        .custom-input {
            width: 20%;
        }

        .form-group.row {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-group.row input[type="date"]{
            padding-left: 15px;
        }

        .form-group.row input, .form-group.row textarea{
            padding-left: 16px;
        }

        .form-group.row label {
            margin-bottom: 0;
            flex-basis: 20%;
            text-align: left;
        }

        .form-group.row .col-sm-10 {
            flex-basis: 80%;
        }
            </style>
        </head>

        <body>
            <div>
                <div>
                    <div>
                        <div>
                            <div>
                                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                                    <div>
                                        <label for="name">Name:</label>
                                        <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
                                    </div>
                                    <div>
                                        <label for="gender">Gender:</label>
                                        <select id="gender" name="gender" required>
                                            <option value="">Select Gender</option>
                                            <option value="Male" <?php if ($gender == 'Male') echo 'selected="selected"'; ?>>Male</option>
                                            <option value="Female" <?php if ($gender == 'Female') echo 'selected="selected"'; ?>>Female</option>
                                            <option value="Other" <?php if ($gender == 'Other') echo 'selected="selected"'; ?>>Other</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="email">Email:</label>
                                        <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
                                    </div>
                                    <div>
                                        <label for="address">Address:</label>
                                        <textarea id="address" name="address" rows="3" required><?php echo $address; ?></textarea>
                                    </div>
                                    <div>
                                        <label for="tin">TIN:</label>
                                        <input type="number" id="tin" name="tin" value="<?php echo $tin; ?>" required>
                                    </div>

                                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                    <input type="submit" value="Submit">
                                    <a href="members.php">Cancel</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</body>

</html>
