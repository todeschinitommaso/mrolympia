<?php
// Includi il file credenziali.php
include 'credenziali.php';

// Connessione al database
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica della connessione
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

// Query per ottenere tutte le competizioni disponibili
$sql_competizioni = "SELECT * FROM COMPETIZIONI";
$result_competizioni = $conn->query($sql_competizioni);

// Inizio del documento HTML
echo "<!DOCTYPE html>
<html>
<head>
    <title>Tabella Partecipazioni</title>
    <style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }
    
    table {
        width: 80%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    
    th, td {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
        cursor: pointer;
    }
    
    th {
        background-color: #f2f2f2;
    }
    
    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    
    tr:hover {
        background-color: #ddd;
    }
    
    a {
        text-decoration: none;
        color: inherit;
    }
    
    form {
        width: 100%;
        margin-top: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    form label {
        margin-bottom: 8px;
    }
    
    form select, form input {
        width: 100%;
        padding: 8px;
        margin-bottom: 16px;
        box-sizing: border-box;
    }
    </style>
</head>
<body>";

// Combobox per selezionare le competizioni
echo "<div id='filtroCompetizioni'>";
echo "<form method='GET'>";
echo "<label for='competizioni'>Seleziona Competizione:</label>";
echo "<select name='competizione' id='competizioni' onchange='this.form.submit()'>";
echo "<option value='tutte'>Tutte le competizioni</option>";

// Opzioni per le competizioni
while ($row_competizione = $result_competizioni->fetch_assoc()) {
    $selected = ($_GET['competizione'] == $row_competizione['nome']) ? "selected" : "";
    echo "<option value='".$row_competizione['nome']."' $selected>".$row_competizione['nome']."</option>";
}
echo "</select>";
echo "</form>";
echo "</div>";

// Query per selezionare i dati dalla tabella PARTECIPAZIONE e ottenere i nomi degli atleti e delle competizioni
$sql = "SELECT ATLETI.nome AS atleta, COMPETIZIONI.nome AS competizione, PARTECIPAZIONE.punteggio, PARTECIPAZIONE.posizione
        FROM PARTECIPAZIONE
        INNER JOIN ATLETI ON PARTECIPAZIONE.id_atleta = ATLETI.id
        INNER JOIN COMPETIZIONI ON PARTECIPAZIONE.id_competizione = COMPETIZIONI.id";

// Aggiungiamo il filtro sulla competizione se è stato selezionato
if (isset($_GET['competizione']) && $_GET['competizione'] != 'tutte') {
    $competizione = $conn->real_escape_string($_GET['competizione']);
    $sql .= " WHERE COMPETIZIONI.nome = '$competizione'";
}

$result = $conn->query($sql);

// Tabella Partecipazioni
echo "<table><tr><th>Atleta</th><th>Competizione</th><th>Punteggio</th><th>Posizione</th></tr>";
// Output dei dati di ogni riga
while($row = $result->fetch_assoc()) {
    echo "<tr><td><a href='atleta.php?atleta=".urlencode($row['atleta'])."'>".$row["atleta"]."</a></td><td>".$row["competizione"]."</td><td>".$row["punteggio"]."</td><td>".$row["posizione"]."</td></tr>";
}
echo "</table>";

// Form per l'inserimento di dati
echo "<div style='margin-top: 20px;'>";
echo "<h3>Inserisci nuova partecipazione</h3>";
echo "<form action='' method='POST'>"; // Rimuovere l'action per inviare al file corrente
echo "<label for='atleta'>Atleta:</label>";
echo "<select name='atleta'>";
// Opzioni per gli atleti
$sql_atleti = "SELECT * FROM ATLETI";
$result_atleti = $conn->query($sql_atleti);
while ($row_atleta = $result_atleti->fetch_assoc()) {
    echo "<option value='".$row_atleta['nome']."'>".$row_atleta['nome']."</option>";
}
echo "</select>";

echo "<label for='competizione'>Competizione:</label>";
echo "<select name='competizione'>";
// Opzioni per le competizioni
$sql_competizioni = "SELECT * FROM COMPETIZIONI";
$result_competizioni = $conn->query($sql_competizioni);
while ($row_competizione = $result_competizioni->fetch_assoc()) {
    echo "<option value='".$row_competizione['nome']."'>".$row_competizione['nome']."</option>";
}
echo "</select>";

echo "<label for='punteggio'>Punteggio:</label>";
echo "<input type='text' name='punteggio' required>";

echo "<label for='posizione'>Posizione:</label>";
echo "<input type='text' name='posizione' required>";

echo "<input type='submit' value='Inserisci'>";
echo "</form>";
echo "</div>";

// Fine del documento HTML
echo "</body>
</html>";

// Gestione dell'inserimento dei dati
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $atleta = $_POST['atleta'];
    $competizione = $_POST['competizione'];
    $punteggio = $_POST['punteggio'];
    $posizione = $_POST['posizione'];

    // Query per l'inserimento dei dati nella tabella PARTECIPAZIONE utilizzando statement preparati
    $stmt = $conn->prepare("INSERT INTO PARTECIPAZIONE (id_atleta, id_competizione, punteggio, posizione) VALUES (
        (SELECT id FROM ATLETI WHERE nome = ?),
        (SELECT id FROM COMPETIZIONI WHERE nome = ?),
        ?,
        ?
    )");

    // Associazione dei parametri
    $stmt->bind_param("ssii", $atleta, $competizione, $punteggio, $posizione);

    // Esecuzione della query
    if ($stmt->execute()) {
        echo "Inserimento avvenuto con successo.";
    } else {
        echo "Errore nell'inserimento: " . $stmt->error;
    }

    // Chiudi lo statement
    $stmt->close();
}

// Chiudi la connessione al database
$conn->close();
?>
