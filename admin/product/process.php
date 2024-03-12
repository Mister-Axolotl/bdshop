<?php require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/connect.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/admin/include/protect.php";
$uploadPath = $_SERVER['DOCUMENT_ROOT'] . "/upload/";

function generateFileName($str, $ext, $uploadPath)
{
    $result = $str;
    $result = strtolower($result);
    $pattern = array(' ', 'é', 'è', 'ë', 'ê', 'á', 'à', 'ä', 'â', 'å', 'ã', 'ó', 'ò', 'ö', 'ô', 'õ', 'í', 'ì', 'ï', 'ú', 'ù', 'ü', 'û', 'ý', 'ÿ', 'ø', 'œ', 'ç', 'ñ', 'ß', 'ț', 'ș', 'ř', 'ž', 'á', 'č', 'ď', 'é', 'ě', 'í', 'ň', 'ó', 'ř', 'š', 'ť', 'ú', 'ů', 'ý', 'ž');
    $replace = array('-', 'e', 'e', 'e', 'e', 'a', 'a', 'a', 'a', 'a', 'a', 'o', 'o', 'o', 'o', 'o', 'i', 'i', 'i', 'u', 'u', 'u', 'u', 'y', 'y', 'o', 'ae', 'c', 'n', 'ss', 't', 's', 'r', 'z', 'a', 'c', 'd', 'e', 'e', 'i', 'n', 'o', 'r', 's', 't', 'u', 'u', 'y', 'z');
    $result = str_replace($pattern, $replace, $result);

    $i = 1;
    while (file_exists($uploadPath . $result . ($i > 1 ? " (" . $i . ")" : "") . "." . $ext)) {
        $i++;
    }

    if ($i > 1) {
        $result .= " (" . $i . ")";
    }

    return $result;
}

function showError($error)
{
    ?>
    <a href="index.php" title="Retour">Retour</a>
    <br>
    <br>
    <h1>
        <?= $error ?>
    </h1>
    <?php
}

