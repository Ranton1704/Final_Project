<?php
function dbconnect()
{
    static $connect = null;

    if ($connect === null) {
        $connect = mysqli_connect('localhost', 'root', '', 'Base');
        if (!$connect) {
            die('Erreur de connexion à la base de données : ' . mysqli_connect_error());
        }
        mysqli_set_charset($connect, 'utf8mb4');
    }

    return $connect;
}

function initialiser()
{
    session_start();

    if (!isset($_SESSION['id_membre'])) {
        header("Location: ../page/Login.php");
        exit;
    }

    $conn = dbconnect();

    // Récupération des catégories depuis Cat_categorie_objet
    $catResult = mysqli_query($conn, "SELECT * FROM Cat_categorie_objet");
    $categories = [];
    while ($row = mysqli_fetch_assoc($catResult)) {
        $categories[] = $row;
    }

    // Filtrage par catégorie
    $filtre = isset($_GET['categorie']) ? $_GET['categorie'] : '';
    $objets = [];

    if ($filtre !== '') {
        $stmt = mysqli_prepare($conn, "
            SELECT o.*, c.nom_categorie, m.Nom AS nom_proprietaire
            FROM Cat_objet o
            JOIN Cat_categorie_objet c ON o.id_categorie = c.id_categorie
            JOIN Cat_Membres m ON o.id_membre = m.id_Membre
            WHERE o.id_categorie = ?
        ");
        mysqli_stmt_bind_param($stmt, 'i', $filtre);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    } else {
        $result = mysqli_query($conn, "
            SELECT o.*, c.nom_categorie, m.Nom AS nom_proprietaire
            FROM Cat_objet o
            JOIN Cat_categorie_objet c ON o.id_categorie = c.id_categorie
            JOIN Cat_Membres m ON o.id_membre = m.id_Membre
        ");
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $objets[] = $row;
    }

    return [
        'conn' => $conn,
        'categories' => $categories,
        'filtre' => $filtre,
        'objets' => $objets
    ];
}
?>
