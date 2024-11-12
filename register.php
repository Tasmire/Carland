<?php
    session_start();

    if (!empty($_SESSION['loggedin']) && $_SESSION['loggedin']) {
    header('location: index.php');
    exit;
    }

    $title = "Register"; //The page title

    require_once('./includes/layouts/header.php'); //Gets the header
    require_once('./includes/db.php'); //Connect to the database

    //Create empty variables
    $uname = $email = $password = $pass_confirm = '';
    $uname_err = $email_err = $password_err = $pass_confirm_err = '';
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/'; //Strong password validation (8+ digits, one lowercase, one uppercase, one digit)
    $hashed_password = '';


    //Process registration data when form is submitted
    //Fetches 'email', 'password', and 'pass_confirm'
    if (isset($_POST['username'])) {

    if (empty(trim($_POST["username"]))) {
        $uname_err = "Please enter a username.";
    } else {
        //Prepare a SELECT statement to check for existing users
        $sql = "SELECT id FROM users WHERE username = :username";

        if ($stmt = $pdo->prepare($sql)) {
        //Bind variables to the statement as parameters
        $stmt->bindParam(":username", $param_uname, PDO::PARAM_STR);

        //Fill parameters
        $param_uname = trim($_POST["username"]);

        //Attempt our prepared statement
        if ($stmt->execute()) {
            if ($stmt->rowCount() == 1) {
            $uname_err = "A user with this username already exists.";
            } else {
            $uname = trim($_POST["username"]);
            }
        } else {
            echo "Something went wrong. Please try again later.";
        }
        unset($stmt);
        }
    }

    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } else {
        //Prepare a SELECT statement to check for existing users
        $sql = "SELECT id FROM users WHERE email = :email";

        if ($stmt = $pdo->prepare($sql)) {
        //Bind variables to the statement as parameters
        $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);

        //Fill parameters
        $param_email = trim($_POST["email"]);

        //Attempt our prepared statement
        if ($stmt->execute()) {
            if ($stmt->rowCount() == 1) {
            $email_err = "A user with this email already exists.";
            } else {
            $email = trim($_POST["email"]);

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $email_err = "Invalid email format";
            }
            }
        } else {
            echo "Something went wrong. Please try again later.";
        }
        unset($stmt);
        }
    }



    //Validating password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } else {
        $password = trim($_POST["password"]);
        if (!preg_match($pattern, $password)) {
        $password_err = "Password must be at least 8 characters and must contain a minimum of one lowercase letter, one uppercase letter and one number.";
        }
    }

    //Validating password confirmation
    if (empty(trim($_POST["pass_confirm"]))) {
        $pass_confirm_err = "Please confirm your password.";
    } else {
        $pass_confirm = trim($_POST["pass_confirm"]);
        if (empty($password_err) && ($password != $pass_confirm)) {
        $pass_confirm_err = "Password and confirmation do not match.";
        }
    }

    //Check for input errors before adding to database
    if (empty($uname_err) && empty($email_err) && empty($password_err) && empty($pass_confirm_err)) {
        //Hash inserted password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        //Prepare to insert email into database
        $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";

        if ($stmt = $pdo->prepare($sql)) {
        //Bind variables to the statement as parameters
        $stmt->bindParam(":username", $uname, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":password", $hashed_password, PDO::PARAM_STR);

        //Try our prepared statement
        if ($stmt->execute()) {
            //Redirect to the homepage
            header("location: {$site_root}/register-success.php");
        } else {
            echo "Something went wrong. Please try again later.";
        }
        unset($stmt);
        }
    }
    unset($pdo);
    }

?>

<div class="log-in">
    <h1 class="page-title">Register</h1>
    <!-- Registration form -->
    <form class="log-in-form" action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' method="post">
        <div class="username">
        <input type="text" name="username" placeholder="Username"
            class="form-control <?php echo (!empty($uname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $uname; ?>">
        <span class="invalid-feedback"><?php echo $uname_err; ?></span>
        </div>
        <div class="email">
        <input type="email" name="email" placeholder="Email"
            class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
        <span class="invalid-feedback"><?php echo $email_err; ?></span>
        </div>
        <div class="password">
            <input type="password" name="password" placeholder="Password"
                class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
            <span class=" invalid-feedback"><?php echo $password_err; ?></span>
        </div>
        <div class="password">
            <input type="password" name="pass_confirm" placeholder="Confirm Password"
                class="form-control <?php echo (!empty($pass_confirm_err)) ? 'is-invalid' : ''; ?>">
            <span class=" invalid-feedback"><?php echo $pass_confirm_err; ?></span>
        </div>

        <input type="submit" class="btn btn-danger submit" value="Submit">

        <p>Already have an account? <a href="login.php">Log in</a>.</p>
    </form>
</div>

<?php
    require_once('./includes/layouts/footer.php');
?>