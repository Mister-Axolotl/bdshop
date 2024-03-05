<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";

function generateFileName($str, $ext)
{
    $result = $str;
    $result = strtolower($result);
    $pattern = array(' ', 'é', 'è', 'ë', 'ê', 'á', 'à', 'ä', 'â', 'å', 'ã', 'ó', 'ò', 'ö', 'ô', 'õ', 'í', 'ì', 'ï', 'ú', 'ù', 'ü', 'û', 'ý', 'ÿ', 'ø', 'œ', 'ç', 'ñ', 'ß', 'ț', 'ș', 'ř', 'ž', 'á', 'č', 'ď', 'é', 'ě', 'í', 'ň', 'ó', 'ř', 'š', 'ť', 'ú', 'ů', 'ý', 'ž');
    $replace = array('-', 'e', 'e', 'e', 'e', 'a', 'a', 'a', 'a', 'a', 'a', 'o', 'o', 'o', 'o', 'o', 'i', 'i', 'i', 'u', 'u', 'u', 'u', 'y', 'y', 'o', 'ae', 'c', 'n', 'ss', 't', 's', 'r', 'z', 'a', 'c', 'd', 'e', 'e', 'i', 'n', 'o', 'r', 's', 't', 'u', 'u', 'y', 'z');
    $result = str_replace($pattern, $replace, $result);

    $i = 1;
    while (file_exists($_SERVER['DOCUMENT_ROOT'] . "/upload/" . $result . ($i > 1 ? " (" . $i . ")" : "") . "." . $ext)) {
        $i++;
    }

    if ($i > 1) {
        $result .= " (" . $i . ")";
    }

    return $result;
}

if (isset($_POST['product_id']) && $_POST['product_id'] > 0) {
    $sql = "UPDATE table_product 
    SET product_name = :name, product_description = :description, product_price = :price, product_stock = :stock, product_author = :author, product_cartoonist = :cartoonist 
    WHERE product_id=:id";
} else {
    $sql = "INSERT INTO table_product (product_name, product_description, product_price, product_stock, product_author, product_cartoonist)
    VALUES (:name, :description, :price, :stock, :author, :cartoonist)";
}

$stmt = $db->prepare($sql);

if (isset($_POST['product_id']) && $_POST['product_id'] > 0) {
    $stmt->bindParam(':id', $_POST['product_id']);
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

if (isset($_FILES['product_image']) && $_FILES['product_image']['name'] != "") {
    $sql = "SELECT product_image FROM table_product
    WHERE product_id = :product_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":product_id", $_POST['product_id'] > 0 ? $_POST['product_id'] : $db->lastInsertId());
    $stmt->execute();

    if ($row = $stmt->fetch()) {
        if ($row['product_image'] != "" && file_exists($_SERVER['DOCUMENT_ROOT'] . "/upload/" . $row['product_image'])) {
            unlink($_SERVER['DOCUMENT_ROOT'] . "/upload/" . $row['product_image']);
        }
    }

    $extension = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);
    $filename = generateFileName($_POST['product_name'], $extension);
    move_uploaded_file(
        $_FILES['product_image']['tmp_name'],
        $_SERVER['DOCUMENT_ROOT'] . "/upload/" . $filename . "." . $extension
    );

    $sql = "UPDATE table_product SET product_image=:product_image 
    WHERE product_id = :product_id";

    $stmt = $db->prepare($sql);
    $stmt->bindValue(":product_image", $filename . "." . $extension); // Value prend la valeur là où on la déclare Param prend en compte les modifications
    $stmt->bindValue(":product_id", $_POST['product_id'] > 0 ? $_POST['product_id'] : $db->lastInsertId());
    $stmt->execute();
}

header("Location:index.php");