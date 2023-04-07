<?php
include '../core/database.php';
include '../core/etna_api.php';

$etna_api = new Etna_Api("https://intra-api.etna-alternance.net/users/");
$etna_api->validtoken();

// ... the rest of the code ...

if (isset($_GET['promotion_id'])) {
    $promotion_id = $_GET['promotion_id'];
    $db = new Database();

    $students = $db->query("
    SELECT s.*, m.description, m.last_melee
    FROM students s
    LEFT JOIN melee m ON s.login = m.login
    WHERE s.promotion_id = " . $promotion_id . " AND (
      m.id IS NULL OR m.id IN (
        SELECT MAX(sub_m.id) 
        FROM melee sub_m
        WHERE sub_m.login = m.login
        GROUP BY sub_m.login
      )
    )
    ORDER BY m.last_melee DESC
  ")->fetchAll();
  


  function format_date($date) {
    if ($date == NULL)
    return 'style="background-color: yellow;"';
    $datetime1 = new DateTime($date);
    $datetime2 = new DateTime();
    $interval = $datetime1->diff($datetime2);
    $months = $interval->format('%m');
  
    if ($months >= 1) {
      return 'style="background-color: red;"';
    } else {
      return 'style="background-color: green;"';
    }
  }
  

?>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Login</th>
            <th>Image</th>
            <th>Last Melee</th>
            <th>Melee Date</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($students as $student): ?>
            <tr <?php echo format_date($student['last_melee']); ?>>
                <td><?php echo $student['id']; ?></td>
                <td><?php echo $student['login']; ?></td>
                <td>
                    <img src="https://auth.etna-alternance.net/api/users/<?php echo $student['login']; ?>/photo?size=medium" alt="<?php echo $student['login']; ?>'s photo">
                </td>
                <td>
                    <?php echo ($student['description'] !== NULL) ? $student['description'] : '(need to see the student)'; ?>
                </td>
                <td>
                    <?php echo ($student['last_melee'] !== NULL) ? date("Y-m-d", strtotime($student['last_melee'])) : ''; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<?php
}
?>
