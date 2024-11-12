<?php
    session_start();

    //If the user is logged in, redirect them to the home page
    if (!empty($_SESSION['loggedin']) && $_SESSION['loggedin']) {
    header('location: index.php');
    exit;
    }

    $title = "Log in"; //The page title

    require_once('./includes/layouts/header.php'); //Gets the header
    require_once('./includes/db.php'); //Connect to the database

    $verification_err = false;
    $username = '';
    $password = '';
    $hashed_password = '';

    //Checks credentials
    if (isset($_POST['username']) || isset($_POST['password'])) {

    //Get the username and password
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    //If there's no username provided, set error
    if (empty($username)) {
        $username_err = "The username field cannot be empty.";
    }

    //If there's no password provided, set error
    if (empty($password)) {
        $password_err = 'The Password field cannot be empty';
    }

    //If there's no errors
    if (empty($username_err) && empty($password_err)) {

        $sql = "SELECT id, username, password, staff FROM users WHERE username = :username";

        if ($stmt = $pdo->prepare($sql)) {

        $stmt->bindParam(":username", $username);

        if ($stmt->execute()) {
            if ($stmt->rowCount() != 1) {
            $verification_err = true;
            } else {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashed_password = $user['password'];
            $verify = password_verify($password, $hashed_password);

            if ($verify) {

                $_SESSION["loggedin"] = true;
                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['staff'] = $user['staff'];

                if (!empty($_POST['redirectToVehicle'])) {
                header("location: vehicle.php?id={$_POST['redirectToVehicle']}");
                } else {
                header('location: index.php');
                }
            } else {
                $verification_err = true;
            }
            }
        }
        }
    }
    }

?>

<div class="log-in">
    <h1 class="page-title">Log In</h1>
    <!-- Login form -->
    <!-- If there was an error, show it -->
    <!-- <?php if ($verification_err) : ?>
    <div class="alert alert-danger mb-1" role="alert">
        Invalid Credentials
    </div>
    <?php endif; ?> -->
    <form class="log-in-form" action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' method="post">
        <div class="username">
        <input type="text" name="username" placeholder="Username"
            class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" autofocus>
        <span class="invalid-feedback"><?php echo $username_err; ?></span>
        </div>
        <div class="password">
            <input type="password" name="password" placeholder="Password"
                class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
            <span class=" invalid-feedback"><?php echo $password_err; ?></span>
        </div>

        <input type="hidden" name="redirectToVehicle"
            value="<?php echo (!empty($_GET['redirectToVehicle'])) ? $_GET['redirectToVehicle'] : '' ?>">

        <input type="submit" class="btn btn-danger submit" value="Submit">

        <p>Don't have an account? <a href="register.php">Sign up</a>.</p>
    </form>
</div>

<?php
    require_once('./includes/layouts/footer.php');
?>