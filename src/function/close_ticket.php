<?php
require_once '../core/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get form data
  $ticket_id = $_POST['ticket_id'];

  // Update ticket status to closed
  $database = new Database();
  $data = [
    'closed' => 1
  ];
  $database->update('tickets', $ticket_id, $data);

  // Redirect back to ticket list page
  header('Location: ../../index.php?pages=ticket_wall');
  exit;
}
