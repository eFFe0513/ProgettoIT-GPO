<?php
$conn = new mysqli("localhost", "root", "", "GestioneFSL");
if ($conn->connect_error) {
    die("Errore connessione: " . $conn->connect_error);
}

$scuola = [
    "nome" => "ITT S. Cannizzaro",
    "indirizzo" => "Via R. Sanzio 2",
    "citta" => "Rho (MI), Lombardia"
];

$aziende = [];
$res = $conn->query("SELECT * FROM Azienda");
while ($row = $res->fetch_assoc()) {
    $aziende[] = $row;
}


$studenti = [];
$res = $conn->query("SELECT * FROM Studente");
while ($row = $res->fetch_assoc()) {
    $studenti[] = $row;
}


$tutor = [];
$res = $conn->query("SELECT * FROM Tutor_scolastico");
while ($row = $res->fetch_assoc()) {
    $tutor[] = $row;
}

$commenti = [];
$res = $conn->query("SELECT * FROM Commento");
while ($row = $res->fetch_assoc()) {
    $commenti[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Scuola</title>

<style>
body {
    margin: 0;
    font-family: Inter, sans-serif;
    background: #ffffff;
}

/* CARD STYLE */
.card {
    width: 100%;
    max-width: 900px;
    margin: 20px auto;
    padding: 20px 20px;
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

/* TITOLI */
h2 {
    color: #1a1a1a;
    margin-bottom: 10px;
}

/* LISTE */
.item {
    padding: 10px;
    border-bottom: 1px solid #eee;
}

.item:last-child {
    border-bottom: none;
}

/* NAVBAR */
nav{
    position: relative;
    display: flex;
    justify-content: space-between; 
    align-items: center;
    height: 90px;
    background-color: white;
    overflow: visible;
    padding: 0 60px;
    position: sticky;
    top: 0;
    z-index: 1000;
}
nav ul{
    display: flex;          
    gap: 50px; 
    list-style-type: none;
    margin: 0;
    padding: 0;
    gap: 50px;
}
nav a{
    display: inline-block;
    color: black;
    text-decoration: none;
    cursor: pointer;
    font-family: calibri;
    font-size: 18px;
    padding: 10px, 15px;
    border-radius: 10px;
    transition: transform 0.2s;
    text-align: center;
    padding: 5px 10px;
}
nav a:hover{
    transform: scale(1.1);
    background-color: rgb(68, 184, 85);
    color: white;
    font-weight: bold;
}

nav li{
    position: relative;
}

/*per adattamento mobile*/
.hamburger {
  display: none;
  flex-direction: column;
  gap: 5px;
  cursor: pointer;
}

.hamburger span {
  width: 26px;
  height: 3px;
  background: black;
  border-radius: 3px;
}

@media (max-width: 950px) {
  nav ul {
    display: none;
  }

  .hamburger {
    display: flex;
  }

  nav ul.active {
    display: flex;
    flex-direction: column;
    position: absolute;
    top: 80px;
    right: 20px;
    background: white;
    padding: 1rem 2rem;
    border-radius: 10px;
    gap: 1rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  }
}


.nav ul.active {
  display: flex;
  flex-direction: column;
  position: absolute;
  top: 70px;
  right: 20px;
  background: #111;
  padding: 1rem 2rem;
  border-radius: 8px;
  gap: 1rem;
}

/* FOOTER */
.footer .col h3 {
    font-family: Inter;
    color: white;
    margin-bottom: 10px;
}
.footer a {
    font-family: Inter;
    display: block;
    color: white;
    text-decoration: none;
    margin: 4px 0;
}
@media (max-width: 900px){
    .footer .col h3{
        text-align: center;
    }
    .footer a{
        text-align: center;
    }
}
.footer a:hover {
    color: green;
}

.footer {
    background: rgb(68, 184, 85);
    color: white;
    padding: 40px 0;
    margin-top: 60px;
}
.footer .container {
    font-family: Inter;
    max-width: 1000px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    gap: 40px;
    flex-wrap: wrap;
}
.search-box {
    width: 90%;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 8px;
    border: 1px solid #ddd;
    font-family: Inter, sans-serif;
}

.hidden {
    display: none;
}
</style>
</head>
<script>
function filter(sezione, valore) {
    valore = valore.toLowerCase().trim();

    let items = document.querySelectorAll("." + sezione + "-card");

    items.forEach(el => {
        let text = el.innerText.toLowerCase();

        if (text.includes(valore)) {
            el.style.display = "block";
        } else {
            el.style.display = "none";
        }
    });
}
</script>
<body>

<!-- NAVBAR -->
<nav>
    <div class="logo">
        <img src="Immagini/logoFSL.png" style="height: 70px;">
    </div>

    <ul>
        <li><a href="homepage-gestionePCTO.php">Home</a></li>
        <li><a href="#">Scuola</a></li>
        <li><a href="FAQ.html">FAQ</a></li>
    </ul>
</nav>

<main>

<div class="card">
    <h2>Informazioni Scuola</h2>
    <p><b>Nome:</b> <?= $scuola['nome'] ?></p>
    <p><b>Indirizzo:</b> <?= $scuola['indirizzo'] ?></p>
    <p><b>Città:</b> <?= $scuola['citta'] ?></p>
</div>

<div class="card">
    <h2>Aziende</h2>

    <input type="text"
       class="search-box"
       placeholder="Cerca azienda..."
       onkeyup="filter('aziende', this.value)"
       onkeydown="if(event.key==='Enter'){ event.preventDefault(); filter('aziende', this.value); }">

    <?php foreach ($aziende as $a): ?>
        <div class="item">
            <b><?= $a['ragione_sociale'] ?></b><br>
            Responsabile: <?= $a['responsabile'] ?><br>
            Email: <?= $a['email'] ?><br>
            Settore: <?= $a['settore'] ?>
        </div>
    <?php endforeach; ?>
</div>

<div class="card">
    <h2>Studenti</h2>

    <input type="text"
       class="search-box"
       placeholder="Cerca studente..."
       onkeyup="filter('studenti', this.value)"
       onkeydown="if(event.key==='Enter'){ event.preventDefault(); filter('studenti', this.value); }">

    <?php foreach ($studenti as $s): ?>
        <div class="item">
            <b><?= $s['nome'] ?> <?= $s['cognome'] ?></b><br>
            Classe: <?= $s['classe'] ?><br>
            Email: <?= $s['email'] ?><br>
            Competenze: <?= $s['competenze'] ?>
        </div>
    <?php endforeach; ?>
</div>


<div class="card">
    <h2>Tutor scolastici</h2>

    <input type="text"
       class="search-box"
       placeholder="Cerca tutor..."
       onkeyup="filter('tutor', this.value)"
       onkeydown="if(event.key==='Enter'){ event.preventDefault(); filter('tutor', this.value); }">

    <?php foreach ($tutor as $t): ?>
        <div class="item">
            <b><?= $t['nome'] ?> <?= $t['cognome'] ?></b><br>
            Email: <?= $t['email'] ?>
        </div>
    <?php endforeach; ?>
</div>


<div class="card">
    <h2>Commenti</h2>

    <input type="text"
       class="search-box"
       placeholder="Cerca commenti..."
       onkeyup="filter('commenti', this.value)"
       onkeydown="if(event.key==='Enter'){ event.preventDefault(); filter('commenti', this.value); }">

    <?php foreach ($commenti as $c): ?>
        <div class="item">
            <b>Studente:</b> <?= $c['CF_S'] ?><br>
            <?= $c['testo'] ?>
        </div>
    <?php endforeach; ?>
</div>

</main>

<!-- FOOTER -->
<footer class="footer">
    <div class="container">
        <div class="col">
            <h3>Azienda</h3>
            <a href="#">Prodotti</a>
            <a href="FAQ.html">FAQ</a>
        </div>

        <div class="col">
            <h3>Supporto</h3>
            <a href="#">Assistenza</a>
            <a href="#">Privacy</a>
        </div>

        <div class="col">
            <h3>Social</h3>
            <a href="#">Instagram</a>
            <a href="#">LinkedIn</a>
        </div>
    </div>

    <center>
        <p>© Copyright easyFSL s.r.l management company 2026</p>
    </center>
</footer>

</body>
</html>