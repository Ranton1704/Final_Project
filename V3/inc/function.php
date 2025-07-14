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
    

    if (!isset($_SESSION['id_membre'])) {
        header("Location: ../page/Login.php");
        exit;
    }

    $conn = dbconnect();

    $catResult = mysqli_query($conn, "SELECT * FROM Cat_categorie_objet");
    $categories = [];
    if ($catResult) {
        while ($row = mysqli_fetch_assoc($catResult)) {
            $categories[] = $row;
        }
    }

    $filtre = isset($_GET['categorie']) && is_numeric($_GET['categorie']) ? intval($_GET['categorie']) : '';
    $objets = [];

    if ($filtre !== '') {
        $stmt = mysqli_prepare($conn, "
            SELECT o.*, c.nom_categorie, m.Nom AS nom_proprietaire
            FROM Cat_objet o
            JOIN Cat_categorie_objet c ON o.id_categorie = c.id_categorie
            JOIN Cat_Membres m ON o.id_membre = m.id_Membre
            WHERE o.id_categorie = ?
        ");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'i', $filtre);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
        }
    } else {
        $result = mysqli_query($conn, "
            SELECT o.*, c.nom_categorie, m.Nom AS nom_proprietaire
            FROM Cat_objet o
            JOIN Cat_categorie_objet c ON o.id_categorie = c.id_categorie
            JOIN Cat_Membres m ON o.id_membre = m.id_Membre
        ");
    }

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $objets[] = $row;
        }
    }

    return [
        'conn' => $conn,
        'categories' => $categories,
        'filtre' => $filtre,
        'objets' => $objets
    ];
}

?>