if (!isset($_POST['product_name'])) {
    showError("Il faut passer par le formulaire pour aller sur cette page");
    exit();
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

    // Vérifier s'il y a une erreur
    if ($_FILES['product_image']['error'] != 0) {
        showError("Erreur lors du transfert de l'image");
        exit();
    }

    $sql = "SELECT product_image FROM table_product
    WHERE product_id = :product_id";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(":product_id", $_POST['product_id'] > 0 ? $_POST['product_id'] : $db->lastInsertId());
    $stmt->execute();

    if ($row = $stmt->fetch()) {
        if ($row['product_image'] != "" && file_exists($uploadPath . $row['product_image'])) {
            unlink($uploadPath . $row['product_image']);
        }
    }

    // Renomme l'image

    $extension = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);
    $filename = generateFileName($_POST['product_name'], $extension, $uploadPath);
    move_uploaded_file(
        $_FILES['product_image']['tmp_name'],
        $uploadPath . $filename . "." . $extension
    );

    // Requête pour ajouter l'image dans la BDD

    $sql = "UPDATE table_product SET product_image=:product_image 
    WHERE product_id = :product_id";

    $stmt = $db->prepare($sql);
    $stmt->bindValue(":product_image", $filename . "." . $extension); // Value prend la valeur là où on la déclare Param prend en compte les modifications
    $stmt->bindValue(":product_id", $_POST['product_id'] > 0 ? $_POST['product_id'] : $db->lastInsertId());
    $stmt->execute();

    $filename = $filename . "." . $extension;

    // Création de l'image

    $tabTailles = [
        ["prefix" => "xl", "largeur" => 1200, "hauteur" => 900],
        ["prefix" => "lg", "largeur" => 800, "hauteur" => 600],
        ["prefix" => "md", "largeur" => 400, "hauteur" => 400],
        ["prefix" => "sm", "largeur" => 150, "hauteur" => 150],
    ];

    foreach ($tabTailles as $taille) {
        // Traitement d'image

        switch (strtolower($extension)) {
            case "gif":
                $imgSource = imagecreatefromgif($uploadPath . $filename);
                break;
            case "png":
                $imgSource = imagecreatefrompng($uploadPath . $filename);
                break;
            case "jpg":
            case "jpeg":
                $imgSource = imagecreatefromjpeg($uploadPath . $filename);
                break;
            default:
                unlink($uploadPath . $filename);
                showError("Format de fichier non autorisé");
                $sql = "UPDATE table_product SET product_image=null
                WHERE product_id = :product_id";

                $stmt = $db->prepare($sql);
                $stmt->bindValue(":product_id", $_POST['product_id'] > 0 ? $_POST['product_id'] : $db->lastInsertId());
                $stmt->execute();
                exit();
        }

        $sizes = getimagesize($uploadPath . $filename);
        $imgSourceLargeur = $sizes[0];
        $imgSourceHauteur = $sizes[1];

        $imgPrefix = $taille['prefix'];
        $imgDestLargeur = $taille['largeur'];
        $imgDestHauteur = $taille['hauteur'];
        $imageSourceZoneX = 0;
        $imageSourceZoneY = 0;
        $imgSourceZoneLargeur = $imgSourceLargeur;
        $imgSourceZoneHauteur = $imgSourceHauteur;

        if ($imgDestLargeur == $imgDestHauteur) {
            // Crop
            if ($imgSourceLargeur > $imgSourceHauteur) {
                // format paysage
                $imageSourceZoneX = ($imgSourceLargeur - $imgSourceHauteur) / 2;
                $imgSourceZoneLargeur = $imgSourceHauteur;
            } else {
                // format portrait
                $imageSourceZoneY = ($imgSourceHauteur - $imgSourceLargeur) / 2;
                $imgSourceZoneHauteur = $imgSourceLargeur;
            }
        } else {
            // Resize
            if ($imgSourceLargeur > $imgSourceHauteur) {
                // format paysage
                $imgDestHauteur = ($imgSourceHauteur * $imgDestLargeur) / $imgSourceLargeur;
            } else {
                // format portrait
                $imgDestLargeur = ($imgSourceLargeur * $imgDestHauteur) / $imgSourceHauteur;
            }
        }

        $imgDest = imagecreatetruecolor($imgDestLargeur, $imgDestHauteur); // Créer une image vierge à la taille souhaitée

        // Transparence
        imagesavealpha($imgDest, true);
        $trans_colour = imagecolorallocatealpha($imgDest, 0, 0, 0, 127);
        imagefill($imgDest, 0, 0, $trans_colour);

        // Copie de l'image source dans l'image vierge
        imagecopyresampled(
            $imgDest,
            $imgSource,
            0,
            0,
            $imageSourceZoneX,
            $imageSourceZoneY,
            $imgDestLargeur,
            $imgDestHauteur,
            $imgSourceZoneLargeur,
            $imgSourceZoneHauteur
        );

        // Création du nouveau fichier

        switch (strtolower($extension)) {
            case "gif":
                imagecolortransparent($imgDest, $trans_colour); // Transparence
                imagegif($imgDest, $uploadPath . $imgPrefix . "_" . $filename);
                imagegif($imgDest, $uploadPath . $filename);
                break;
            case "png":
                imagepng($imgDest, $uploadPath . $imgPrefix . "_" . $filename, 5, PNG_ALL_FILTERS);
                imagepng($imgDest, $uploadPath . $filename);
                break;
            case "jpg":
            case "jpeg":
                imagejpeg($imgDest, $uploadPath . $imgPrefix . "_" . $filename, 97);
                imagejpeg($imgDest, $uploadPath . $filename);
                break;
        }
    }

    // Suppression de l'image source

    unlink($uploadPath . $filename);
}

// header("Location:index.php");

// TODO
// Vérifier si l'extension n'a pas été renommée et qu'elle coincide avec l'extension du fichier
// Ne pas resize l'image si elle est plus petite que la taille souhaitée
// Vérifier si l'image doit être affichée format paysage ou portrait