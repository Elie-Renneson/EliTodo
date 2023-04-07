<?php
include 'src/core/database.php';
include 'src/core/etna_api.php';

$db = new Database();
$students = $db->query("SELECT students.*, promotions.id_promo FROM students JOIN promotions ON students.promotion_id = promotions.id LIMIT 16")->fetchAll();

$etna_api = new Etna_Api("https://intra-api.etna-alternance.net/users/");
$etna_api->validtoken();
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
        <th>API Data</th>
        <th>Conversations</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($students as $student): ?>
        <tr>
          <td><?php echo $student['id']; ?></td>
          <td><?php echo $student['login']; ?></td>
          <td>
            <?php
            $api_data = $etna_api->get_more($student['login']);
            $data_object = json_decode($api_data);
            echo '<pre>';
            print_r($data_object);
            echo '</pre>';
            ?>
          </td>
          <td class="conversations" data-student-id="<?php echo $data_object->id; ?>" data-term-id="<?php echo $student['id_promo']; ?>">
            Loading conversations...
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <script>
  $(document).ready(function() {
    $('.conversations').each(function() {
      const studentId = $(this).data('student-id');
      const termId = $(this).data('term-id');
      const element = $(this);
      
      $.get('src/function/get_conversations.php', { student_id: studentId, term_id: termId }, function(data) {
        console.log('Received data:', data);
        try {
          const parsedData = data;
          const formattedData = parsedData;
          element.html('<pre>' + formattedData + '</pre>');
        } catch (error) {
          console.error('Error parsing data:', error);
          element.text('Error loading conversations');
        }
      }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error('AJAX error:', textStatus, errorThrown);
        element.text('Error loading conversations');
      });
    });
  });
</script>


</body>
</html>

