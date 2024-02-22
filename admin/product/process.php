<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

if (isset($_POST['id']) && $_POST['id'] > 0) {
    $sql = "UPDATE table_product 
    SET product_name = :name, product_description = :description, product_price = :price, product_stock = :stock, product_author = :author, product_cartoonist = :cartoonist 
    WHERE product_id=:id";
} else {
    $sql = "INSERT INTO table_product (product_name, product_description, product_price, product_stock, product_author, product_cartoonist)
    VALUES (:name, :description, :price, :stock, :author, :cartoonist)";
}

$stmt = $db->prepare($sql);

if (isset($_POST['id']) && $_POST['id'] > 0) {
    $stmt->bindParam(':id', $_POST['id']);
}

if (isset($_POST['product_name'])) {
    $stmt->bindParam(':name', $_POST['product_name']);
}

if (isset($_POST['product_description'])) {
    $stmt->bindParam(':description', $_POST['product_description']);
}

if (isset($_POST['product_price'])) {
    $stmt->bindParam(':price', $_POST['product_price']);
}

if (isset($_POST['product_stock'])) {
    $stmt->bindParam(':stock', $_POST['product_stock']);
}

if (isset($_POST['product_author'])) {
    $stmt->bindParam(':author', $_POST['product_author']);
}

if (isset($_POST['product_cartoonist'])) {
    $stmt->bindParam(':cartoonist', $_POST['product_cartoonist']);
}

$stmt->execute();

header("Location:index.php");