<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";
$product_id = 0;
$product_name = "";
$product_description = "";
$product_price = "";
$product_stock = "";
$product_author = "";
$product_cartoonist = "";

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $sql = "SELECT * FROM table_product WHERE product_id = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute([":id" => $_GET['id']]);
    if ($row = $stmt->fetch()) {
        $product_id = $_GET['id'];
        $product_name = $row["product_name"];
        $product_description = $row["product_description"];
        $product_price = $row["product_price"];
        $product_stock = $row["product_stock"];
        $product_author = $row["product_author"];
        $product_cartoonist = $row["product_cartoonist"];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BDShop | Formulaire</title>

    <head>
        <link rel="stylesheet" href="../../css/bootstrap.min.css">
        <link rel="stylesheet" href="../../css/fontawesome-free/css/all.css">
    </head>
</head>

<body>
    <main class="container">
        <a href="index.php" title="Retour">
            <i class="fa-solid fa-arrow-left mt-3"></i>
            Retour
        </a>
        <form action="process.php" method="post" enctype="multipart/form-data">
            <!-- enctype sert à envoyer autre chose que du texte -->
            <div class="form-group mt-3">
                <label for="product_name">Nom :</label>
                <input type="text" class="form-control" name="product_name" id="product_name"
                    placeholder="Entrez le nom du produit" value="<?= $product_name; ?>">
            </div>

            <div class="form-group mt-3">
                <label for="product_description">Description :</label>
                <input type="text" class="form-control" name="product_description" id="product_description"
                    placeholder="Entrez la description du produit" value="<?= $product_description; ?>">
            </div>

            <div class="form-group mt-3">
                <label for="product_price">Prix :</label>
                <input type="number" class="form-control" name="product_price" id="product_price"
                    placeholder="Entrez le prix du produit" value="<?= $product_price; ?>">
            </div>

            <div class="form-group mt-3">
                <label for="product_stock">Stock :</label>
                <input type="number" class="form-control" name="product_stock" id="product_stock"
                    placeholder="Entrez le stock du produit" value="<?= $product_stock; ?>">
            </div>

            <div class="form-group mt-3">
                <label for="product_author">Auteur :</label>
                <input type="text" class="form-control" name="product_author" id="product_author"
                    placeholder="Entrez le nom de l'auteur du produit" value="<?= $product_author; ?>">
            </div>

            <div class="form-group mt-3">
                <label for="product_cartoonist">Dessinateur :</label>
                <input type="text" class="form-control" name="product_cartoonist" id="product_cartoonist"
                    placeholder="Entrez le nom du dessinateur du produit" value="<?= $product_cartoonist; ?>">
            </div>

            <div class="form-group mt-3">
                <label for="product_image">Image de couverture :</label>
                <input type="file" class="form-control" name="product_image" id="product_image">
            </div>

            <button type="submit" class="btn btn-primary mt-3">Valider</button>
            <input type="hidden" name="product_id" value="<?= $product_id; ?>">
        </form>
    </main>
</body>

</html>