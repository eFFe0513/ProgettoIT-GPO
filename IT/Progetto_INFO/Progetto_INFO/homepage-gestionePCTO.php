<!DOCTYPE html>
<html>
    <head>
        <title>
            EasyFSL - Gestisci la Formazione Scuola Lavoro
        </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="e-drone_logo.png" type="image/png">
        <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    </head>
    <body align="center">
        <?php
        $conn = new mysqli("localhost", "root", "", "GestioneFSL");

        if ($conn->connect_errno) {
            echo "Errore di connessione con il database.";
        } else {

            $querySQL = "SELECT COUNT(*) FROM Azienda;";
            $ris = $conn->query($querySQL);

            if ($ris) {
                $row = $ris->fetch_row(); 
                $num = $row[0];
            }
        }
        ?>
        <nav>
            <div class="logo"><img src="Immagini/logoFSL.png" style="height: 90px; width: 90px;"></div>
            <div class="name" id="greeting"><h2 style="font-family: Inter; font-size: 15px; color: black;"></h2></div>
            <ul>
                <li><a href="">Home</a></li>
                <li><a onclick="document.getElementById('sezionePrincipale').scrollIntoView({behavior: 'smooth'});">Accedi</a></li>
                <li><a href="FAQ.html">FAQ</a></li>
            </ul>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </nav>
        
        <div class="mainContainer"> 
            <section class="sectionHP">
                <div class="mainDiv">
                    <div class="mainDivElement hidden-element">
                        <h1 align="left">
                            Scrivi il tuo percorso <br>FSL
                        </h1>
                    </div>
                    <div class="mainDivElement" align="left">
                        <h2>
                        Ragiona sul tuo campo d'interesse e scegli l'attività più adatta a te
                        </h2>
                    </div>
                    <div class="mainDivElement" align="left">
                        <button class="startButton" onclick="document.getElementById('sezionePrincipale').scrollIntoView({behavior: 'smooth'});">
                            <b>Inizia</b>
                        </button>
                    </div>
                </div>
            </section>
        </div>
        
        <div class="container">
            <div class="ml_description hidden-element">
                <h1>SCOPRI</h1>
                <h2>Unione di efficienza e dedizione</h2>
                <h3>
                    Da sempre dimostriamo ai nostri clienti professionalità nella consegna e nella cura dei nostri prodotti.
                    Acquista il tuo drone in tempi record.
                </h3>
                <button onclick="document.getElementById('sezionePrincipale').scrollIntoView({behavior: 'smooth'});">
                   Scopri di più
                </button>
            </div>

            <div class="ml_number hidden-element">
                <h1 class="counter" data-target="<?= $num ?>"></h1> 
                <h2>Aziende</h2>
            </div>
        </div>
        
        <div class="section">
            <h1>Un punto di incontro fra scuola e studente</h1>
            <h2>
                Seleziona la tua marca preferita dalla nostra piattaforma di E-commerce. E-drone è pensato per essere intuitivo e
                professionale, l'azienda garantisce rapidità in termini di consegna e qualità dei prodotti offerti.
            </h2>
            <div class="containerSection">
                <div class="S-element hidden-element">
                    <img src="Immagini/sicurezza.png" style="height: 100px; width: 100px;"> 
                    <h1>Sicurezza</h1>
                    <h3>I dati sensibili sono crittografati tramite metodi certificati.</h3>
                </div>
                <div class="S-element hidden-element">
                    <img src="Immagini/efficienza.png" style="height: 100px; width: 100px;">
                    <h1>Efficienza</h1>
                    <h3>Gli studenti identificano velocemente il campo d'interesse</h3>
                </div>
                <div class="S-element hidden-element">
                    <img src="Immagini/monitoraggio.png" style="height: 100px; width: 100px;">
                    <h1>Monitoraggio</h1>
                    <h3>L'istituto scolastico monitora costantemente il percorso FSL</h3>
                </div>
            </div>
        </div>
        
        <section class="sectionP" id="sezionePrincipale">
            <div>
                <h1 style="font-size: 30px; font-family: Inter; color: black;">
                    Seleziona il ruolo
                </h1>
            </div>
            <div class="card-collection">
                <div class="card-box" onclick="azienda_login_register()">
                    <h1>
                        Azienda
                    </h1>
                    <h2>
                        Aggiungi o accedi alla tua azienda
                    </h2>
                </div>
                <div class="card-box" onclick="studente_login_register()">
                    <h1>
                        Studente
                    </h1>
                    <h2>
                        Aggiungi o accedi alla tua azienda
                    </h2>
                </div>
                <div class="card-box" onclick="tutor_login_register()">
                    <h1>
                        Tutor
                    </h1>
                    <h2>
                        Monitora il percorso FSL dei tuoi studenti
                    </h2>
                </div>
            </div>
            <div>
                <h2 style="color: black;">
                    - Oppure -
                </h2>
            </div> 
            <div class="referente-card" onclick="referente_login()">
                <h2 style="color: white;">
                    Referente scolastico
                </h2>
            </div>
            
            <!-- STATUS per feedback -->
            <div id="status" style="margin-top: 30px; padding: 15px; font-family: Inter; font-size: 16px; border-radius: 10px;"></div>
        </section>
        
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
            <p class="copy" style="font-family: calibri;">©Copyright easyFSL s.r.l management company 2026</p>
        </footer>
    </body>
    <script src="script.js"></script>
    <script>
        function azienda_login_register(){
            window.location.href = "azienda_login.php";
        }
        function studente_login_register(){
            window.location.href = "studente_login.php";
        }
        function tutor_login_register(){
            window.location.href = "tutor_login.php";
        }
        function referente_login(){
            window.location.href = "referente_login.php";
        }
    </script>
</html>