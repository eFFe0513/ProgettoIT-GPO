<?php
    session_start();
    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $pi = $_POST["pi"];
        $pw = $_POST["password"];

        if(isset($pi) && isset($pw) && !empty($pi) && !empty("pw")){
            $conn = new mysqli("localhost", "root", "", "GestioneFSL");
            $querySQL = "SELECT * FROM Chiavi WHERE Tipo = 'Azienda';";
            $ris = $conn ->query($querySQL);
            if($ris -> num_rows > 0){
                while($row = $ris ->fetch_assoc()){
                    if($pi == $row['User_id'] && $pw == $row['Password_cod']){
                        //accesso possibile a pagina successiva (elenco aziende + account azienda) tramite passaggio ID
                        $_SESSION["pi"] = $pi;
                        header("Location: pagina_principale_azienda.php");
                        exit;
                    }else{
                        continue;
                    }
                }
            }
        }
    }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login della tua azienda</title>
    <link rel="stylesheet" href="logins_style.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="container">
        <h1>
            Accedi
        </h1>
        <form action="azienda_login.php" method="POST">
            <div class="label-wrapper">
                <label for="pi">
                    PI o codice fiscale
                </label>
            </div>
            <input type="text" id="pi" name="pi" placeholder="Il codice fiscale" required>
            <div class="label-wrapper">
                <label for="password">
                    Password 
                </label>
            </div>
            <input type="password" id="key" name="password" placeholder="Password" required>
            <input type="submit">
        </form>
        <h3>
            Non hai un account?<a href="azienda_register.php"> Registrati</a>
        </h3>
    </div>
</body>
</html>