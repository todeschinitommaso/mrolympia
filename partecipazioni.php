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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Gestione dell'eliminazione dei dati
    if (isset($_POST['elimina']) && isset($_POST['id_partecipazione'])) {
        $id_partecipazione = $_POST['id_partecipazione'];
        $stmt_delete = $conn->prepare("DELETE FROM PARTECIPAZIONE WHERE id = ?");
        $stmt_delete->bind_param("i", $id_partecipazione);
        $stmt_delete->execute();
        $stmt_delete->close();
    }

    // Gestione della modifica dei dati
    if (isset($_POST['modifica']) && isset($_POST['id_partecipazione'])) {
        $id_partecipazione = $_POST['id_partecipazione'];

        // Recupera i dati della riga da modificare
        $stmt_select = $conn->prepare("SELECT ATLETI.nome AS atleta, COMPETIZIONI.nome AS competizione, PARTECIPAZIONE.premio, PARTECIPAZIONE.posizione
                                       FROM PARTECIPAZIONE
                                       INNER JOIN ATLETI ON PARTECIPAZIONE.id_atleta = ATLETI.id
                                       INNER JOIN COMPETIZIONI ON PARTECIPAZIONE.id_competizione = COMPETIZIONI.id
                                       WHERE PARTECIPAZIONE.id = ?");
        $stmt_select->bind_param("i", $id_partecipazione);
        $stmt_select->execute();
        $result_select = $stmt_select->get_result();
        $row = $result_select->fetch_assoc();

        // Valorizza le variabili per popolare il form di modifica
        $atleta_modifica = $row['atleta'];
        $competizione_modifica = $row['competizione'];
        $premio_modifica = $row['premio'];
        $posizione_modifica = $row['posizione'];
    }

    // Gestione dell'inserimento dei dati
    if (isset($_POST['inserisci'])) {
        // Recupera i valori inviati dal form
        $atleta = $_POST['atleta'];
        $competizione = $_POST['competizione'];
        $premio = $_POST['premio'];
        $posizione = $_POST['posizione'];

        // Verifica se l'atleta e la competizione esistono nel database
        $stmt_check_atleta = $conn->prepare("SELECT id FROM ATLETI WHERE nome = ?");
        $stmt_check_atleta->bind_param("s", $atleta);
        $stmt_check_atleta->execute();
        $stmt_check_atleta->store_result();
        if ($stmt_check_atleta->num_rows == 0) {
            die("Atleta non trovato nel database.");
        }
        $stmt_check_atleta->close();

        $stmt_check_competizione = $conn->prepare("SELECT id FROM COMPETIZIONI WHERE nome = ?");
        $stmt_check_competizione->bind_param("s", $competizione);
        $stmt_check_competizione->execute();
        $stmt_check_competizione->store_result();
        if ($stmt_check_competizione->num_rows == 0) {
            die("Competizione non trovata nel database.");
        }
        $stmt_check_competizione->close();

        // Prepara e esegui la query per inserire i dati nella tabella PARTECIPAZIONE
        $stmt_insert = $conn->prepare("INSERT INTO PARTECIPAZIONE (id_atleta, id_competizione, premio, posizione) VALUES ((SELECT id FROM ATLETI WHERE nome = ?), (SELECT id FROM COMPETIZIONI WHERE nome = ?), ?, ?)");
        $stmt_insert->bind_param("ssii", $atleta, $competizione, $premio, $posizione);
        $stmt_insert->execute();
        $stmt_insert->close();
    }

    // Gestione della modifica dei dati
    if (isset($_POST['salva_modifiche']) && isset($_POST['id_partecipazione'])) {
        // Recupera i valori inviati dal form
        $atleta = $_POST['atleta'];
        $competizione = $_POST['competizione'];
        $premio = $_POST['premio'];
        $posizione = $_POST['posizione'];
        $id_partecipazione = $_POST['id_partecipazione'];

        // Verifica se l'atleta e la competizione esistono nel database
        $stmt_check_atleta = $conn->prepare("SELECT id FROM ATLETI WHERE nome = ?");
        $stmt_check_atleta->bind_param("s", $atleta);
        $stmt_check_atleta->execute();
        $stmt_check_atleta->store_result();
        if ($stmt_check_atleta->num_rows == 0) {
            die("Atleta non trovato nel database.");
        }
        $stmt_check_atleta->close();

        $stmt_check_competizione = $conn->prepare("SELECT id FROM COMPETIZIONI WHERE nome = ?");
        $stmt_check_competizione->bind_param("s", $competizione);
        $stmt_check_competizione->execute();
        $stmt_check_competizione->store_result();
        if ($stmt_check_competizione->num_rows == 0) {
            die("Competizione non trovata nel database.");
        }
        $stmt_check_competizione->close();

        // Prepara e esegui la query per aggiornare i dati nella tabella PARTECIPAZIONE
        $stmt_update = $conn->prepare("UPDATE PARTECIPAZIONE SET id_atleta = (SELECT id FROM ATLETI WHERE nome = ?), id_competizione = (SELECT id FROM COMPETIZIONI WHERE nome = ?), premio = ?, posizione = ? WHERE id = ?");
        $stmt_update->bind_param("ssiii", $atleta, $competizione, $premio, $posizione, $id_partecipazione);
        $stmt_update->execute();
        $stmt_update->close();
    }
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
    $selected_atleta = (isset($_GET['atleta']) && $_GET['atleta'] == $row_atleta['nome']) ? "selected" : "";
    echo "<option value='".$row_atleta['nome']."' $selected_atleta>".$row_atleta['nome']."</option>";
}
echo "</select>";

