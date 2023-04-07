<!DOCTYPE html>
<html>
<head>
  <title>Ticket List</title>
  <link rel="stylesheet" type="text/css" href="style/ticket.css">
</head>
<body>
  <h1>Melee List</h1>

  <!-- Form to add new melee -->
  <h2>Add New Melee</h2>
  <form method="post" action="src/function/add_melee.php">
    <label>Login:</label>
    <input type="text" name="login" required>
    <label>Description:</label>
    <textarea name="description" required></textarea>
    <button type="submit">Add Melee</button>
  </form>

  <!-- List of open melee -->
  <h2>Open Melees</h2>
  <ul>
  <?php
  require_once('src/core/database.php');
  $db = new Database();
  $tickets = $db->query("SELECT * FROM melee WHERE closed = 0")->fetchAll();
  foreach ($tickets as $ticket) {
    echo "<li class='ticket'><h3>{$ticket['login']}</h3><p>{$ticket['description']}</p><form method='post' action='src/function/close_melee.php'><input type='hidden' name='ticket_id' value='{$ticket['id']}'><button type='submit'>Close</button></form></li>";
  }
  ?>
  </ul>

</body>
</html>
