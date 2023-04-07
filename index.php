<!DOCTYPE html>
<html>
<head>
    <title>EliTodo</title>
    <link rel="stylesheet" href="style/index.css?id=347">
    <link rel="icon" type="image/png" href="assets/picture/logo.png"/>
</head>
<body>

<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'src/core/index_core.php';
?>

<div class="container">
    <header class="page-header">
        <h1>EliTodo</h1>
        <nav>
            <ul>
                <li><a href="index.php?pages=home">Home</a></li>
                <li><a href="index.php?pages=calendar">Calendar</a></li>
                <li><a href="index.php?pages=ticket_wall">Ticket Wall</a></li>
                <li><a href="index.php?pages=melee">Melee</a></li>
                <li><a href="index.php?pages=suivi_promo">Suivi Promos</a></li>
                <li><a href="index.php?pages=compteur_de_licornes">Compteur de licornes</a></li>
                
            </ul>
        </nav>
    </header>

    <div class="content-body">
        <?php
        if (isset($_GET["pages"])) {
            $pages = htmlspecialchars($_GET["pages"]);
            $tmp = 0;
            $ta = $_SESSION["IndexCore"]->get_all_files_php("sheet/", $pages);
            foreach ($ta as $l) {
                $tmp = 1;
                include ("sheet/".$l);
            }
            if ($tmp === 0) {
                echo "Error 404<br>";
            }
        }
        ?>
    </div>
</div>

</body>
</html>
