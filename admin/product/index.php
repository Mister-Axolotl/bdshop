<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

$sql = "SELECT * FROM table_product ORDER BY product_name";
$stmt = $db->prepare($sql);
$stmt->execute();
if (isset($_GET['search_query'])) {
    echo "test";
}
$recordset = $stmt->fetchAll();
$search_query = "";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BDShop | Liste des produits</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/fontawesome-free/css/all.css">
</head>

<body>
    <main class="container">
        <div class="d-flex justify-content-between my-3 align-items-center">
            <h1>Liste des produits</h1>
            <a href="form.php" class="btn btn-primary px-5" title="Ajouter un produit">Ajouter</a>
        </div>

        <table class="table table-striped">
            <caption>liste des produits</caption>
            <div class="input-group rounded mb-5">
                <form action="research.php" method="POST">
                    <input type="search" class="form-control rounded" placeholder="Search" aria-label="Search"
                        aria-describedby="search-addon" id="research" name="search_query"
                        value="<?= $search_query ?>" />
                    <button type="submit" class="btn btn-primary px-5 rounded ms-3">Chercher</button>
                </form>
                <span class="input-group-text border-0" id="search-addon">
                    <i class="fas fa-search"></i>
                </span>
            </div>
            <tr>
                <th scope="col">Nom</td>
                <th scope="col">Description</td>
                <th scope="col">Prix</td>
                <th scope="col">Stock</td>
                <th scope="col">Auteur</td>
                <th scope="col">Dessinateur</td>
                <th scope="col" class="text-center">Supprimer</td>
                <th scope="col" class="text-center">Modifier</td>
            </tr>

            <?php foreach ($recordset as $row) { ?>
                <tr>
                    <td>
                        <?= $row["product_name"]; ?>
                    </td>
                    <td>
                        <?= $row["product_description"]; ?>
                    </td>
                    <td>
                        <?= $row["product_price"]; ?>
                    </td>
                    <td>
                        <?= $row["product_stock"]; ?>
                    </td>
                    <td>
                        <?= $row["product_author"]; ?>
                    </td>
                    <td>
                        <?= $row["product_cartoonist"]; ?>
                    </td>
                    <td class="text-center">
                        <a href="delete.php?id=<?= $row["product_id"]; ?>" title="Supprimer le produit">
                            <i class="fa-solid fa-trash text-danger"></i>
                        </a>
                    </td>
                    <td class="text-center">
                        <a href="form.php?id=<?= $row["product_id"]; ?>" title="Modifier le produit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    </main>
</body>

</html>