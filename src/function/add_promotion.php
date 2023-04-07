<?php
// add_promotion.php
include '../core/database.php';

$db = new Database();

$promotion_data = [
  'year' => $_POST['year'],
  'title' => $_POST['title']
];

if ($db->create('promotions', $promotion_data)) {
  header('Location: ../../index.php?pages=suivi_promo');
  exit();
} else {
  echo "Error: Unable to add promotion.";
}
