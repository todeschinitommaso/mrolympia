<?php
// Include il file credenziali.php
include 'credenziali.php';

// Connessione al database
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica della connessione
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

// Verifica se il form di inserimento è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['atleta']) && isset($_POST['competizione']) && isset($_POST['premio']) && isset($_POST['posizione'])) {
    // Recupera i valori inviati dal form
    $atleta = $_POST['atleta'];
    $competizione = $_POST['competizione'];
    $premio = $_POST['premio'];
    $posizione = $_POST['posizione'];

    // Verifica se l'atleta e la competizione esistono nel database
    $stmt_check = $conn->prepare("SELECT id FROM ATLETI WHERE nome = ?");
    $stmt_check->bind_param("s", $atleta);
    $stmt_check->execute();
    $stmt_check->store_result();
    if ($stmt_check->num_rows == 0) {
        die("Atleta non trovato nel database.");
    }
    $stmt_check->close();

    $stmt_check = $conn->prepare("SELECT id FROM COMPETIZIONI WHERE nome = ?");
    $stmt_check->bind_param("s", $competizione);
    $stmt_check->execute();
    $stmt_check->store_result();
    if ($stmt_check->num_rows == 0) {
        die("Competizione non trovata nel database.");
    }
    $stmt_check->close();

    // Prepara e esegui la query per inserire i dati nella tabella PARTECIPAZIONE
    $stmt = $conn->prepare("INSERT INTO PARTECIPAZIONE (id_atleta, id_competizione, premio, posizione) VALUES ((SELECT id FROM ATLETI WHERE nome = ?), (SELECT id FROM COMPETIZIONI WHERE nome = ?), ?, ?)");
    $stmt->bind_param("ssii", $atleta, $competizione, $premio, $posizione);
    $stmt->execute();
    $stmt->close();
}

// Gestione dell'eliminazione dei dati
if(isset($_POST['elimina']) && isset($_POST['id_partecipazione'])) {
    $id_partecipazione = $_POST['id_partecipazione'];
    $stmt_delete = $conn->prepare("DELETE FROM PARTECIPAZIONE WHERE id = ?");
    $stmt_delete->bind_param("i", $id_partecipazione);
    $stmt_delete->execute();
    $stmt_delete->close();
}

// Query per ottenere tutte le competizioni disponibili
$sql_competizioni = "SELECT * FROM COMPETIZIONI";
$result_competizioni = $conn->query($sql_competizioni);

// Query per ottenere tutti gli atleti disponibili
$sql_atleti = "SELECT * FROM ATLETI";
$result_atleti = $conn->query($sql_atleti);

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
        padding: 50px;
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
        display: inline; /* Modifica per renderlo inline */
    }
    
    form input[type='submit'] {
        background-color: transparent;
        border: none;
        cursor: pointer;
        color: blue;
        text-decoration: underline;
        padding: 0;
    }
    
    form input[type='submit']:hover {
        color: red;
    }
    
    form input[type='submit']:focus {
        outline: none;
    }
    
    </style>
</head>
<body>";

// Combobox per selezionare le competizioni
echo "<div id='filtroCompetizioni'>";
echo "<form method='GET'>";

echo "<label for='atleta'>Seleziona Atleta:</label>";
echo "<select name='atleta' id='atleta' onchange='this.form.submit()'>";
echo "<option value='tutti'>Tutti gli atleti</option>";
while ($row_atleta = $result_atleti->fetch_assoc()) {
    $selected = ($_GET['atleta'] == $row_atleta['nome']) ? "selected" : "";
    echo "<option value='".$row_atleta['nome']."' $selected>".$row_atleta['nome']."</option>";
}
echo "</select>";

echo "<label for='competizioni'>Seleziona Competizione:</label>";
echo "<select name='competizione' id='competizioni' onchange='this.form.submit()'>";
echo "<option value='tutte'>Tutte le competizioni</option>";

// Opzioni per le competizioni
while ($row_competizione = $result_competizioni->fetch_assoc()) {
    $selected = ($_GET['competizione'] == $row_competizione['nome']) ? "selected" : "";
    echo "<option value='".$row_competizione['nome']."' $selected>".$row_competizione['nome']."</option>";
}
echo "</select>";

echo "<label for='posizione'>Seleziona Posizione:</label>";
echo "<select name='posizione' id='posizioni' onchange='this.form.submit()'>";
echo "<option value='tutte'>Tutte le posizioni</option>";
for ($i = 1; $i <= 10; $i++) {
    $selected = ($_GET['posizione'] == $i) ? "selected" : "";
    echo "<option value='$i' $selected>$i</option>";
}
echo "</select>";

