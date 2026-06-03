<?php
    session_start();
    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $pi = $_POST["pi"];
        $name = $_POST["name"];
        $resp = $_POST["resp"];
        $email = $_POST["email"];
        $settore = $_POST["settore"];
        $atc = $_POST["atc"];
        $tel = $_POST["tel"];

        if(isset($pi) && isset($name) && isset($resp) && isset($email) && isset($settore) && isset($atc) && isset($tel)){
            $conn = new mysqli("localhost", "root", "", "gestioneFSL");
            $insertAzienda = "INSERT INTO Azienda (PI, ragione_sociale, responsabile, email, settore, codice_ATECO, telefono) 
            VALUES ('$pi', '$name', '$resp', '$email', '$settore', '$atc', '$tel');";
            $conn -> query($insertAzienda);

            //generazione token (chiave)
            $token = bin2hex(random_bytes(4));
            $insertChiavi = "INSERT INTO Chiavi (User_id, Tipo, Password_cod) 
            VALUES ('$pi', 'Azienda', '$token')";
            $conn -> query($insertChiavi);

            $_SESSION["pi"] = $pi;
            header("Location: pagina_principale_azienda.php");
            exit;
        }
    }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registra la tua azienda</title>
    <link rel="stylesheet" href="logins_style.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="container_register">
        <h1>
            Registrati
        </h1>
        <form action="azienda_register.php" method="POST">
            <div class="label-wrapper">
                <label for="pi">
                    Codice fiscale
                </label>
            </div>
            <input type="text" id="pi" name="pi" placeholder="Il codice fiscale" required>

            <div class="label-wrapper">
                <label for="name">
                    Ragione sociale
                </label>
            </div>
            <input type="text" id="pi" name="name" placeholder="Il nome della tua azienda" required>

            <div class="label-wrapper">
                <label for="resp">
                    Responsabile 
                </label>
            </div>
            <input type="text" id="resp" name="resp" placeholder="Il responsabile" required>

            <div class="label-wrapper">
                <label for="email">
                    E-mail 
                </label>
            </div>
            <input type="email" id="email" name="email" placeholder="E-mail organizzazione" required>

            <div class="label-wrapper">
                <label for="settore">
                    Settore
                </label>
            </div>
            <input type="text" id="settore" name="settore" placeholder="Settore operativo" required>

            <div class="merger">
                <div style="display: flex; justify-content: center; align-items: center; gap: 10px; flex-direction: column; width: 50%; padding: 0px;">
                    <div class="label-wrapper">
                        <label for="atc">
                            Codice ATECO
                        </label>
                    </div>
                    <input type="text" id="atc" name="atc" placeholder="Il codice ATECO" maxlength="6" required>
                </div>
                <div style="display: flex; justify-content: center; align-items: center; gap: 10px; flex-direction: column; width: 50%; padding: 0px;">
                    <div class="label-wrapper">
                        <label for="tel">
                            Telefono
                        </label>
                    </div>
                    <input type="text" id="tel" name="tel" placeholder="Numero di telefono" required>
                </div>
            </div>
            <input type="submit">
        </form>
    </div>
</body>
</html>