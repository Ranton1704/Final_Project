<?php
require_once 'function.php';
$conn = dbconnect();

// Récupération sécurisée des données
$idCategorie = isset($_POST['id_categorie']) ? intval($_POST['id_categorie']) : 0;
$nomImageCustom = isset($_POST['nom_image_custom']) ? (trim($_POST['nom_image_custom'])) : '';
$image = $_FILES['image'] ?? null;

// Vérification des champs requis
if ($idCategorie > 0 && $nomImageCustom !== '' && $image && $image['error'] === 0 && is_uploaded_file($image['tmp_name'])) {
    
    // Extension du fichier
    $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
    $ext = strtolower($ext);

    // Vérification type de fichier
    $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($ext, $allowedExts)) {
        echo "❌ Format de fichier non autorisé.";
        exit;
    }

    // Générer un nom unique pour le fichier
    $fichierFinal = uniqid('img_', true) . '.' . $ext;
    $destination = '../uploads/' . $fichierFinal;

    // Déplacer le fichier uploadé
    if (move_uploaded_file($image['tmp_name'], $destination)) {
        // Enregistrement en base
        $stmt = mysqli_prepare($conn, "INSERT INTO Cat_images_categorie (id_categorie, nom_image, fichier_image) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'iss', $idCategorie, $nomImageCustom, $fichierFinal);

        if (mysqli_stmt_execute($stmt)) {
            echo "✅ Image enregistrée avec succès.";
        } else {
            echo "❌ Erreur lors de l'enregistrement en base.";
        }
    } else {
        echo "❌ Échec du téléchargement de l’image.";
    }
} else {
    echo "❌ Données manquantes ou fichier invalide.";
}
?>
