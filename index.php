<?php

    $title = "Home"; //The page title
    $home_nav = true; //Makes nav bar dark

    require_once('./includes/layouts/header.php'); //Gets the header
    require_once('./includes/db.php'); //Connect to the database

?>

<!-- Banner below nav bar -->
<div class="banner">
    <img src="<?php echo $site_root ?>/static/img/banner.png" class="banner-img" alt="Time lapse photo of car lights on a dark road"
          height="354px">
    <h1 class="banner-top">
        Great cars.
    </h1>
    <h1 class="banner-bottom">
        Unbeatable prices.
    </h1>
</div>

<h2 class="section-title" id="cars-available">
    Over 10,000 cars available!
</h2>

<!-- Retrieves vehicle data and displays image and name -->
<div class="vehicle-list" data-aos="fade-up">
    <?php
        $sql = "SELECT id, make, model, year, image FROM cars";

        if ($result = $pdo->query($sql)) :
            while ($row = $result->fetch()) :
        ?>

        <div class="single-vehicle">
                <img class="vehicle-img" src="<?php echo (!empty($row['image'])) ? 'uploads/' . $row['image'] : 'static/img/no-image.png' ?>">
                <a class="vehicle-link" href="vehicle.php?id=<?php echo $row['id'] ?>">
                    <h3 class="vehicle-name">
                        <?php echo $row['make'] . " " . $row['model'] . " " . $row['year'] ?>
                    </h3>
                </a>
            </div>

        <?php
            endwhile;
        endif; ?>
</div>

<!-- Hides results after first row -->

<span id="empty"></span>

<p onclick="seeMore()" id="moreButton">See more</p>

<script>

    function seeMore() {
        var empty = document.getElementById("empty");
        // Selects all vehicles from the 5th one onwards
        var moreCars = document.querySelectorAll(".single-vehicle:nth-child(n+5)");
        var btnText = document.getElementById("moreButton");

        if (empty.style.display === "none") {
            empty.style.display = "inline";
            btnText.innerHTML = "See more";
            // Loop through all matching elements and hide them
            moreCars.forEach(function(car) {
                car.style.display = "none";
            });
        } else {
            empty.style.display = "none";
            btnText.innerHTML = "See less";
            // Loop through all matching elements and show them
            moreCars.forEach(function(car) {
                car.style.display = "block";
            });
        }
    }
    
    //Adds fade in functionality
    AOS.init();
</script>

<?php
    require_once('./includes/layouts/footer.php');
?>