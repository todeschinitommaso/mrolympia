<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atleta</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative;
        }

        #filtroCompetizioni {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 10px 20px;
            z-index: 1000;
        }

        #filtroCompetizioni label {
            display: block;
            margin-bottom: 8px;
        }

        #filtroCompetizioni select {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
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

        tr.selected {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
<?php
// Includi il file credenziali.php
include 'credenziali.php';

// Connessione al database
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica della connessione
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

if (isset($_GET['atleta'])) {
    $atleta = $_GET['atleta'];

    // Query per ottenere i dati degli atleti
    $sql = "SELECT * FROM ATLETI";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table><tr><th>ID</th><th>Nome</th><th>Nazionalità</th><th>Età</th><th>Altezza</th><th>Peso Gara</th><th>Peso Massa</th><th>ID Preparatore</th><th>ID Biografia</th></tr>";
        // Output dei dati di ogni riga
        while ($row = $result->fetch_assoc()) {
            echo "<tr" . ($row['nome'] == $atleta ? ' class="selected"' : '') . ">";
            foreach ($row as $value) {
                echo "<td>" . $value . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Nessun risultato trovato per questo atleta.";
    }
} else {
    echo "Nome dell'atleta non specificato.";
}

// Chiudi la connessione al database
$conn->close();
?>
</body>
</html>
