<?php
include '../core/database.php';
include '../core/etna_api.php';

$etna_api = new Etna_Api("https://intra-api.etna-alternance.net/users/");
$etna_api->validtoken();
$api_data = $etna_api->get_more($_POST['login']);
$data_object = json_decode($api_data);

$db = new Database();
$student_data = [
    'login' => $_POST['login'],
    'promotion_id' => $_POST['promotion_id']
];

if (isset($data_object->id)) {
    $student_data['student_id'] = $data_object->id;
}

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
