<?php
    session_start();
    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $id = $_POST["Ref_ID"];
        $pw = $_POST["password"];

        if(isset($id) && isset($pw)){
            $conn = new mysqli("localhost", "root", "", "GestioneFSL");
            $querySQL = "SELECT * FROM Referente;";
            $ris = $conn ->query($querySQL);
            if($ris -> num_rows > 0){
                while($row = $ris ->fetch_assoc()){
                    if($id == $row['Ref_ID'] && $pw == $row['password']){
                        header("Location: elencoTotale_scuola.php");
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
    <title>Login dello studente</title>
    <link rel="stylesheet" href="logins_style.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="container">
        <h1>
            Accedi
        </h1>
        <form action="referente_login.php" method="POST">
            <div class="label-wrapper">
                <label for="Ref_ID">
                    Codice fiscale
                </label>
            </div>
            <input type="text" id="pi" name="Ref_ID" placeholder="Il codice fiscale" required>
            <div class="label-wrapper">
                <label for="password">
                    Password 
                </label>
            </div>
            <input type="password" id="key" name="password" placeholder="Password" required>
            <input type="submit">
        </form>
    </div>
</body>
</html>