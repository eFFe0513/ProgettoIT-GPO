<?php
    // ALLO STUDENTE VIENE ASSEGNATO UN TUTOR SCOLASTICO DAL REFERENTE
    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $pi = $_POST["pi"];
        $name = $_POST["name"];
        $surname = $_POST["surname"];
        $date = $_POST["date"];
        $class = $_POST["class"];
        $indirizzo = $_POST["indirizzo"];
        $tel = $_POST["tel"];
        $email = $_POST["email"];
        $competenze = $_POST["comp"];
        //la chiave secondaria viene posta a null

        if(isset($pi) && isset($name) && isset($surname) && isset($date) && isset($class) && isset($indirizzo) && isset($tel) && isset($email) && isset($competenze)){
            $conn = new mysqli("localhost", "root", "", "gestioneFSL");
            $insertStudente = "INSERT INTO Studente (CF_S, nome, cognome, data_nascita, classe, indirizzo_studi, telefono, email, competenze, CF_TS) 
            VALUES ('$pi', '$name', '$surname', '$date', '$class', '$indirizzo', '$tel', '$email', '$competenze', NULL);";
            $conn -> query($insertStudente);

            //generazione token (chiave)
            $token = bin2hex(random_bytes(4));
            $insertChiavi = "INSERT INTO Chiavi (User_id, Tipo, Password_cod) 
            VALUES ('$pi', 'Studente', '$token')";
            $conn -> query($insertChiavi);

            session_start();
            $_SESSION["pi"] = $pi;
            header("Location: pagina_principale_studente.php");
            exit;
        }
    }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrati come studente</title>
    <link rel="stylesheet" href="logins_style.css?v=<?php echo time(); ?>">
</head>
<body style="height: 150vh;">
    <div class="container_register">
        <h1>
            Registrati
        </h1>
        <form action="studente_register.php" method="POST">
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
            <input type="text" id="name" name="name" placeholder="Il tuo nome" required>

            <div class="label-wrapper">
                <label for="resp">
                    Cognome
                </label>
            </div>
            <input type="text" id="surname" name="surname" placeholder="Il tuo cognome" required>

            <div class="label-wrapper">
                <label for="date">
                    Data di nascita
                </label>
            </div>
            <input type="text" id="date" name="date" placeholder="La tua data di nascita" required>

            <div class="merger">
                <div style="display: flex; justify-content: center; align-items: center; gap: 10px; flex-direction: column; width: 50%; padding: 0px;">
                    <div class="label-wrapper">
                        <label for="class">
                            Classe
                        </label>
                    </div>
                    <input type="text" id="class" name="class" placeholder="La tua classe" required>
                </div>
                <div style="display: flex; justify-content: center; align-items: center; gap: 10px; flex-direction: column; width: 50%; padding: 0px;">
                    <div class="label-wrapper">
                        <label for="indirizzo">
                            Indirizzo di studi
                        </label>
                    </div>
                    <input type="text" id="indirizzo" name="indirizzo" placeholder="Il tuo indirizzo di studi" required>
                </div>
            </div>

            <div class="label-wrapper">
                <label for="email">
                    email
                </label>
            </div>
            <input type="email" id="email" name="email" placeholder="La tua email" required>

            <div class="label-wrapper">
                <label for="comp">
                    Competenze
                </label>
            </div>
            <input type="text" id="comp" name="comp" placeholder="Le tue competenze" required>

            <div class="label-wrapper">
                <label for="tel">
                    Telefono
                </label>
            </div>
            <input type="text" id="tel" name="tel" placeholder="Il tuo numero di telefono" required>
            
            <input type="submit">
        </form>
    </div>
</body>
</html>