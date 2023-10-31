<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <title>SQL</title>
    <style>
        body {
            background-color: #333;
            color: lightgrey;
        }
    </style>

</head>

<body class="text-center">
    <h1> My SQL</h1>
    <hr>
    <h2>ADD NEW USER</h2>

    <?php
    // connect DB
    try {
        $host = 'localhost';
        $db_name = 'dwwn_solidev';
        $login = 'root';
        $pass = '';

        $connection = new PDO("mysql:host=$host;dbname=$db_name", $login, $pass);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (Exception $e) {
        die($erreur_sql = 'Erreur connect bd: ' . $e->getMessage());
    }
    ?>

    <?php
    $bgMail = '';
    /************ INSERT NEW USER ************/
    if (isset($_POST['userNew'])) {
        print_r($_POST);
        echo '<hr>';
        //TEST MAIL
        $mail = strip_tags($_POST['mail']);
        if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            //echo 'email ok <hr>';
            //OK INSERT
            $bgMail = 'success';
            $msgMail = '<span class = "text-warning">Le mail ' . $mail . ' bon </span>';
            // requete INSERT NEW USER
            try {
                $sql = "INSERT INTO users SET name=?, firstName=?, mail=?, password=?";
                $stmt = $connection->prepare($sql);
                $stmt->execute(array(
                    strip_tags($_POST['name']),
                    strip_tags($_POST['firstName']),
                    $mail,
                    strip_tags($_POST['password'])

                ));
            } catch (Exception $e) {
                $sqlError = $e->getMessage();
            }
            //  if error
            if (isset($sqlError)) {
                echo $sqlError;
            }
        } else {
            //echo 'email pas ok <hr>';
            //NO INSERT
            $bgMail = 'warning';
            $msgMail = '<span class = "text-warning">Désolé, le mail ' . $mail . ' envoyé n\'est pas bon </span>';
        }
        if (isset($msgMail)) {
            echo $msgMail;
        }
    } ?>


    <div class="d-flex justify-content-center">
        <form method="POST">
            <input type="text" name="name" placeholder="name">
            <br>
            <input type="text" name="firstName" placeholder="first name">
            <br>
            <input type="mail" name="mail" placeholder="mail" class="bg-<?php echo $bgMail; ?>">
            <br>
            <input type="mail" name="password" placeholder="password">
            <br>
            <input type="submit" value="Add User" name="userNew">
        </form>
    </div>



    <hr>
    <h1>Users</h1>
    <?php
    /********************* CARDS USERS *********************/
    // requete SELECT ALL USERS
    try {
        $sql = "SELECT * FROM users";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array());
    } catch (Exception $e) {
        $sqlError = $e->getMessage();
    }
    //  if error
    if (isset($sqlError)) {
        echo $sqlError;
    }
    echo 'Number of USERS : ' . $stmt->rowCount(); ?>
    </div>
    <?php
    //  loop results
    while ($users = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '<hr>';
    ?>
        <div class="d-flex justify-content-center">
            <div class="card text-center" style="width: 20rem;">
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">First Name : <?= $users['firstName'] ?></li>
                        <li class="list-group-item">Name : <?= $users['name'] ?></li>
                        <li class="list-group-item">User created : <?= $users['dateCreate'] ?></li>
                    </ul>
                </div>
                <div class="card-body">
                    <a href="<?= 'userDetails.php?id=' . $users['id'] ?>" class="card-link ">View Profil</a>
                </div>
            </div>
        </div>

    <?php } ?>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>

</html>