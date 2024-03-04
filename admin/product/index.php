<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

$sql = "SELECT * FROM table_product ORDER BY product_name";
$stmt = $db->prepare($sql);

if (isset($_GET['search_query'])) { // Si une requête a été soumise
    $search_query = '%' . $_GET['search_query'] . '%'; // L'usage des % est spécifique à "LIKE" (c'est du REGEX pour dire n'importe quel caractère)
    $sql = "SELECT * FROM table_product 
    WHERE LOWER(product_name) LIKE LOWER(:search_query)
    OR LOWER(product_description) LIKE LOWER(:search_query)
    OR LOWER(product_author) LIKE LOWER(:search_query)
    OR LOWER(product_cartoonist) LIKE LOWER(:search_query)
    ORDER BY product_name";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':search_query', $search_query);
}

$stmt->execute();
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
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a href="/admin/index.php">Panel Admin</a>
    </nav>
    <main class="container">

        <div class="d-flex justify-content-between my-3 align-items-center">
            <h1>Liste des produits</h1>
            <a href="form.php" class="btn btn-primary px-5" title="Ajouter un produit">Ajouter</a>
        </div>

        <table class="table table-striped border-2 border">
            <caption>liste des produits</caption>
            <div class="input-group rounded mb-5">
                <form action="index.php" method="GET">
                    <button class="btn btn-outline-primary me-3 rounded" title="Réinitialiser le champ de rechercher">
                        <i class="fa-solid fa-arrow-rotate-right"></i>
                    </button>
                    <input type="search" class="form-control rounded" placeholder="Search" aria-label="Search"
                        aria-describedby="search-addon" id="research" name="search_query"
                        value="<?= $search_query ?>" />
                    <button type="submit" class="btn btn-primary px-5 rounded ms-3" title="Chercher un produit">Chercher</button>
                </form>
                <span class="input-group-text border-0" id="search-addon">
                    <i class="fas fa-search"></i>
                </span>
            </div>
            <hr class="text-primary">
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