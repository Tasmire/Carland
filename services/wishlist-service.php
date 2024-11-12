<?php
/* This service allows the user to add or remove items from their wishlist without refreshing the page */

session_start();

//Check whether the user is logged in
if ($_SESSION['loggedin']) {

  //Check whether the request was made as a POST request
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    require_once('../includes/db.php');

    header('Content-Type: application/json'); //Set the response type to Json

    $user_id = $_SESSION['id'];
    $vehicle_id = trim($_POST['vehicleid']);


    $sql = 'SELECT * FROM wishlist WHERE user_id = :user_id AND vehicle_id = :vehicle_id';

    if ($stmt = $pdo->prepare($sql)) {

      $stmt->bindParam(':user_id', $user_id);
      $stmt->bindParam(':vehicle_id', $vehicle_id);

      if ($stmt->execute()) {

        //Setup a response to use in both cases
        $response = ['user' => $_SESSION['id'], 'vehicle_id' => $vehicle_id];

        //If there was a result, delete it
        if ($stmt->rowCount() == 1) {

          $sql = 'DELETE FROM wishlist WHERE user_id = :user_id AND vehicle_id = :vehicle_id';

          if ($stmt = $pdo->prepare($sql)) {

            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':vehicle_id', $vehicle_id);

            if ($stmt->execute()) {
              $response['wishlisted'] =  'false';
              echo json_encode($response);
            }
          }
        } else {
          $sql = 'INSERT INTO wishlist (user_id, vehicle_id) VALUES (:user_id, :vehicle_id)';

          if ($stmt = $pdo->prepare($sql)) {

            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':vehicle_id', $vehicle_id);

            if ($stmt->execute()) {
              $response['wishlisted'] =  'true';
              echo json_encode($response);
            }
          }
        }
      }
    }
  }
} else {
  http_response_code(403);
}