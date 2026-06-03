<?php
session_start();

$pi = $_SESSION["pi"] ?? null;

$studente = [];
$attivita = [];
$presente = false;
$azienda = [];

$conn = new mysqli("localhost", "root", "", "GestioneFSL");
if ($conn->connect_error) {
    die("Errore connessione: " . $conn->connect_error);
}

if ($pi) {
    $pi_safe = $conn->real_escape_string($pi);

    $query = "SELECT * FROM Studente WHERE CF_S = '$pi_safe'";
    $ris = $conn->query($query);

    if ($ris && $ris->num_rows > 0) {
        $row = $ris->fetch_assoc();

        $studente = [
            "cf" => $row['CF_S'],
            "nome" => $row['nome'],
            "cognome" => $row['cognome'],
            "data_nascita" => $row['data_nascita'],
            "classe" => $row['classe'],
            "indirizzo_studi" => $row['indirizzo_studi'],
            "tel" => $row['telefono'],
            "email" => $row['email'],
            "competenze" => $row['competenze'],
        ];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // COMMENTO
    if (!empty($_POST['nome']) && !empty($_POST['commento'])) {

        $commento = $_POST['commento'];

        $stmt = $conn->prepare("INSERT INTO Commento (testo, CF_S) VALUES (?, ?)");
        $stmt->bind_param("ss", $commento, $pi);
        $stmt->execute();
        $stmt->close();
    }

    // ATTIVITÀ
    if (!empty($_POST['titolo'])) {

        $titolo = $_POST['titolo'];

        $stmt = $conn->prepare("INSERT INTO Partecipa (CF_S, titolo) VALUES (?, ?)");
        $stmt->bind_param("ss", $pi, $titolo);
        $stmt->execute();
        $stmt->close();
    }
}

if (!empty($studente['cf'])) {

    $stmt = $conn->prepare("SELECT CF_S FROM Partecipa WHERE CF_S = ?");
    $stmt->bind_param("s", $studente['cf']);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $presente = true;

        $stmt2 = $conn->prepare("
            SELECT ragione_sociale, responsabile
            FROM Azienda
            WHERE PI IN (
                SELECT PI FROM Attivita
                WHERE titolo IN (
                    SELECT titolo FROM Partecipa WHERE CF_S = ?
                )
            )
            LIMIT 1
        ");

        $stmt2->bind_param("s", $studente['cf']);
        $stmt2->execute();
        $res2 = $stmt2->get_result();

        if ($row = $res2->fetch_assoc()) {
            $azienda = $row;
        }
    }
}


if (!$presente) {
    $res = $conn->query("SELECT * FROM Attivita");

    $attivita = [];
    while ($row = $res->fetch_assoc()) {
        $attivita[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
<title>Il tuo account - Studente</title>
</head>

<body style="background-color: white;">

<!-- NAVBAR (IDENTICA) -->
<nav>
    <div class="logo"><img src="Immagini/logoFSL.png" style="height: 90px; width: 90px;"></div>
    <div class="name" id="greeting"><h2 style="font-family: Inter; font-size: 15px; color: black;"></h2></div>
    <ul>
        <li><a href="homepage-gestionePCTO.php">Home</a></li>
        <li><a>
            <?php
            if (!empty($studente)) {
                echo htmlspecialchars($studente['nome']);
            } else {
                echo "Utente non autenticato";
            }
            ?>
        </a></li>
        <li><a href="FAQ.html">FAQ</a></li>
    </ul>
    <div class="hamburger">
        <span></span>
        <span></span>
        <span></span>
    </div>
</nav>

<main style="padding: 40px; font-family: Inter, sans-serif; max-width: 1000px; margin: 0 auto;">

<!-- PROFILO -->
<?php if (!empty($studente)): ?>
<div class="profile-card" style="background: #f9f9f9; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 30px;">
    <h2 style="color: black;">Dati del Profilo</h2>

    <p><b>Nome:</b> <?= htmlspecialchars($studente['nome']) ?></p>
    <p><b>Cognome:</b> <?= htmlspecialchars($studente['cognome']) ?></p>
    <p><b>Data nascita:</b> <?= htmlspecialchars($studente['data_nascita']) ?></p>
    <p><b>Classe:</b> <?= htmlspecialchars($studente['classe']) ?></p>
    <p><b>Indirizzo:</b> <?= htmlspecialchars($studente['indirizzo_studi']) ?></p>
    <p><b>Telefono:</b> <?= htmlspecialchars($studente['tel']) ?></p>
    <p><b>Email:</b> <?= htmlspecialchars($studente['email']) ?></p>
    <p><b>Competenze:</b> <?= htmlspecialchars($studente['competenze']) ?></p>
</div>
<?php endif; ?>



<?php if ($presente): ?>

<div class="profile-card" style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
    <h2 style="color: black;">La tua azienda</h2>

    <p><b>Ragione sociale:</b> <?= htmlspecialchars($azienda['ragione_sociale'] ?? '') ?></p>
    <p><b>Responsabile:</b> <?= htmlspecialchars($azienda['responsabile'] ?? '') ?></p>
</div>

<div style="margin-top: 20px; text-align: center;">
    <button id="btnCommento" onclick="toggleCommento()"
        style="padding: 12px 24px; font-size: 16px; background-color: rgb(68, 184, 85); color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
        Aggiungi commento
    </button>
</div>

<div id="commento" style="display:none; max-width: 480px; margin: 0 auto; padding: 24px; background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); font-family: system-ui, sans-serif;">

    <h2 style="font-size: 1.4rem; font-weight: 600; color: #1a1a1a; margin-bottom: 16px;">
        Lascia un commento
    </h2>

    <form method="POST" action="pagina_principale_studente.php" style="display: flex; flex-direction: column; gap: 14px;">

        <input type="text" name="nome" placeholder="Il tuo nome"
            style="padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 8px;">

        <textarea name="commento" placeholder="Scrivi il tuo commento..."
            style="padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 8px; height: 120px;"></textarea>

        <button type="submit"
            style="padding: 12px; background: #3b82f6; color: white; border: none; border-radius: 8px;">
            Invia commento
        </button>

    </form>
</div>



<?php else: ?>

<div style="width: 100%; max-width: 600px; margin: 20px auto; padding: 20px; background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); font-family: system-ui, sans-serif;">

<?php foreach ($attivita as $a): ?>

    <div style="width: 100%; max-width: 600px; margin: 20px auto; padding: 20px; background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); font-family: system-ui, sans-serif;">

        <h2 style="font-size: 1.4rem; font-weight: 600; color: #1a1a1a; margin-bottom: 12px;">
            <?= htmlspecialchars($a['titolo']) ?>
        </h2>

        <p style="font-size: 0.95rem; color: #555; line-height: 1.5; margin-bottom: 14px;">
            <?= htmlspecialchars($a['descrizione']) ?>
        </p>

        <div style="display: flex; flex-direction: column; gap: 6px; font-size: 0.9rem; color: #444; margin-bottom: 16px;">
            <span><strong>Periodo:</strong> <?= htmlspecialchars($a['periodo_i']) ?> → <?= htmlspecialchars($a['periodo_f']) ?></span>
            <span><strong>Durata:</strong> <?= htmlspecialchars($a['periodo']) ?> giorni</span>
            <span><strong>Orario:</strong> <?= htmlspecialchars($a['orario_i']) ?> - <?= htmlspecialchars($a['orario_f']) ?></span>
            <span><strong>Oggetto attività:</strong> <?= htmlspecialchars($a['att_oggetto']) ?></span>
            <span><strong>Max studenti:</strong> <?= htmlspecialchars($a['max_studenti']) ?></span>
            <span><strong>Competenze richieste:</strong> <?= htmlspecialchars($a['competenze_ric']) ?></span>
            <span><strong>Ambito:</strong> <?= htmlspecialchars($a['ambito']) ?></span>
        </div>

        <form method="POST" action=""> <input type="hidden" name="titolo" value="<?= htmlspecialchars($a['titolo']) ?>">
            <button type="submit" style="padding: 10px 18px; background: #3b82f6; color: white; border: none; border-radius: 8px; cursor: pointer;">
                Scegli
            </button>
        </form>

    </div> <?php endforeach; ?>

</div>

<?php endif; ?>

</main>

<script>
function toggleCommento() {
    var container = document.getElementById("commento");
    container.style.display = (container.style.display === "none") ? "block" : "none";
}
</script>

<!-- FOOTER (IDENTICO) -->
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
    <center><p class="copy">©Copyright easyFSL s.r.l management company 2026</p></center>
</footer>

</body>
</html>