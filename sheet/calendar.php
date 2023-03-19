<?php

// Set timezone
date_default_timezone_set('UTC');

// Include database.php file
require_once 'src/core/database.php';

// Instantiate database class
$db = new Database();

// Get the current date
$today = date('Y-m-d');

// Calculate the start and end dates of the week
$start_of_week = date('Y-m-d', strtotime('monday this week', strtotime($today)));
$end_of_week = date('Y-m-d', strtotime('sunday this week', strtotime($today)));


// Get events for the current week from the database
$stmt = $db->query("SELECT * FROM events WHERE start_time BETWEEN '$start_of_week 00:00:00' AND '$end_of_week 23:59:59'");
$events = $stmt->fetchAll();

// Group events by date and hour
$grouped_events = [];
foreach ($events as $event) {
    $start_time = strtotime($event['start_time']);
    $date = date('Y-m-d', $start_time);
    $hour = date('H', $start_time);
    $grouped_events[$date][$hour][] = $event;
}

// Display the calendar
?>
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
            <td data-date="<?= $current_date ?>" data-time="<?= str_pad($hour, 2, '0', STR_PAD_LEFT) ?>:00">
                <?php if (isset($grouped_events[$current_date][$hour])) : ?>
                <?php foreach ($grouped_events[$current_date][$hour] as $event) : ?>
                <div class="event">
                    <h3><?= $event['title'] ?></h3>
                    <p><?= $event['description'] ?></p>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
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
const calendarCases = document.querySelectorAll('#calendar td');

calendarCases.forEach(calendarCase => {
  calendarCase.addEventListener('click', () => {
    // Get the date and time of the clicked cell
    const date = calendarCase.getAttribute('data-date');
    const time = calendarCase.getAttribute('data-time');

    // Set the values of the start_time and end_time input fields in the form
    document.querySelector('#start_time').value = date + 'T' + time;
    document.querySelector('#end_time').value = date + 'T' + time;

    // Display the form container
    document.querySelector('.form-container').style.display = 'block';
  });
});

// Hide the form container by default
document.querySelector('.form-container').style.display = 'none';
</script>

<?php

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

?>