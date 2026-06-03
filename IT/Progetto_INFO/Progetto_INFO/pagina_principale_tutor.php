<?php
session_start();

$pi = $_SESSION["pi"] ?? null;
$tutor = [];

if (isset($pi)) {
    $conn = new mysqli("localhost", "root", "", "GestioneFSL");
    
    if (!$conn->connect_error) {
        $pi_safe = $conn->real_escape_string($pi);
        $querySQL = "SELECT * FROM Tutor_scolastico WHERE CF_TS = '$pi_safe'";
        $ris = $conn->query($querySQL);
        
        if ($ris && $ris->num_rows > 0) {
            $row = $ris->fetch_assoc();
            $tutor = [
                "CF_TS" => $row['CF_TS'],
                "nome" => $row['nome'],
                "cognome" => $row['cognome'],
                "email" => $row['email']
            ];
        }
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <title>Il tuo account - Tutor</title>
</head>
<body style="background-color: white;">
    <nav>
        <div class="logo"><img src="Immagini/logoFSL.png" style="height: 90px; width: 90px;"></div>
        <div class="name" id="greeting"><h2 style="font-family: Inter; font-size: 15px; color: black;"></h2></div>
        <ul>
            <li><a href="homepage-gestionePCTO.php">Home</a></li>
            <li><a><?php 
                if(!empty($tutor)) {
                    echo htmlspecialchars($tutor['nome'] . ' ' . $tutor['cognome']);
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
        <?php if (!empty($tutor)): ?>
            <div class="profile-card" style="background: #f9f9f9; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 30px;">
                <h2 style="margin-top: 0; color: #333;">Dati del Profilo</h2>
                <hr style="border: 0; height: 1px; background: #eee; margin-bottom: 20px;">
                <p><strong>Nome:</strong> <?php echo htmlspecialchars($tutor['nome']); ?></p>
                <p><strong>Cognome:</strong> <?php echo htmlspecialchars($tutor['cognome']); ?></p>
                <p><strong>Codice Fiscale:</strong> <?php echo htmlspecialchars($tutor['CF_TS']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($tutor['email']); ?></p>
            </div>
        <?php else: ?>
            <p style="color: red; text-align: center;">Nessun dato trovato. Effettua il login.</p>
        <?php endif; ?>

        <?php
            // studenti assegnati
            $studenti_assegnati = [];
            if(!empty($tutor['CF_TS'])){
                $conn = new mysqli("localhost", "root", "", "GestioneFSL");
                if (!$conn->connect_error) {
                    $cf_tutor_safe = $conn->real_escape_string($tutor['CF_TS']);
                    $querySQL = "SELECT * FROM Studente WHERE CF_TS = '$cf_tutor_safe'";
                    $ris = $conn->query($querySQL);
                    
                    if($ris && $ris->num_rows > 0){
                        while($row = $ris->fetch_assoc()){
                            // Nota le parentesi quadre [] per aggiungere elementi alla lista
                            $studenti_assegnati[] = [
                                "CF_S"             => $row['CF_S'],
                                "nome"             => $row['nome'],
                                "cognome"          => $row['cognome'],
                                "data_nascita"     => $row['data_nascita'],
                                "classe"           => $row['classe'],
                                "indirizzo_studi"  => $row['indirizzo_studi'],
                                "telefono"         => $row['telefono'],
                                "email"            => $row['email'],
                                "competenze"       => $row['competenze'],
                                "CF_TS"            => $row['CF_TS']
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
                <table style="width: 100%; border-collapse: collapse; background: white; min-width: 600px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
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