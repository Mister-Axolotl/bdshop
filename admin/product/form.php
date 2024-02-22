<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";
$productId = 0;
$productName = "";
$productDescription = "";
$productPrice = "";
$productStock = "";
$productAuthor = "";
$productCartoonist = "";

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $sql = "SELECT * FROM table_product WHERE product_id = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute([":id" => $_GET['id']]);
    if ($row = $stmt->fetch()) {
        $productId = $_GET['id'];
        $productName = $row["product_name"];
        $productDescription = $row["product_description"];
        $productPrice = $row["product_price"];
        $productStock = $row["product_stock"];
        $productAuthor = $row["product_author"];
        $productCartoonist = $row["product_cartoonist"];
    }
}
?>

<head>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/fontawesome-free/css/all.css">
</head>

<main class="container">
    <a href="index.php" title="Retour">
        <i class="fa-solid fa-arrow-left mt-3"></i>
        Retour
    </a>
    <form action="process.php" method="post">
        <div class="form-group mt-3">
            <label for="productName">Nom :</label>
            <input type="text" class="form-control" name="product_name" id="productName" placeholder="Entrez le nom du produit"
                value="<?= $productName; ?>">
        </div>

        <div class="form-group mt-3">
            <label for="productDescription">Description :</label>
            <input type="text" class="form-control" name="product_description" id="productName" placeholder="Entrez la description du produit"
                value="<?= $productDescription; ?>">
        </div>

        <div class="form-group mt-3">
            <label for="productPrice">Prix :</label>
            <input type="number" class="form-control" name="product_price" id="productPrice"
                placeholder="Entrez le prix du produit" value="<?= $productPrice; ?>">
        </div>

        <div class="form-group mt-3">
            <label for="productStock">Stock :</label>
            <input type="number" class="form-control" name="product_stock" id="productStock"
                placeholder="Entrez le stock du produit" value="<?= $productStock; ?>">
        </div>

        <div class="form-group mt-3">
            <label for="productAuthor">Auteur :</label>
            <input type="text" class="form-control" name="product_author" id="productAuthor"
                placeholder="Entrez le nom de l'auteur du produit" value="<?= $productAuthor; ?>">
        </div>

        <div class="form-group mt-3">
            <label for="productCartoonist">Dessinateur :</label>
            <input type="text" class="form-control" name="product_cartoonist" id="productCartoonist" placeholder="Entrez le nom du dessinateur du produit"
                value="<?= $productCartoonist; ?>">
        </div>

        <button type="submit" class="btn btn-primary mt-3">Valider</button>
        <input type="hidden" name="id" value="<?= $productId; ?>">
    </form>
</main>