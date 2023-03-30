<?php
require_once '../core/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get form data
  $title = $_POST['login'];
  $description = $_POST['description'];

  // Insert new ticket into database
  $database = new Database();
  $data = [
    'login' => $title,
    'description' => $description,
    'closed' => 0
  ];
  $database->create('melee', $data);

  // Redirect back to ticket list page
  header('Location: ../../index.php?pages=melee');
  exit;
}
