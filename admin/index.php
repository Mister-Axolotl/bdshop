<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php"; ?> <!-- A mettre partout où on doit être connecté ! -->
<h1>Bienvenue
    <?= $_SESSION['user_name']; ?>
</h1>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BD Shop | Login</title>
    <link rel="stylesheet" href="/admin/css/bootstrap.min.css">
    <link rel="stylesheet" href="/admin/css/all.css">
    <link rel="stylesheet" href="/admin/css/style.css">
</head>