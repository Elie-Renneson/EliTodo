<?php
require_once '../core/database.php';
require_once '../core/etna_api.php';

$etna_api = new Etna_Api("https://intra-api.etna-alternance.net/users/");
$etna_api->validtoken();

$student_id = $_GET['student_id'];
$term_id = $_GET['term_id'];

$conversations_data = $etna_api->get_student_conversations($student_id, $term_id);
print_r ($conversations_data);
?>
