<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";

$errorMessage = "";
if (isset($_POST['login']) && isset($_POST['password'])) {
    $errorMessage = "Mauvais identifiant ou mot de passe";
    $sql = "SELECT * FROM table_admin WHERE admin_login = :login";
    $stmt = $db->prepare($sql);
    $stmt->execute([":login" => $_POST['login']]);
    if ($row = $stmt->fetch()) {
        if (password_verify($_POST['password'], $row["admin_password"])) {
            echo "Bonjour " . $row["admin_name"];
            session_start();
            $_SESSION['user_connected'] = "ok"; // Pour éviter le hack, il faudrait mettre des valeurs plus complexes
            $_SESSION['user_name'] = $row["admin_name"];
            header("Location:index.php");
            exit(); // Bloque le script 
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BD Shop | Login</title>
    <link rel="stylesheet" href="/admin/css/bootstrap.min.css">
    <link rel="stylesheet" href="/admin/css/all.css">
    <link rel="stylesheet" href="/admin/css/style.css">
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <form action="login.php" method="post" class="d-flex justify-content-center align-items-center flex-column border border-black p-3 rounded">
            <p class="display-4 p-3 border-bottom border-2">Connexion</p>
            <div class="form-group mt-3">
                <input type="text" name="login" id="login" class="form-control" placeholder="Identifiant" required>
            </div>
            <div class="form-group mt-3">
                <input type="password" name="password" id="password" class="form-control" placeholder="Mot de passe"
                    required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Se connecter</button>
            <?php if ($errorMessage != "") { ?>
                <div class="text-danger">
                    <?= $errorMessage; ?>
                </div>
            <?php } ?>
        </form>
    </div>
</body>

</html>

<style>
    .custom-hr {
    height: 2px;
    background-color: black;
}
</style>