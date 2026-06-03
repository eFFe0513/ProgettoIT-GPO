<?php
session_start();

$pi = $_SESSION["pi"] ?? null;
$azienda = [];

if (isset($pi)) {
    $conn = new mysqli("localhost", "root", "", "GestioneFSL");
    
    if (!$conn->connect_error) {
        $pi_safe = $conn->real_escape_string($pi);
        $querySQL = "SELECT * FROM Azienda WHERE PI = '$pi_safe'";
        $ris = $conn->query($querySQL);
        
        if ($ris && $ris->num_rows > 0) {
            $row = $ris->fetch_assoc();
            $azienda = [
                "pi" => $row['PI'],
                "rs" => $row['ragione_sociale'],
                "resp" => $row['responsabile'],
                "email" => $row['email'],
                "settore" => $row['settore'],
                "atc" => $row['codice_ATECO'],
                "tel" => $row['telefono']
            ];
        }
    }
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit_tutor"])) {

        $CF_TA = $_POST["cf"];
        $PI = $pi;
        $nome = $_POST["name"];
        $cognome = $_POST["surname"];
        $inq = $_POST["inq"];
        $comp = $_POST["comp"];
        $esp = $_POST["esp"];
        $email = $_POST["email"];
        $tel = $_POST["tel"];

        if (!empty($CF_TA) && !empty($PI) && !empty($nome) && !empty($cognome) && !empty($comp) && !empty($esp) && !empty($email) && !empty($tel)) {

            $insert = "INSERT INTO Tutor_aziendale (CF_TA, PI, nome, cognome, inquadramento, competenze, esperienze, email, telefono)
                    VALUES ('$CF_TA', '$PI', '$nome', '$cognome', '$inq', '$comp', '$esp', '$email', '$tel')";
            $conn->query($insert);
        }
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit_attivita"])) {

        $titolo = $_POST['titolo'];
        $descrizione = $_POST['descrizione'];
        $periodo_i = $_POST['periodo_i'];
        $periodo_f = $_POST['periodo_f'];
        $periodo = $_POST['periodo'];
        $orario_i = $_POST['orario_i'];
        $orario_f = $_POST['orario_f'];
        $att_oggetto = $_POST['att_oggetto'];
        $max_studenti = $_POST['max_studenti'];
        $competenze_ric = $_POST['competenze_ric'];
        $ambito = $_POST['ambito'];
        $PI2 = $pi;
        $CF_TA1 = NULL; // se deve essere NULL nel DB

        if (!empty($titolo) && !empty($descrizione) && !empty($periodo_i) && !empty($periodo_f) && !empty($periodo) &&
            !empty($orario_i) && !empty($orario_f) && !empty($att_oggetto) && !empty($max_studenti) &&
            !empty($competenze_ric) && !empty($ambito) && !empty($PI2)) {

            $insert2 = "INSERT INTO Attivita (titolo, descrizione, periodo_i, periodo_f, periodo, orario_i, orario_f, att_oggetto, max_studenti, competenze_ric, ambito, PI, CF_TA)
                        VALUES ('$titolo', '$descrizione', '$periodo_i', '$periodo_f', '$periodo', '$orario_i', '$orario_f', '$att_oggetto', '$max_studenti', '$competenze_ric', '$ambito', '$PI2', NULL)";
            $conn->query($insert2);
        }
    }


}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <title>Il tuo account - Azienda</title>
    <style>
        a{
            text-decoration: none;
            color:rgb(68, 184, 85);
        }
        .main-scritta{
            font-size: 35px;
            color: black;
            font-family: calibri;
            font-weight: bold;
        }
        .secondary-scritta{
            font-size: 15px;
            color: rgb(178, 178, 178);
            font-family: calibri;
            font-weight: bold;
        }
        .container_register{
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            gap: 20px;
            height: auto;
            width: 50%;
            padding: 40px 5px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        }
        form{
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            flex-direction: column;
            width: 100%;
        }
        .label-wrapper{
            width: 70%;
            display: flex;
            align-items: center;
            justify-content: left;
            font-family: calibri;
            color: rgb(155, 155, 155);
        }
        input[type="text"], input[type="password"], input[type="email"], input[type="number"]{
            cursor: pointer;
            width: 70%;
            height: 30px;
            background-color: white;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
            border-radius: 10px;
            font-family: calibri;
            border: 0px;
            padding: 10px 10px;
            transition: transform 0.2s ease-in-out;
        }
        input[type="text"]:hover, input[type="password"]:hover,  input[type="email"]:hover, input[type="submit"]:hover, input[type="number"]:hover{
            transform: scale(1.04);
        }
        input[type="submit"]{
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            width: 70%;
            height: 40px;
            background-color:rgb(68, 184, 85);
            padding: 5px 20px;
            font-family: calibri;
            font-size: 18px;
            color: white;
            border-radius: 10px;
            border: 0px;
            transition: transform 0.2s ease-in-out;
        }
        .merger{
            padding: 0px 0px;
            display: flex;
            flex-direction: row;
            width: 70%;
        }
    </style>
