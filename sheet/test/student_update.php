<?php
include 'src/core/database.php';
include 'src/core/etna_api.php';

$db = new Database();
$students = $db->query("SELECT students.*, promotions.id_promo FROM students JOIN promotions ON students.promotion_id = promotions.id WHERE `student_id` = -1 ")->fetchAll();

$etna_api = new Etna_Api("https://intra-api.etna-alternance.net/users/");
$etna_api->validtoken();

// Loop through the students and update their student_id in the database
foreach ($students as $student) {
    $api_data = $etna_api->get_more($student['login']);
    $data_object = json_decode($api_data);

    if (isset($data_object->id)) {
        $api_student_id = $data_object->id;
        $db_student_id = $student['id'];

        // Update the student_id in the database
        $db->query("UPDATE students SET student_id = $api_student_id WHERE id = $db_student_id");
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student API Data</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
  <h1>Student API Data</h1>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Login</th>
        <th>DB DATA</th>
        <th>API Data</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($students as $student): ?>
        <tr>
          <td><?php echo $student['id']; ?></td>
          <td><?php echo $student['login']; ?></td>
          <td><?php 
          
          echo '<pre>';
          print_r($student);
          echo '</pre>';
          ?></td>
          <td>
            <?php
            $api_data = $etna_api->get_more($student['login']);
            $data_object = json_decode($api_data);
            echo '<pre>';
            print_r($data_object);
            echo '</pre>';

            ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>



</body>
</html>

