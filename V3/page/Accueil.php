<?php
require_once '../inc/function.php';
session_start();

$init = initialiser();
$conn = $init['conn'];
$categories = $init['categories'];
$filtre = $init['filtre'];
$objets = $init['objets'];

if (
    isset($_POST['emprunter'], $_POST['id_objet'], $_POST['duree'])
    && isset($_SESSION['id_membre'])
) {
    $id_objet = intval($_POST['id_objet']);
    $duree = intval($_POST['duree']);
    $id_membre = intval($_SESSION['id_membre']);

    $req = mysqli_prepare($conn, "SELECT 1 FROM Cat_emprunt WHERE id_objet=? AND date_retour IS NULL");
    mysqli_stmt_bind_param($req, 'i', $id_objet);
    mysqli_stmt_execute($req);
    $res = mysqli_stmt_get_result($req);
    if (mysqli_fetch_assoc($res)) {
        $emprunt_error = "Cet objet est déjà emprunté.";
    } else {
        $date_emprunt = date('Y-m-d');
        $date_retour_prevu = date('Y-m-d', strtotime("+$duree days"));
        $insert = mysqli_prepare($conn, "INSERT INTO Cat_emprunt (id_objet, id_membre, date_emprunt, date_retour, date_retour_prevu) VALUES (?, ?, ?, NULL, ?)");
        mysqli_stmt_bind_param($insert, 'iiss', $id_objet, $id_membre, $date_emprunt, $date_retour_prevu);
        mysqli_stmt_execute($insert);
        header("Location: ".$_SERVER['REQUEST_URI']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - Liste des Objets</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --main-color: #ff3c3c;
            --bg-color: #0d0d0d;
            --card-color: #1a1a1a;
            --text-color: #ffffff;
            --text-muted: #cccccc;
            --input-border: #ff3c3c;
            --radius: 18px;
            --shadow: 0 0 30px rgba(255, 60, 60, 0.25);
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--bg-color), #1a1a1a);
            color: var(--text-color);
            padding: 30px 20px;
            min-height: 100vh;
        }
        .error-message { color: #ff3c3c; margin-bottom: 15px; font-weight: bold; }
        .statut.emprunte { color: #ff3c3c; }
        .statut.disponible { color: #44bb44; }
    </style>
</head>
<body>

<?php if (isset($emprunt_error)): ?>
    <div class="error-message"><?= htmlspecialchars($emprunt_error) ?></div>
<?php endif; ?>

<div class="welcome">
    <h1>Bienvenue <?= htmlspecialchars($_SESSION['Nom']) ?> !</h1>
    <a class="btn-deconnexion" href="../inc/deconnexion.php">
        <i class="fas fa-sign-out-alt"></i> Se déconnecter
    </a>
</div>

<form method="get" class="filtre-box">
    <label for="categorie">Filtrer par catégorie :</label>
    <select name="categorie" id="categorie">
        <option value="">-- Toutes --</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id_categorie'] ?>" <?= ($filtre == $cat['id_categorie']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['nom_categorie']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit" class="btn-filtrer">Filtrer</button>
</form>

<div class="page-layout">

    <div class="formulaire-ajout">
        <h2>Ajouter une image par catégorie</h2>
        <form action="ajouter_image_categorie.php" method="post" enctype="multipart/form-data">
            <label>Catégorie :</label><br>
            <select name="id_categorie" required>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id_categorie'] ?>"><?= htmlspecialchars($cat['nom_categorie']) ?></option>
                <?php endforeach; ?>
            </select><br><br>

            <label>Nom de l’image (ex : rasoir, perceuse...):</label><br>
            <input type="text" name="nom_image_custom" placeholder="Entrez un nom descriptif" required><br><br>

            <label>Image :</label><br>
            <input type="file" name="image" accept="image/*" required><br><br>

            <input type="submit" value="Ajouter l’image">
        </form>
    </div>

    <div class="liste-objets">
        <h2>Objets disponibles</h2>
        <div class="container">
            <?php if (empty($objets)): ?>
                <p>Aucun objet trouvé pour cette catégorie.</p>
            <?php endif; ?>

            <?php foreach ($objets as $obj): ?>
                <div class="card">
                    <?php
                    $stmt = mysqli_prepare($conn, "SELECT fichier_image, nom_image FROM Cat_images_categorie WHERE id_categorie = ? LIMIT 1");
                    mysqli_stmt_bind_param($stmt, 'i', $obj['id_categorie']);
                    mysqli_stmt_execute($stmt);
                    $resImg = mysqli_stmt_get_result($stmt);
                    $image = mysqli_fetch_assoc($resImg);

                    if ($image && !empty($image['fichier_image'])) {
                        echo '<img src="../uploads/' . htmlspecialchars($image['fichier_image']) . '" alt="' . htmlspecialchars($image['nom_image']) . '" style="width:100px; height:auto; margin-bottom:10px;">';
                    }

                    $stmtStatut = mysqli_prepare($conn, "SELECT date_retour, date_retour_prevu FROM Cat_emprunt WHERE id_objet = ? ORDER BY id_emprunt DESC LIMIT 1");
                    mysqli_stmt_bind_param($stmtStatut, 'i', $obj['id_objet']);
                    mysqli_stmt_execute($stmtStatut);
                    $resStatut = mysqli_stmt_get_result($stmtStatut);
                    $statutData = mysqli_fetch_assoc($resStatut);

                    $isEmprunte = ($statutData && $statutData['date_retour'] === null);
                    $dateRetourPrevu = $statutData['date_retour_prevu'] ?? null;
                    ?>

                    <h3><?= htmlspecialchars($obj['nom_objet']) ?></h3>
                    <p><strong>Catégorie :</strong> <?= htmlspecialchars($obj['nom_categorie']) ?></p>
                    <p><strong>Propriétaire :</strong> <?= htmlspecialchars($obj['nom_proprietaire']) ?></p>

                    <?php if (!$isEmprunte): ?>
                        <form method="post" action="">
                            <input type="hidden" name="id_objet" value="<?= $obj['id_objet'] ?>">
                            <label for="duree_<?= $obj['id_objet'] ?>">Durée (jours) :</label>
                            <input type="number" min="1" max="30" name="duree" id="duree_<?= $obj['id_objet'] ?>" required>
                            <button type="submit" name="emprunter" class="btn">Emprunter</button>
                        </form>
                    <?php endif; ?>

                    <p class="statut <?= $isEmprunte ? 'emprunte' : 'disponible' ?>">
                        <?= $isEmprunte ? 'Emprunté' : 'Disponible' ?>
                        <?php if ($isEmprunte && $dateRetourPrevu): ?>
                            <br><small>Date de retour prévue : <?= date('d/m/Y', strtotime($dateRetourPrevu)) ?></small>
                        <?php endif; ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>
</body>
</html>