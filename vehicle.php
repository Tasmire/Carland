<?php

    require_once('./includes/layouts/header.php'); //Gets the header
    require_once('./includes/db.php'); //Connect to the database

    //Checks whether an ID route query parameter has been provided
    if (isset($_GET['id']) && !empty(trim($_GET['id']))) {

    $param_id = trim($_GET['id']);

    $sql = "SELECT * FROM cars WHERE id = :vehicle_id";

    if ($stmt = $pdo->prepare($sql)) {

        $stmt->bindParam(":vehicle_id", $param_id);

        if ($stmt->execute()) {

        //Check to make sure exactly one row was returned
        if ($stmt->rowCount() == 1) {

            //Get the row as an associative array
            $vehicle = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {

            //ID parameter in URL is invalid
            header('location: 404.php');
            exit();
        }
        }
    }

    $title = $vehicle['make'] . " " . $vehicle['model'] . " " . $vehicle['year']; //The page title

    //Close the statement
    unset($stmt);
    } else {

    //URL doesn't contain a id parameter
    header('location: 404.php');
    exit();
    }

    //See if the vehicle is on the user's wishlist
    $sql = "SELECT vehicle_id FROM wishlist WHERE user_id = :user_id AND vehicle_id = :vehicle_id";
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(':user_id', $_SESSION['id']);
        $stmt->bindParam(':vehicle_id', $param_id);

        if ($stmt->execute()) {
            if ($stmt->rowCount() == 1) {
            $wishlisted = true;
            } else {
            $wishlisted = false;
            }
        }
    }

?>

<div class=back>
    <a href="index.php">< Back to all vehicles</a>
</div>

<?php //Select information from database
    $sql = "SELECT * FROM cars WHERE id = $param_id";

    if ($result = $pdo->query($sql)) :
        while ($vehicle = $result->fetch()) :
    ?>

    <!-- Displays all vehicle information -->
    <div class="vehicle-full" data-aos="fade-up">
        <img class="vehicle-page-img" src="<?php echo "uploads/" . $vehicle['image'] ?>">
        <div class="vehicle-details">
            <!-- Vehicle full name -->
            <h1 class="single-vehicle-name">
                <?php echo $vehicle['make'] . " " . $vehicle['model'] . " " . $vehicle['year'] ?>
            </h1>
            <div class="vehicle-specs">
                <!-- Make title and result -->
                <div class="vehicle-row">
                    <div class="vehicle-label">Make</div>
                    <div class="underline"></div>
                    <div class="vehicle-spec"><?php echo $vehicle['make'] ?></div>
                </div>
                <!-- Model title and result -->
                <div class="vehicle-row">
                    <div class="vehicle-label">Model</div>
                    <div class="underline"></div>
                    <div class="vehicle-spec"><?php echo $vehicle['model'] ?></div>
                </div>
                <!-- Year title and result -->
                <div class="vehicle-row">
                    <div class="vehicle-label">Year</div>
                    <div class="underline"></div>
                    <div class="vehicle-spec"><?php echo $vehicle['year'] ?></div>
                </div>
                <!-- Colour title and result -->
                <div class="vehicle-row">
                    <div class="vehicle-label">Colour</div>
                    <div class="underline"></div>
                    <div class="vehicle-spec"><?php echo $vehicle['colour'] ?></div>
                </div>
                <!-- Type title and result -->
                <div class="vehicle-row">
                    <div class="vehicle-label">Type</div>
                    <div class="underline"></div>
                    <div class="vehicle-spec"><?php echo $vehicle['type'] ?></div>
                </div>
                <!-- Fuel title and result -->
                <div class="vehicle-row">
                    <div class="vehicle-label">Fuel</div>
                    <div class="underline"></div>
                    <div class="vehicle-spec"><?php echo $vehicle['fuel'] ?></div>
                </div>
                <!-- Safety rating title and result -->
                <div class="vehicle-row">
                    <div class="vehicle-label">Safety Rating</div>
                    <div class="underline"></div>
                    <div class="vehicle-spec">
                        <!-- Checks for value for each star, makes it full or empty -->
                        <i <?php if($vehicle['safety_rating']>=1): ?>
                            class="fa fa-star"
                            <?php else: ?>
                            class="fa fa-star-o"
                            <?php endif;?> aria-hidden="true"></i>
                        <i <?php if($vehicle['safety_rating']>=2): ?>
                            class="fa fa-star"
                            <?php else: ?>
                            class="fa fa-star-o"
                            <?php endif;?> aria-hidden="true"></i>
                        <i <?php if($vehicle['safety_rating']>=3): ?>
                            class="fa fa-star"
                            <?php else: ?>
                            class="fa fa-star-o"
                            <?php endif;?> aria-hidden="true"></i>
                        <i <?php if($vehicle['safety_rating']>=4): ?>
                            class="fa fa-star"
                            <?php else: ?>
                            class="fa fa-star-o"
                            <?php endif;?> aria-hidden="true"></i>
                        <i <?php if($vehicle['safety_rating']==5): ?>
                            class="fa fa-star"
                            <?php else: ?>
                            class="fa fa-star-o"
                            <?php endif;?> aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            <!-- Shows wishlist button if user is logged in -->
            <?php if ($_SESSION['loggedin']) : ?>
            <button
                class="btn rounded-pill wishlist-add wishlistButton <?php echo $wishlisted ? 'btn-warning'  : 'btn-outline-secondary' ?>"
                data-am-vehicle-id="<?php echo $vehicle['id'] ?>">
                <?php echo $wishlisted ? 'Wishlisted' : 'Add to Wishlist' ?>
            </button>
            <?php else : ?>
            <a href="login.php?redirectToVehicle=<?php echo $vehicle['id'] ?>">
                <button class="btn btn-outline-secondary rounded-pill wishlist-signin">
                Sign in to Wishlist
                </button>
            </a>
            <?php endif; ?>
        </div>
    </div>

    <p class="vehicle-desc">
        <?php echo $vehicle['description'] ?>
    </p>
<?php endwhile;
    endif; ?>

<script>
  //Add functionality to the wishlist button

  var wishlistButtons = document.getElementsByClassName('wishlistButton');

  for (button of wishlistButtons) {
    button.addEventListener('click', (e) => {
      console.log(e.target.dataset.amVehicleId);

      xhr = new XMLHttpRequest();
      xhr.open("POST", 'services/wishlist-service.php');
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

      xhr.addEventListener('readystatechange', () => {

        if (xhr.readyState == 4 && xhr.status == 200) {
          var res = JSON.parse(xhr.responseText);

          if (res.wishlisted == "true") {
            e.target.classList.add('btn-warning');
            e.target.classList.remove('btn-outline-secondary');
            e.target.innerHTML = 'Wishlisted';
          } else {
            e.target.classList.add('btn-outline-secondary');
            e.target.classList.remove('btn-warning');
            e.target.innerHTML = 'Add to Wishlist';
          }
        }

      })

      xhr.send(`vehicleid=${e.target.dataset.amVehicleId}`);
    })
  }
  
  //Adds fade in functionality
    AOS.init();
  </script>

<?php
    require_once('./includes/layouts/footer.php');
?>