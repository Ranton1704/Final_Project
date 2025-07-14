<?php
require_once 'function.php';
$conn = dbconnect();

$idCategorie = intval($_POST['id_categorie']);
$nomImageCustom = htmlspecialchars(trim($_POST['nom_image_custom']));
$image = $_FILES['image'];

if ($image['error'] === 0 && is_uploaded_file($image['tmp_name'])) {
    $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
    $fichierFinal = uniqid('img_', true) . '.' . strtolower($ext);
    $destination = '../uploads/' . $fichierFinal;

    if (move_uploaded_file($image['tmp_name'], $destination)) {
        $stmt = mysqli_prepare($conn, "INSERT INTO Cat_images_categorie (id_categorie, nom_image, fichier_image) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'iss', $idCategorie, $nomImageCustom, $fichierFinal);
        mysqli_stmt_execute($stmt);
        echo "✅ Image enregistrée avec succès.";
    } else {
        echo "❌ Erreur lors du téléchargement de l’image.";
    }
} else {
    echo "❌ Aucun fichier valide.";
}
?>
