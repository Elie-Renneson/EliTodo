<?php
// Set timezone
date_default_timezone_set('UTC');

// Include database.php file
require_once 'src/core/database.php';

// Instantiate database class
$db = new Database();

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the data from the form
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Create the event in the database
    $db->create('events', [
        'title' => $title,
        'description' => $description,
        'start_time' => $start_time,
        'end_time' => $end_time,
    ]);
}

// Get the current date
$today = date('Y-m-d');

// Calculate the start and end dates of the week
$start_of_week = date('Y-m-d', strtotime('monday this week', strtotime($today)));
$end_of_week = date('Y-m-d', strtotime('sunday this week', strtotime($today)));

// Display the calendar
?>
<h1>EliTodo</h1>
<div class="first">
    <table id="calendar">
        <tr>
            <th>Hour</th>
            <th>Monday</th>
            <th>Tuesday</th>
            <th>Wednesday</th>
            <th>Thursday</th>
            <th>Friday</th>
            <th>Saturday</th>
            <th>Sunday</th>
        </tr>

        <?php for ($hour = 8; $hour < 24; $hour++) : ?>
        <tr>
            <td><?= str_pad($hour, 2, '0', STR_PAD_LEFT) ?>:00</td>
            <?php for ($i = 0; $i < 7; $i++) :
                $current_date = date('Y-m-d', strtotime("$start_of_week +$i day"));
                ?>
            <td>
                <?php
                // Get events for the current date and hour from the database
                $stmt = $db->query("SELECT * FROM events WHERE DATE(start_time) = '$current_date' AND HOUR(start_time) = '$hour'");
                $events = $stmt->fetchAll();
                foreach ($events as $event) :
                ?>
                <div class="event">
                    <h3><?= $event['title'] ?></h3>
                    <p><?= $event['description'] ?></p>
                </div>
                <?php endforeach; ?>
            </td>
            <?php endfor; ?>
        </tr>
        <?php endfor; ?>

    </table>
</div>

<div class="form-container">
    <h2>Create Event</h2>
    <form method="POST">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title"><br>
        <label for="description">Description:</label><br>
        <textarea id="description" name="description"></textarea><br>
        <label for="start_time">Start Time:</label><br>
        <input type="datetime-local" id="start_time" name="start_time"><br>
        <label for="end_time">End Time:</label><br>
        <input type="datetime-local" id="end_time" name="end_time"><br>
        <input type="submit" value="Create">
    </form>
</div>

<?php
// Include the CSS file
echo '<link rel="stylesheet" href="style/style.css">';
?>

<script>
  const cells = document.querySelectorAll('#calendar td');
  cells.forEach(cell => {
    cell.addEventListener('click', () => {
      // Get the date and time of the clicked cell
      const date = cell.getAttribute('data-date');
      const time = cell.getAttribute('data-time');

      // Set the values of the start_time and end_time input fields in the form
      document.querySelector('#start_time').value = date + 'T' + time;
      document.querySelector('#end_time').value = date + 'T' + time;

      // Display the form container
      document.querySelector('.form-container').style.display = 'block';
    });
  });
</script>
