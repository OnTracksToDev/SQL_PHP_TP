<?php date_default_timezone_set('Europe/Paris'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>User</title>
    <style>
        body {
            background-color: #333;
            color: lightgrey;
        }
    </style>
</head>

<body class="text-center">
    <?php
    //Connect DB
    try {
        $connection = new PDO("mysql:host=localhost;dbname=dwwn_soliDev", "root", "");
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (Exception $e) {
        die($erreur_sql = 'Erreur connect bd: ' . $e->getMessage());
    }
    ?>

    <hr>
    <h2> UPDATE USER</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="name">
        <br>
        <input type="text" name="firstName" placeholder="first name">
        <br>
        <input type="mail" name="mail" placeholder="mail">
        <br>
        <input type="submit" value="Modifier" name="Modifier">
    </form>
    <hr>


    <?php
    //UPDATE name, firstName, mail
    //if exist POST
    if (isset($_POST['Modifier'])) {
        // Récup données de POST
        strip_tags($_GET['id']);
        $firstName = strip_tags($_POST['firstName']);
        $name = strip_tags($_POST['name']);
        $mail = strip_tags($_POST['mail']);

        // REQUETE MAJ
        try {
            $sql = "UPDATE users SET firstName = ?, name = ?, mail = ?,  WHERE id = ?";
            $stmt = $connection->prepare($sql);
            $stmt->execute(array(
                $firstName,
                $name,
                $mail,
                strip_tags($_GET['id'])
            ));

    ?>
            <div class="alert alert-success" role="alert" style="width: 20rem;">
                <?php
                echo 'MISE A JOUR REUSSIE !'
                ?>
            </div>
    <?php
        } catch (Exception $e) {
            $sqlError = $e->getMessage();
        }
    } ?>




    <?php
    //requete UPDATE dateUpdate
    try {
        $sql = "UPDATE users SET dateUpdate=? WHERE id=?";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array(
            date('Y-m-d H:i:s'),
            $_GET['id']
        ));
    } catch (Exception $e) {
        $sqlError = $e->getMessage();
    }
    //  if error
    if (isset($sqlError)) {
        echo $sqlError;
    }
    ?>

    <?php
    // requete SELECT
    try {
        $sql = "SELECT * FROM users WHERE id=?";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array($_GET['id'])); //recupere ID 
    } catch (Exception $e) {
        $sqlError = $e->getMessage();
    }
    //  if error
    if (isset($sqlError)) {
        echo $sqlError;
    }
    //Construction des RESULTATS
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    /***************** DATES ************************** */
    $dateCreate = explode(' ', $user['dateCreate']);
    //print_r($dateCreate);
    $date = $dateCreate[0];
    $heure = $dateCreate[1];
    $dateInfos = explode('-', $date);
    $annee = $dateInfos[0];
    $mois = $dateInfos[1];
    $jour = $dateInfos[2];

    //Mois
    $mois_annee = array(1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Aout', 9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Decembre');
    //affiche date format JJ MOIS AAAA 
    echo 'Créer Le ' . $jour . ' ' . $mois_annee[$mois] . ' ' . $annee;
    echo '<hr>';
    //Jour 
    $jour_semaine = array(1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi', 7 => 'Dimanche',);
    echo 'N° du jour ' . $jour_semaine[date_format(date_create($user['dateCreate']), 'w')];
    $numeroJour = $jour_semaine[date_format(date_create($user['dateCreate']), 'w')];
    echo '<hr>';

    //affiche JOUR 00 Mois AAAA
    echo 'Le ' . $numeroJour . ' ' . $jour . ' ' . $mois_annee[$mois] . ' ' . $annee;
    echo '<hr>';


    /*****************************  USER CARD DETAILS ********************/
    //SI
    if (empty($user)) { ?>
        <div class="alert alert-danger" role="alert">
            UNKNOWN USER
        </div>
    <?php
        //SINON
    } else {
        $today = new DateTime(); // objet date actuelle
        $dateCreation = new DateTime($user['dateCreate']); // objet date de création
        $interval = $today->diff($dateCreation);
        $tempsEcoule = $interval->format('%y year, %m month, %d days, %h hours, %i minutes');
    ?>
        <div class="d-flex justify-content-center">
            <div class="card" style="width: 20rem;">
                <div class="card-body">
                    <h5 class="card-title">ID USER :<?= $user['id'] ?></h5>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">First Name : <?= $user['firstName'] ?></li>
                    <li class="list-group-item">Name : <?= $user['name'] ?></li>
                    <li class="list-group-item">Mail : <?= $user['mail'] ?></li>
                    <li class="list-group-item">Created Since : <?= $tempsEcoule ?></li>
                    <li class="list-group-item">Modif : <?= $user['dateUpdate'] ?></li>
                </ul>
                <div class="card-body">
                    <a href="index.php" class="card-link">Back</a>
                </div>
            </div>
        </div>
    <?php } ?>



    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

</body>

</html>