echo "</form>";

echo "<form method='GET' style='margin-top: 20px;'>";
echo "<input type='submit' value='RESETTA FILTRI'>";
echo "</form>";

echo "</div>";

// Query per selezionare i dati dalla tabella PARTECIPAZIONE e ottenere i nomi degli atleti e delle competizioni
$sql = "SELECT ATLETI.nome AS atleta, COMPETIZIONI.nome AS competizione_nome, PARTECIPAZIONE.premio, PARTECIPAZIONE.posizione, PARTECIPAZIONE.id AS id_partecipazione
        FROM PARTECIPAZIONE
        INNER JOIN ATLETI ON PARTECIPAZIONE.id_atleta = ATLETI.id
        INNER JOIN COMPETIZIONI ON PARTECIPAZIONE.id_competizione = COMPETIZIONI.id";

// Aggiungiamo il filtro sulla competizione se è stato selezionato
if (isset($_GET['competizione']) && $_GET['competizione'] != 'tutte') {
    $competizione = $conn->real_escape_string($_GET['competizione']);
    $sql .= " WHERE COMPETIZIONI.nome = '$competizione'";
}

// Aggiungiamo il filtro sulla posizione se è stato selezionato
if (isset($_GET['posizione']) && $_GET['posizione'] != 'tutte') {
    $posizione = $conn->real_escape_string($_GET['posizione']);
    if (strpos($sql, 'WHERE') !== false) {
        $sql .= " AND PARTECIPAZIONE.posizione = '$posizione'";
    } else {
        $sql .= " WHERE PARTECIPAZIONE.posizione = '$posizione'";
    }
}

// Aggiungiamo il filtro sul nome dell'atleta se è stato selezionato
if (isset($_GET['atleta']) && $_GET['atleta'] != 'tutti') {
    $atleta = $conn->real_escape_string($_GET['atleta']);
    if (strpos($sql, 'WHERE') !== false) {
        $sql .= " AND ATLETI.nome = '$atleta'";
    } else {
        $sql .= " WHERE ATLETI.nome = '$atleta'";
    }
}

$result = $conn->query($sql);

// Tabella Partecipazioni
echo "<table><tr><th>Atleta</th><th>Competizione</th><th>Premio ($)</th><th>Posizione</th><th>Azioni</th></tr>";

// Verifica se $result è definito prima di utilizzarlo
if (isset($result)) {
    // Output dei dati di ogni riga
    while($row = $result->fetch_assoc()) {
        echo "<tr><td><a href='atleta.php?atleta=".urlencode($row['atleta'])."'>".$row["atleta"]."</a></td><td>".$row["competizione_nome"]."</td><td>".$row["premio"]."</td><td>".$row["posizione"]."</td>";
        
        // Aggiungi un pulsante "Elimina" per ogni riga
        echo "<td><form method='POST'><input type='hidden' name='id_partecipazione' value='".$row["id_partecipazione"]."'><input type='submit' name='elimina' value='Elimina'></form></td></tr>";
    }
}

echo "</table>";

// Form per l'inserimento di dati
echo "<div style='margin-top: 20px;'>";
echo "<h3>Inserisci nuova partecipazione</h3>";
echo "<form method='POST'>";
echo "<label for='atleta'>Atleta:</label>";
echo "<select name='atleta'>";
// Opzioni per gli atleti
$result_atleti = $conn->query($sql_atleti);
while ($row_atleta = $result_atleti->fetch_assoc()) {
    echo "<option value='".$row_atleta['nome']."'>".$row_atleta['nome']."</option>";
}
echo "</select>";

echo "<label for='competizione'>Competizione:</label>";
echo "<select name='competizione'>";
// Opzioni per le competizioni
$result_competizioni = $conn->query($sql_competizioni);
while ($row_competizione = $result_competizioni->fetch_assoc()) {
    echo "<option value='".$row_competizione['nome']."'>".$row_competizione['nome']."</option>";
}
echo "</select>";

echo "<label for='premio'>Premio:</label>";
echo "<input type='text' name='premio' required>";

echo "<label for='posizione'>Posizione:</label>";
echo "<input type='text' name='posizione' required>";

echo "<input type='submit' value='Inserisci'>";
echo "</form>";
echo "</div>";

// Fine del documento HTML
echo "</body>
</html>";

// Chiudi la connessione al database
$conn->close();
?>
