<?php
$url = 'https://melee-api.etna-alternance.net/student/40094/conversations/school?term_id=813';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$authenticator = isset($_COOKIE['authenticator']) ? $_COOKIE['authenticator'] : '';
curl_setopt($ch, CURLOPT_COOKIE, "authenticator=$authenticator");

$data = curl_exec($ch);

if ($data === false) {
    echo 'Error fetching data from the URL: ' . curl_error($ch);
} else {
    $decoded_data = json_decode($data);
    if (isset($decoded_data->last_message->content)) {
        echo $decoded_data->last_message->content;
    } else {
        echo "No content found.";
    }
}

curl_close($ch);
?>
