

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/profil.css">
    <link rel="stylesheet" href="CSS/police.css">
    <link rel="stylesheet" href="CSS/header.css" />
    <title>Document</title>
</head>
<?php
require 'Classes/Database.php';
$db = new Database();
require 'header.php';

?>

<form action="Action/upload.php" method="post" enctype="multipart/form-data">
        <label for="image">Sélectionnez une image :</label>
        <input type="file" name="image" id="image" accept="image/*">
        <br>
        <input type="submit" value="Uploader l'image" name="submit">
</form>
