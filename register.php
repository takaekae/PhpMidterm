<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = $confirm_password = $lastname = $firstname = $gender = $birthdate = "";
$username_err = $password_err = $confirm_password_err = $lastname_err = $firstname_err = $gender_err = $birthdate_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    // Validate lastname
    if(empty(trim($_POST["lastname"]))){
        $lastname_err = "Please enter a lastname.";
    } elseif(!preg_match('/^[a-zA-Z]+$/', trim($_POST["lastname"]))){
        $lastname_err = "Lastname can only contain letters.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE lastname = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_lastname);

            // Set parameters
            $param_lastname = trim($_POST["lastname"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $lastname_err = "This lastname is already taken.";
                } else{
                    $lastname = trim($_POST["lastname"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate firstname
    if(empty(trim($_POST["firstname"]))){
        $firstname_err = "Please enter a firstname.";
    } elseif(!preg_match('/^[a-zA-Z]+$/', trim($_POST["firstname"]))){
        $firstname_err = "Firstname can only contain letters.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE firstname = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_firstname);

            // Set parameters
            $param_firstname = trim($_POST["firstname"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $firstname_err = "This firstname is already taken.";
                } else{
                    $firstname = trim($_POST["firstname"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate gender
    // Prepare a select statement
    $sql = "SELECT id FROM users WHERE gender = ?";

    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_gender);

        // Set parameters
        $param_gender = trim($_POST["gender"]);

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            /* store result */
            mysqli_stmt_store_result($stmt);
            $gender = trim($_POST["gender"]);
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Validate birthdate
    if (empty(trim($_POST["birthdate"]))) {
        $birthdate_err = "Please enter your birthdate.";
    } else {
        // Convert birthdate string to a DateTime object
        $birthdate_obj = DateTime::createFromFormat('Y-m-d', $_POST['birthdate']);

        // Check if birthdate is a valid date and is not in the future
        if (!$birthdate_obj || $birthdate_obj > new DateTime()) {
            $birthdate_err = "Please enter a valid birthdate.";
        } else {
            $birthdate = $birthdate_obj->format('Y-m-d');
        }
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($lastname_err) && empty($firstname_err) && empty($gender_err) && empty($birthdate_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password, lastname, firstname, gender, birthdate) VALUES (?, ?, ?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssss", $param_username, $param_password, $param_lastname, $param_firstname, $param_gender, $param_birthdate);

            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_lastname = $lastname;
            $param_firstname = $firstname;
            $param_gender = $gender;
            $param_birthdate = $birthdate;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <!-- Add Tailwind CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.0.3/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto my-4 p-6 bg-white rounded-md shadow-lg">
        <h2 class="text-2xl font-bold mb-4">Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="my-4">
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="username">Username</label>
                <input value="<?php echo $username; ?>" type="text" name="username" id="username"
                       class="<?php echo (!empty($username_err)) ? 'border-red-500' : 'border-gray-400'; ?> p-2 rounded-md border w-full"
                       placeholder="Enter your username">
                <span class="text-red-500 text-sm"><?php echo $username_err; ?></span>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="password">Password</label>
                <input value="<?php echo $password; ?>" type="password" name="password" id="password"
                       class="<?php echo (!empty($password_err)) ? 'border-red-500' : 'border-gray-400'; ?> p-2 rounded-md border w-full"
                       placeholder="Enter your password">
                <span class="text-red-500 text-sm"><?php echo $password_err; ?></span>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="confirm_password">Confirm Password</label>
                <input value="<?php echo $confirm_password; ?>" type="password" name="confirm_password" id="confirm_password"
                       class="<?php echo (!empty($confirm_password_err)) ? 'border-red-500' : 'border-gray-400'; ?> p-2 rounded-md border w-full"
                       placeholder="Confirm your password">
                <span class="text-red-500 text-sm"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="lastname">Last Name</label>
                <input value="<?php echo $lastname; ?>" type="text" name="lastname" id="lastname"
                       class="<?php echo (!empty($lastname_err)) ? 'border-red-500' : 'border-gray-400'; ?> p-2 rounded-md border w-full"
                       placeholder="Enter your last name">
                <span class="text-red-500 text-sm"><?php echo $lastname_err; ?></span>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="firstname">First Name</label>
                <input value="<?php echo $firstname; ?>" type="text" name="firstname" id="firstname"
                       class="<?php echo (!empty($firstname_err)) ? 'border-red-500' : 'border-gray-400'; ?> p-2 rounded-md border w-full"
                       placeholder="Enter your first name">
                <span class="text-red-500 text-sm"><?php echo $firstname_err; ?></span>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="gender">Gender</label>
                <select name="gender" id="gender"
                       class="<?php echo (!empty($gender_err)) ? 'border-red-500' : 'border-gray-400'; ?> p-2 rounded-md border w-full">
                    <option value="Male" <?php if($gender == "Male") echo "selected"; ?>>Male</option>
                    <option value="Female" <?php if($gender == "Female") echo "selected"; ?>>Female</option>
                </select>
                <span class="text-red-500 text-sm"><?php echo $gender_err; ?></span>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="birthdate">Birthdate</label>
                <input value="<?php echo $birthdate; ?>" type="date" name="birthdate" id="birthdate"
                       class="<?php echo (!empty($birthdate_err)) ? 'border-red-500' : 'border-gray-400'; ?> p-2 rounded-md border w-full">
                <span class="text-red-500 text-sm"><?php echo $birthdate_err; ?></span>
            </div>
            <div class="flex justify-between items-center">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Submit</button>
                <button type="reset" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Reset</button>
            </div>
            <p class="mt-4">Already have an account? <a href="login.php" class="text-blue-500">Login here</a>.</p>
        </form>
    </div>
</body>
</html>

