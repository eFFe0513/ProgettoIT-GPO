<?php
    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $pi = $_POST["pi"];
        $name = $_POST["name"];
        $surname = $_POST["surname"];
        $email = $_POST["email"];

        if(isset($pi) && isset($name) && isset($surname) && isset($email)){
            $conn = new mysqli("localhost", "root", "", "gestioneFSL");
            $insertTutorS = "INSERT INTO Tutor_scolastico (CF_TS, nome, cognome, email) 
            VALUES ('$pi', '$name', '$surname', '$email');";
            $conn -> query($insertTutorS);

            //generazione token (chiave)
            $token = bin2hex(random_bytes(4));
            $insertChiavi = "INSERT INTO Chiavi (User_id, Tipo, Password_cod) 
            VALUES ('$pi', 'TutorS', '$token')";
            $conn -> query($insertChiavi);

            session_start();
            $_SESSION["pi"] = $pi;
            header("Location: pagina_principale_tutor.php");
            exit;
        }
    }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrati come tutor</title>
    <link rel="stylesheet" href="logins_style.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="container_register">
        <h1>
            Registrati
        </h1>
        <form action="tutor_register.php" method="POST">
            <div class="label-wrapper">
                <label for="pi">
                    Codice fiscale
                </label>
            </div>
            <input type="text" id="pi" name="pi" placeholder="Il codice fiscale" required>

            <div class="label-wrapper">
                <label for="name">
                    Nome
                </label>
            </div>
            <input type="text" id="pi" name="name" placeholder="Il tuo nome" required>

            <div class="label-wrapper">
                <label for="surname">
                    Cognome
                </label>
            </div>
            <input type="text" id="surname" name="surname" placeholder="La tua email" required>

            <div class="label-wrapper">
                <label for="email">
                    Email
                </label>
            </div>
            <input type="email" id="email" name="email" placeholder="La tua email" required>
            
            <input type="submit">
        </form>
    </div>
</body>
</html>