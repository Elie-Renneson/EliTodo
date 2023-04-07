<?php
include '../core/database.php';
include '../core/etna_api.php';

$etna_api = new Etna_Api("https://intra-api.etna-alternance.net/users/");
$etna_api->validtoken();


if (isset($_POST['logins']) && isset($_POST['promotion_id'])) {
    $logins = explode(';', $_POST['logins']);
    $promotion_id = $_POST['promotion_id'];
    $db = new Database();
    $errors = [];
    foreach ($logins as $login) {
        if (!empty($login)) {
            if (!$db->student_exists($db, $login)) {

                $api_data = $etna_api->get_more($login);
                $data_object = json_decode($api_data);

                $data = [
                    'login' => $login,
                    'promotion_id' => $promotion_id
                ];

                if (isset($data_object->id)) {
                    $data['student_id'] = $data_object->id;
                }
                else {
                    $data['student_id'] = -1;
                }


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
