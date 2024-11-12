 <?php
  require_once('./includes/db.php'); //Connect to the database

  //Prepare the main select statement
  $sql = "
  SELECT cars.id, make, model, year, description, image, wishlist.user_id FROM cars LEFT JOIN (SELECT * FROM wishlist WHERE user_id = ?) AS wishlist ON wishlist.vehicle_id = cars.id
      ";

  if (!isset($conditions)) {
    $conditions = [];
  }
  if (!isset($parameters)) {
    $parameters = [];
  }

  //Add userid as first param for wishlist search
  array_unshift($parameters, $_SESSION['id'] ?? null);

  //Add the where conditions to the statement
  if (!empty($conditions)) {
    $sql .= " WHERE " . implode(' AND ', $conditions);
  }

  //Max amount of results that should be displayed
  $per_page = 5;

  if (isset($_GET["page"])) {
    $page = $_GET["page"];
  } else {
    $page = 1;
  }

  $start_from = ($page - 1) * $per_page;

  // $sql .= ' ORDER BY property.property_ID ASC';
  $sql .= ' LIMIT ' . ($per_page + 1);
  $sql .= ' OFFSET ' . $start_from;

  //Attempt to execute the statement
  if ($stmt = $pdo->prepare($sql)) {

    if ($stmt->execute($parameters)) :
      $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if (count($results) > 0) :
        if (count($results) > $per_page) {
          $next_page = true;
        } else {
          $next_page = false;
        }

  ?>

<div id="results" class="results" data-aos="fade-up">

  <?php
    //Loop over the results
    for ($i = 0; $i < count($results); $i++) :
      if ($i >= $per_page) {
        break;
      }
    $vehicle = $results[$i];
  ?>

  <!-- Single vehicle display -->
  <div class="result-vehicle">
    <div>
      <div>
        <a href="vehicle.php?id=<?php echo $vehicle['id'] ?>"
          title="View <?php echo htmlspecialchars($vehicle['make'] . " " . $vehicle['model'] . " " . $vehicle['year']) ?>">
          <div class="ratio-content rounded result-img"
            style="background-image: url('<?php echo (!empty($vehicle['image'])) ? 'uploads/' . $vehicle['image'] : 'static/img/no-image.png' ?>'); background-size: contain; background-repeat: no-repeat; background-position: center;">
          </div>
        </a>
      </div>
    </div>
    <div class="result-details">
      <div class="result-top">
        <div class="result-title">
          <a href="vehicle.php?id=<?php echo $vehicle['id'] ?>"><?php echo ucwords($vehicle['make'] . " " . $vehicle['model'] . " " . $vehicle['year']) ?></a>
        </div>

        <div>

          <!-- Show the correct wishlist button per vehicle -->
          <?php if ($_SESSION['loggedin']) : ?>
          <i class="wishlistButton wishlistStar <?php echo ($vehicle['user_id'] != null) ? 'wishlisted fa fa-star' : 'fa fa-star-o' ?>"
            data-bs-toggle="tooltip" data-bs-placement="left" data-am-vehicle-id="<?php echo $vehicle['id'] ?>"
            title="Add to Wishlist"></i>
          <?php else : ?>
          <a href="login.php" class="text-dark" title="Login to Wishlist">
            <i class="wishlistButton wishlistStar fa fa-star-o" data-bs-toggle="tooltip" data-bs-placement="left"
              title="Wishlist (Requires Login)"> </i>
          </a>
          <?php endif; ?>
        </div>
         
      </div>
      <p class="result-desc">
        <!-- Trims the description if it's too long -->
        <?php echo (strlen($vehicle['description']) > 450) ? htmlspecialchars(trim(substr($vehicle['description'], 0, 450)) . '...') : htmlspecialchars($vehicle['description']); ?>
      </p>

    </div>
  </div>

   <?php
            //Add a HR if this is not the last result to display
            if (count($results) <= $per_page) {
              if ($i < count($results) - 1) {
                echo '<hr>';
              }
            } else {
              if ($i < count($results) - 2) {
                echo '<hr>';
              }
            }
          endfor;

          ?>
 </div> <!-- #results -->

 <?php
        require('pagination.php');

      else : //If there's no results
      ?>

 <p class="text-center text-muted">No Results</p>

 <?php
      endif; // Count($results) > 0
    endif; // $stmt->execute()
  }
  ?>

 <script>
/* Makes all of the wishlist buttons interactive */

var wishlistButtons = document.getElementsByClassName('wishlistButton');

for (button of wishlistButtons) {
  button.addEventListener('click', (e) => {

    xhr = new XMLHttpRequest();
    xhr.open("POST", 'services/wishlist-service.php');
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.addEventListener('readystatechange', () => {

      if (xhr.readyState == 4 && xhr.status == 200) {

        var res = JSON.parse(xhr.responseText);

        if (res.wishlisted == "true") {
          e.target.classList.add('wishlisted', 'fa-star');
          e.target.classList.remove('fa-star-o');
        } else {
          e.target.classList.remove('wishlisted', 'fa-star')
          e.target.classList.add('fa-star-o');
        }
      }

    })

    xhr.send(`vehicleid=${e.target.dataset.amVehicleId}`);

  })
}

  //Adds fade in functionality
    AOS.init();
 </script>