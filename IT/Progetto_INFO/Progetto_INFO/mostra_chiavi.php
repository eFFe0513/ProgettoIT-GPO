<?php
// Configurazione database
$host = 'localhost';
$dbname = 'GestioneFSL';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "SELECT * FROM chiavi";
    $stmt = $pdo->query($sql);
    $risultati = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    die("Errore di connessione al database: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Valori della Tabella Chiavi</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 80%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .back { margin-top: 20px; }
    </style>
</head>
<body>
    <h1>Contenuto della tabella chiavi</h1>
    
    <?php if (count($risultati) > 0): ?>
        <table>
            <thead>
                <tr>
                    <?php 
                    if (!empty($risultati)) {
                        foreach (array_keys($risultati[0]) as $colonna) {
                            echo "<th>" . htmlspecialchars($colonna) . "</th>";
                        }
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($risultati as $riga): ?>
                    <tr>
                        <?php foreach ($riga as $valore): ?>
                            <td><?php echo htmlspecialchars($valore); ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nessun dato trovato nella tabella "chiavi".</p>
    <?php endif; ?>
    
    <div class="back">
        <a href="FAQ.html">Torna alla pagina con il pulsante invisibile</a>
    </div>
</body>
</html>