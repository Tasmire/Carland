<?php
//Alters the params in a URL
require_once(__DIR__ . '/../functions/change_url_parameter.php');

if (empty($_GET['page'])) {
  $_GET['page'] = 1;
}
?>

<!-- Adds previous and next button for wishlist page -->
<nav aria-label="Page navigation">
  <ul class="pagination justify-content-center my-1">
    <li class="page-item <?php echo ($_GET['page'] <= 1) ? 'disabled' : '' ?>">
      <a class="page-link"
        href="<?php echo change_url_parameter($_SERVER['REQUEST_URI'], 'page', max(0, (int) $_GET['page'] - 1)); ?>">Previous</a>
    </li>
    <li class="page-item <?php echo (!$next_page) ? 'disabled' : '' ?>">
      <a class="page-link"
        href="<?php echo change_url_parameter($_SERVER['REQUEST_URI'], 'page', $_GET['page'] + 1); ?>">Next</a>
    </li>
  </ul>
</nav>