echo "<label for='competizioni'>Seleziona Competizione:</label>";
echo "<select name='competizione' id='competizioni' onchange='this.form.submit()'>";
echo "<option value='tutte'>Tutte le competizioni</option>";

// Opzioni per le competizioni
while ($row_competizione = $result_competizioni->fetch_assoc()) {
    $selected_competizione = (isset($_GET['competizione']) && $_GET['competizione'] == $row_competizione['nome']) ? "selected" : "";
    echo "<option value='".$row_competizione['nome']."' $selected_competizione>".$row_competizione['nome']."</option>";
}
echo "</select>";

echo "<label for='posizione'>Seleziona Posizione:</label>";
echo "<select name='posizione' id='posizioni' onchange='this.form.submit()'>";
echo "<option value='tutte'>Tutte le posizioni</option>";
for ($i = 1; $i <= 10; $i++) {
    $selected_posizione = (isset($_GET['posizione']) && $_GET['posizione'] == $i) ? "selected" : "";
    echo "<option value='$i' $selected_posizione>$i</option>";
}
echo "</select>";

echo "</form>";

echo "<form method='GET' style='margin-top: 20px;'>";
echo "<input type='submit' value='RESETTA FILTRI'>";
echo "</form>";

echo "</div>";

// Tabella Partecipazioni
echo "<table><tr><th>Atleta</th><th>Competizione</th><th>Premio ($)</th><th>Posizione</th><th>Azioni</th></tr>";

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

// Verifica se $result è definito prima di utilizzarlo
if (isset($result)) {
    // Output dei dati di ogni riga
    while($row = $result->fetch_assoc()) {
        echo "<tr><td><a href='atleta.php?atleta=".urlencode($row['atleta'])."'>".$row["atleta"]."</a></td><td>".$row["competizione_nome"]."</td><td>".$row["premio"]."</td><td>".$row["posizione"]."</td>";
        
        // Aggiungi un pulsante "Elimina" per ogni riga
        echo "<td>
                  <form method='POST'>
                      <input type='hidden' name='id_partecipazione' value='".$row["id_partecipazione"]."'>
                      <input type='submit' name='elimina' value='Elimina'>
                  </form>
                  <form method='POST'>
                      <input type='hidden' name='id_partecipazione' value='".$row["id_partecipazione"]."'>
                      <input type='submit' name='modifica' value='Modifica'>
                  </form>
              </td></tr>";
    }
}

echo "</table>";

// Form per l'inserimento e la modifica di dati
echo "<div style='margin-top: 20px;'>";
echo "<h3>";
echo (isset($atleta_modifica)) ? "Modifica partecipazione" : "Inserisci nuova partecipazione";
echo "</h3>";
echo "<form method='POST'>";
echo "<label for='atleta'>Atleta:</label>";
echo "<select name='atleta'>";
// Opzioni per gli atleti
$result_atleti = $conn->query($sql_atleti);
while ($row_atleta = $result_atleti->fetch_assoc()) {
    $selected_atleta_modifica = (isset($atleta_modifica) && $atleta_modifica == $row_atleta['nome']) ? "selected" : "";
    echo "<option value='".$row_atleta['nome']."' $selected_atleta_modifica>".$row_atleta['nome']."</option>";
}
echo "</select>";

echo "<label for='competizione'>Competizione:</label>";
echo "<select name='competizione'>";
// Opzioni per le competizioni
$result_competizioni = $conn->query($sql_competizioni);
while ($row_competizione = $result_competizioni->fetch_assoc()) {
    $selected_competizione_modifica = (isset($competizione_modifica) && $competizione_modifica == $row_competizione['nome']) ? "selected" : "";
    echo "<option value='".$row_competizione['nome']."' $selected_competizione_modifica>".$row_competizione['nome']."</option>";
}
echo "</select>";

echo "<label for='premio'>Premio:</label>";
echo "<input type='text' name='premio' value='".(isset($premio_modifica) ? $premio_modifica : "")."' required>";

echo "<label for='posizione'>Posizione:</label>";
echo "<input type='text' name='posizione' value='".(isset($posizione_modifica) ? $posizione_modifica : "")."' required>";

// Aggiungi un campo nascosto per memorizzare l'ID della partecipazione da modificare
echo "<input type='hidden' name='id_partecipazione' value='".(isset($id_partecipazione) ? $id_partecipazione : "")."'>";

// Mostra il pulsante corretto in base a inserimento o modifica
echo "<input type='submit' name='".(isset($atleta_modifica) ? "salva_modifiche" : "inserisci")."' value='".(isset($atleta_modifica) ? "Salva Modifiche" : "Inserisci")."'>";

echo "</form>";
echo "</div>";

// Fine del documento HTML
echo "</body>
</html>";

// Chiudi la connessione al database
$conn->close();
?>
