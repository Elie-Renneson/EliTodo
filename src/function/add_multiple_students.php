<?php
include '../core/database.php';

if (isset($_POST['logins']) && isset($_POST['promotion_id'])) {
    $logins = explode(';', $_POST['logins']);
    $promotion_id = $_POST['promotion_id'];
    $db = new Database();
    $errors = [];
    foreach ($logins as $login) {
        if (!empty($login)) {
            if (!$db->student_exists($db, $login)) {
                $data = [
                    'login' => $login,
                    'promotion_id' => $promotion_id
                ];
                $db->create('students', $data);
            } else {
                $errors[] = "Error: Student with login '$login' already exists in the database.";
            }
        }
    }
}
header('Location: ../../index.php?pages=suivi_promo');
exit;
?>