</head>
<body style="background-color: white;">
    <nav>
        <div class="logo"><img src="Immagini/logoFSL.png" style="height: 90px; width: 90px;"></div>
        <div class="name" id="greeting"><h2 style="font-family: Inter; font-size: 15px; color: black;"></h2></div>
        <ul>
            <li><a href="homepage-gestionePCTO.php">Home</a></li>
            <li><a><?php 
                if(!empty($azienda)) {
                    echo htmlspecialchars($azienda['rs']);
                } else {
                    echo "Utente non autenticato";
                }
                ?></a></li>
            <li><a href="FAQ.html">FAQ</a></li>
        </ul>
        <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </nav>

    <main style="padding: 40px; font-family: Inter, sans-serif; max-width: 1000px; margin: 0 auto;">
        <?php if (!empty($azienda)): ?>
            <div class="profile-card" style="background: #f9f9f9; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 30px;">
                <h2 style="margin-top: 0; color: #333;">Dati del Profilo</h2>
                <hr style="border: 0; height: 1px; background: #eee; margin-bottom: 20px;">

                <p style="font-size: 30px; font-weight: bold;"><?php echo htmlspecialchars($azienda['rs']); ?></p>

                <p> <strong>Partita IVA:</strong> <?php echo htmlspecialchars($azienda['pi']); ?></p>
                <p><strong>Responsabile:</strong> <?php echo htmlspecialchars($azienda['resp']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($azienda['email']); ?></p>
                <p><strong>Settore:</strong> <?php echo htmlspecialchars($azienda['settore']); ?></p>
                <p><strong>ATECO:</strong> <?php echo htmlspecialchars($azienda['atc']); ?></p>
                <p><strong>Telefono:</strong> <?php echo htmlspecialchars($azienda['tel']); ?></p>
            </div>
        <?php else: ?>
            <p style="color: red; text-align: center;">Nessun dato trovato. Effettua il login.</p>
        <?php endif; ?>

        <?php
            // RECUPERO DEGLI STUDENTI ASSEGNATI
            $studenti_assegnati = [];
            if (!empty($azienda['pi'])) {
                $conn = new mysqli("localhost", "root", "", "GestioneFSL");
                if (!$conn->connect_error) {
                    $pi_safe = $conn->real_escape_string($azienda['pi']);

                    $query = "SELECT *
                            FROM Studente
                            WHERE CF_S IN (
                                SELECT CF_S 
                                FROM Partecipa 
                                WHERE titolo IN (
                                    SELECT titolo 
                                    FROM Attivita
                                    WHERE PI = '$pi_safe'
                                )
                            )";
                    $ris = $conn->query($query);

                    if ($ris && $ris->num_rows > 0) {
                        while ($row = $ris->fetch_assoc()) {
                            $studenti_assegnati[] = [
                                "CF_S"            => $row['CF_S'],
                                "nome"            => $row['nome'],
                                "cognome"         => $row['cognome'],
                                "data_nascita"    => $row['data_nascita'],
                                "classe"          => $row['classe'],
                                "indirizzo_studi" => $row['indirizzo_studi'],
                                "telefono"        => $row['telefono'],
                                "email"           => $row['email'],
                                "competenze"      => $row['competenze'],
                                "CF_TS"           => $row['CF_TS'],
                            ];
                        }
                    }
                    $conn->close();
                }
            }
        ?>
        <?php
            // RECUPERO TUTOR_A
            $tutor_A = [];
            if(!empty($azienda['pi'])){
                $conn = new mysqli("localhost", "root", "", "GestioneFSL");
                if (!$conn->connect_error) {

                    $pi_safe = $conn->real_escape_string($azienda['pi']);
                    $query = "SELECT * FROM Tutor_aziendale WHERE PI = '$pi_safe'";
                    $ris2 = $conn->query($query);

                    if($ris2 && $ris2->num_rows > 0){
                        while($row = $ris2->fetch_assoc()){
                            // Nota le parentesi quadre [] per aggiungere elementi alla lista
                            $tutor_A[] = [
                                "CF_TA"             => $row['CF_TA'],
                                "nome"          => $row['nome'],
                                "cognome"          => $row['cognome'],
                                "inquadramento"     => $row['inquadramento'],
                                "telefono"         => $row['telefono'],
                                "email"            => $row['email'],
                            ];
                        }
                    }
                    $conn->close();
                }
            }
        ?>
        <?php
            // RECUPERO Attività
            $attivita = [];
            if(!empty($azienda['pi'])){
                $conn = new mysqli("localhost", "root", "", "GestioneFSL");
                if (!$conn->connect_error) {

                    $pi_safe = $conn->real_escape_string($azienda['pi']);
                    $query = "SELECT * FROM Attivita WHERE PI = '$pi_safe'";
                    $ris3 = $conn->query($query);

                    if($ris3 && $ris3->num_rows > 0){
                        while($row = $ris3->fetch_assoc()){
                            // Nota le parentesi quadre [] per aggiungere elementi alla lista
                            $attivita[] = [
                                "titolo"             => $row['titolo'],
                                "descrizione"          => $row['descrizione'],
                                "periodo_i"          => $row['periodo_i'],
                                "periodo_f"     => $row['periodo_f'],
                                "att_oggetto"         => $row['att_oggetto'],
                                "max-studenti"            => $row['max_studenti'],
                            ];
                        }
                    }
                    $conn->close();
                }
            }
        ?>

        <div style="margin-top: 20px; text-align: center;">
            <button id="btnMostraStudenti" onclick="toggleStudenti()" style="padding: 12px 24px; font-size: 16px; background-color: rgb(68, 184, 85); color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; transition: background 0.3s;">
                Visualizza Elenco Studenti
            </button>
        </div>

        <div id="tabellaStudentiContainer" style="display: none; margin-top: 30px; overflow-x: auto;">
            <h3 style="color: #333; margin-bottom: 15px;">Studenti Associati al tuo Profilo</h3>
            
            <?php if (!empty($studenti_assegnati)): ?>
                <table style="width: 100%; border-collapse: collapse; background: white; min-width: 600px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border-radius: 10px;">
                    <thead>
                        <tr style="background-color: #f2f2f2; text-align: left; border-bottom: 2px solid #ddd;">
                            <th style="padding: 12px; border: 1px solid #ddd;">Nome / Cognome</th>
                            <th style="padding: 12px; border: 1px solid #ddd;">CF Studente</th>
                            <th style="padding: 12px; border: 1px solid #ddd;">Classe & Indirizzo</th>
                            <th style="padding: 12px; border: 1px solid #ddd;">Contatti</th>
                            <th style="padding: 12px; border: 1px solid #ddd;">Competenze</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($studenti_assegnati as $studente): ?>
                            <tr style="border-bottom: 1px solid #ddd;">
                                <td style="padding: 12px; border: 1px solid #ddd;">
                                    <strong><?php echo htmlspecialchars($studente['nome'] . " " . $studente['cognome']); ?></strong><br>
                                    <small style="color:#666;">Nato il: <?php echo htmlspecialchars($studente['data_nascita']); ?></small>
                                </td>
                                <td style="padding: 12px; border: 1px solid #ddd; font-family: monospace; font-size: 13px;">
                                    <?php echo htmlspecialchars($studente['CF_S']); ?>
                                </td>
                                <td style="padding: 12px; border: 1px solid #ddd;">
                                    <?php echo htmlspecialchars($studente['classe'] . " - " . $studente['indirizzo_studi']); ?>
                                </td>
                                <td style="padding: 12px; border: 1px solid #ddd; font-size: 14px;">
                                    <?php echo htmlspecialchars($studente['telefono']); ?><br>
                                    <?php echo htmlspecialchars($studente['email']); ?>
                                </td>
                                <td style="padding: 12px; border: 1px solid #ddd; font-size: 14px; max-width: 200px; word-wrap: break-word;">
                                    <?php echo nl2br(htmlspecialchars($studente['competenze'])); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color: #666; font-style: italic; text-align: center; background: #f9f9f9; padding: 20px; border-radius: 5px;">
                    Al momento non hai nessuno studente assegnato.
                </p>
            <?php endif; ?>
        </div>

        <div style="margin-top: 30px; overflow-x: auto;">
            <h3 style="color: #333; margin-bottom: 15px;">Tutor associati alla tua azienda</h3>
            
            <?php if (!empty($tutor_A)): ?>
                <table style="width: 100%; border-collapse: collapse; background: white; min-width: 600px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border-radius: 10px;">
                    <thead>
                        <tr style="background-color: #f2f2f2; text-align: left; border-bottom: 2px solid #ddd;">
                            <th style="padding: 12px; border: 1px solid #ddd;">Nome / Cognome</th>
                            <th style="padding: 12px; border: 1px solid #ddd;">Settore</th>
                            <th style="padding: 12px; border: 1px solid #ddd;">Contatti</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tutor_A as $tutor): ?>
                            <tr style="border-bottom: 1px solid #ddd;">
                                <td style="padding: 12px; border: 1px solid #ddd;">
                                    <strong><?php echo htmlspecialchars($tutor['nome'] . " " . $tutor['cognome']); ?></strong><br>
                                    <small style="color:#666;">Settore: <?php echo htmlspecialchars($tutor['inquadramento']); ?></small>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($tutor['inquadramento']); ?></strong><br>
                                </td>
                                <td style="padding: 12px; border: 1px solid #ddd; font-size: 14px;">
                                    <?php echo htmlspecialchars($tutor['telefono']); ?><br>
                                    <?php echo htmlspecialchars($tutor['email']); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color: #666; font-style: italic; text-align: center; background: #f9f9f9; padding: 20px; border-radius: 5px;">
                    Al momento non hai nessun tutor aziendale
                </p>
            <?php endif; ?>
        </div>

        <div style="margin-top: 20px; text-align: center;">
            <button id="btnAddTutor" onclick="toggleTutorRegister()" style="padding: 12px 24px; font-size: 16px; background-color: rgb(68, 184, 85); color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; transition: background 0.3s;">
                Aggiungi tutor
            </button>
        </div>

        <div style="margin-top: 30px; overflow-x: auto;">
            <h3 style="color: #333; margin-bottom: 15px;">Attivita associate alla tua azienda</h3>
            
            <?php if (!empty($attivita)): ?>
                <table style="width: 100%; border-collapse: collapse; background: white; min-width: 600px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border-radius: 10px;">
                    <thead>
                        <tr style="background-color: #f2f2f2; text-align: left; border-bottom: 2px solid #ddd;">
                            <th style="padding: 12px; border: 1px solid #ddd;">titolo</th>
                            <th style="padding: 12px; border: 1px solid #ddd;">periodo_i</th>
                            <th style="padding: 12px; border: 1px solid #ddd;">periodo_f</th>
                            <th style="padding: 12px; border: 1px solid #ddd;">att_oggetto</th>
                            <th style="padding: 12px; border: 1px solid #ddd;">max_studenti</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($attivita as $a): ?>
                            <tr style="border-bottom: 1px solid #ddd;">
                                <td style="padding: 12px; border: 1px solid #ddd;">
                                    <strong><?php echo htmlspecialchars($a['titolo']); ?></strong>
                                    <!-- rimuovi da qui il <small> con max-studenti -->
                                </td>
                                <td style="padding: 12px; border: 1px solid #ddd;">
                                    <?php echo htmlspecialchars($a['periodo_i']); ?>
                                </td>
                                <td style="padding: 12px; border: 1px solid #ddd;">
                                    <?php echo htmlspecialchars($a['periodo_f']); ?>
                                </td>
                                <td style="padding: 12px; border: 1px solid #ddd;">
                                    <?php echo htmlspecialchars($a['att_oggetto']); ?><br>
                                    <?php echo htmlspecialchars($a['descrizione']); ?>
                                </td>
                                <!-- NUOVA CELLA per max_studenti -->
                                <td style="padding: 12px; border: 1px solid #ddd;">
                                    <?php echo htmlspecialchars($a['max-studenti']); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color: #666; font-style: italic; text-align: center; background: #f9f9f9; padding: 20px; border-radius: 5px;">
                    Al momento non hai nessuna attività
                </p>
            <?php endif; ?>
        </div>

        <div style="margin-top: 20px; text-align: center;">
            <button id="btnAddAtt" onclick="toggleAttRegister()" style="padding: 12px 24px; font-size: 16px; background-color: rgb(68, 184, 85); color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; transition: background 0.3s;">
                Aggiungi attività
            </button>
        </div>

        <div id="tutorRegister" align="center" style="display: none; margin: 10px;">
            <div class="container_register">
                <h1 class="main-scritta">
                    Aggiungi Tutor
                </h1>
                <form action="pagina_principale_azienda.php" method="POST">
                    <div class="label-wrapper">
                        <label for="cf">
                            Codice fiscale
                        </label>
                    </div>
                    <input type="text" id="cf" name="cf" placeholder="Il codice fiscale" required>

                    <div class="label-wrapper">
                        <label for="name">
                            nome
                        </label>
                    </div>
                    <input type="text" id="pi" name="name" placeholder="Il nome" required>

                    <div class="label-wrapper">
                        <label for="surname">
                            Cognome
                        </label>
                    </div>
                    <input type="text" id="surname" name="surname" placeholder="L'email" required>

                    <div class="label-wrapper">
                        <label for="inq">
                            Inquadramento
                        </label>
                    </div>
                    <input type="text" id="inq" name="inq" placeholder="L'inquadramento" required>

                    <div class="label-wrapper">
                        <label for="comp">
                            Competenze
                        </label>
                    </div>
                    <input type="text" id="comp" name="comp" placeholder="Le competenze del tutor" required>

                    <div class="label-wrapper">
                        <label for="esp">
                            Esperienze
                        </label>
                    </div>
                    <input type="text" id="esp" name="esp" placeholder="Le esperienze del tutor" required>

                    <div class="label-wrapper">
                        <label for="email">
                            Email
                        </label>
                    </div>
                    <input type="email" id="email" name="email" placeholder="La tua email" required>

                    <div class="label-wrapper">
                        <label for="tel">
                            Telefono
                        </label>
                    </div>
                    <input type="text" id="tel" name="tel" placeholder="Numero di telefono" required>
                    
                    <input type="submit" name="submit_tutor">
                </form>
            </div>
        </div>
        <div id="attRegister" align="center" style="display: none; margin: 10px;">
            <div class="container_register">
                <h1 class="main-scritta">
                    Aggiungi attività
                </h1>
                <form action="pagina_principale_azienda.php" method="POST">
                    <div class="label-wrapper">
                        <label for="titolo">
                            Titolo
                        </label>
                    </div>
                    <input type="text" id="titolo" name="titolo" placeholder="Il titolo" required>

                    <div class="label-wrapper">
                        <label for="descrizione">
                            Descrizione
                        </label>
                    </div>
                    <input type="text" id="descrizione" name="descrizione" placeholder="La descrizione" required>

                    <div class="label-wrapper">
                        <label for="periodo_i">
                            Periodo iniziale
                        </label>
                    </div>
                    <input type="text" id="periodo_i" name="periodo_i" placeholder="Periodo iniziale" required>

                    <div class="label-wrapper">
                        <label for="periodo_f">
                            Periodo finale
                        </label>
                    </div>
                    <input type="text" id="periodo_f" name="periodo_f" placeholder="Periodo finale" required>

                    <div class="label-wrapper">
                        <label for="periodo">
                            Periodo
                        </label>
                    </div>
                    <input type="number" id="periodo" name="periodo" placeholder="Durata in giorni" required>

                    <div class="label-wrapper">
                        <label for="orario_i">
                            Orario iniziale
                        </label>
                    </div>
                    <input type="text" id="orario_i" name="orario_i" placeholder="Orario iniziale" required>

                    <div class="label-wrapper">
                        <label for="orario_f">
                            Orario finale
                        </label>
                    </div>
                    <input type="text" id="orario_f" name="orario_f" placeholder="Orario finale" required>

                    <div class="label-wrapper">
                        <label for="att_oggetto">
                            Attività oggetto
                        </label>
                    </div>
                    <input type="text" id="att_oggetto" name="att_oggetto" placeholder="Attività oggetto" required>

                    <div class="label-wrapper">
                        <label for="max_studenti">
                            Massimo studenti
                        </label>
                    </div>
                    <input type="number" id="max_studenti" name="max_studenti" placeholder="Massimo studenti dell'attività" required>
                    <div class="label-wrapper">
                        <label for="competenze_ric">
                            Competenze richieste
                        </label>
                    </div>
                    <input type="text" id="competenze_ric" name="competenze_ric" placeholder="Competenze richieste" required>
                    <div class="label-wrapper">
                        <label for="ambito">
                            Ambito
                        </label>
                    </div>
                    <input type="text" id="ambito" name="ambito" placeholder="Ambito attività" required>
                    
                    <input type="submit" name="submit_attivita">
                </form>
            </div>
        </div>
    </main>

    <script>
    function toggleStudenti() {
        var container = document.getElementById("tabellaStudentiContainer");
        var btn = document.getElementById("btnMostraStudenti");
        
        if (container.style.display === "none") {
            container.style.display = "block";
            btn.textContent = "Nascondi Elenco Studenti";
            btn.style.backgroundColor = "#6c757d";
        } else {
            container.style.display = "none";
            btn.textContent = "Visualizza Elenco Studenti";
            btn.style.backgroundColor = "rgb(68, 184, 85)";
        }
    }
    function toggleTutorRegister() {
        var container = document.getElementById("tutorRegister");
        var btn = document.getElementById("btnAddTutor");
        
        if (container.style.display === "none") {
            container.style.display = "block";
            btn.textContent = "Aggiungi Tutor aziendale";
            btn.style.backgroundColor = "#6c757d";
        } else {
            container.style.display = "none";
            btn.textContent = "Aggiungi Tutor aziendale";
            btn.style.backgroundColor = "rgb(68, 184, 85)";
        }
    }
    function toggleAttRegister() {
        var container = document.getElementById("attRegister");
        var btn = document.getElementById("btnAddAtt");
        
        if (container.style.display === "none") {
            container.style.display = "block";
            btn.textContent = "Aggiungi attività";
            btn.style.backgroundColor = "#6c757d";
        } else {
            container.style.display = "none";
            btn.textContent = "Aggiungi attività";
            btn.style.backgroundColor = "rgb(68, 184, 85)";
        }
    }
    </script>

    <footer class="footer">
        <div class="container">
            <div class="col">
                <h3>Azienda</h3>
                <a href="#sezionePrincipale.html">Prodotti</a>
                <a href="FAQ.html">FAQ</a>
                <a href="#">Lavora con noi</a>
            </div>
            <div class="col">
                <h3>Supporto</h3>
                <a href="#">FAQ</a>
                <a href="#">Assistenza</a>
                <a href="#">Privacy</a>
            </div>
            <div class="col">
                <h3>Social</h3>
                <a href="#">Instagram</a>
                <a href="#">LinkedIn</a>
                <a href="#">GitHub</a>
            </div>
        </div>
        <center><p class="copy" style="font-family: calibri;">©Copyright easyFSL s.r.l management company 2026</p></center>
    </footer>
</body>
</html>