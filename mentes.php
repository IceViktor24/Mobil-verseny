<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fileName = $_POST['fileName'];
    $nev = htmlspecialchars($_POST['nev']);
    $tema = htmlspecialchars($_POST['tema']);
    $datum = htmlspecialchars($_POST['datum']);
    $gepeltIdo = htmlspecialchars($_POST['gepeltIdo']);
    $szoveg = htmlspecialchars($_POST['szoveg']);

    $fileContent = "Név: $nev\nTéma: $tema\nDátum: $datum\nGépelt idő: $gepeltIdo\n\n$szoveg";

    $file = fopen($fileName, 'w');
    fwrite($file, $fileContent);
    fclose($file);

    echo "A fájl sikeresen létrejött: " . htmlspecialchars($fileName);
}
?>
