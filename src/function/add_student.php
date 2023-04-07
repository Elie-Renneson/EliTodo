<?php
include '../core/database.php';

$db = new Database();
$student_data = [
  'login' => $_POST['login'],
  'promotion_id' => $_POST['promotion_id']
];
if (!$db->student_exists($db, $student_data['login'])) {
    if ($db->create('students', $student_data)) {
        header('Location: ../../index.php?pages=suivi_promo');
        exit;
    } else {
        echo "Error: Unable to add student.";
    }
} else {
    echo "Error: Student already exists in the database.";
}


header('Location: ../../index.php?pages=suivi_promo');
exit;
?>
