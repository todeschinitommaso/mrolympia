<?php
session_start();
require "libreria.php"; // per funzioni che verranno eseguite dal server e che possono servire 
require "credenziali.php"; //per tenere le credenziali di connessione al database

if (isset($_SESSION["UTENTE"])) {

    // css per presentazione più belina
    echo "<html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 20px;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }

                th, td {
                    border: 1px solid #dddddd;
                    text-align: left;
                    padding: 8px;
                }

                th {
                    background-color: #f2f2f2;
                }

                form {
                    margin-bottom: 20px;
                }

                footer {
                    margin-top: 20px;
                }

                button {
                    padding: 10px;
                    cursor: pointer;
                    background-color: #4CAF50;
                    color: white;
                    border: none;
                    border-radius: 4px;
                }

                select, button[type='submit'] {
                    padding: 10px;
                    margin-right: 10px;
                }
                .add-container {
                    float: right;
                    margin: 0 auto; /* Imposta il margine automatico per centrare il div */
                    width: fit-content; /* Imposta la larghezza in base al contenuto */
                }
                
                .add-container label {
                    font-weight: bold;
                }
                
                .add-container input[type='text'] {
                    padding: 5px;
                    margin-left: 20px;
                }
            </style>
        </head>
        <body>";

        //fine parte css, messa qui tanto per non creare un file a parte per così poco

    echo "Benvenuto negli oggetti " . $_SESSION["UTENTE"];

    echo "<footer>
        <button onclick='redirectToPage(\"scaffale.php\")'>Visualizza gli Scaffali</button>
      </footer><br>";

    //connessione per la stampa della tabella principale se la pagina non è ricaricata
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Controlla se la pagina è stata ricaricata
        if (isset($_POST['ricaricaPagina'])) {
            
            $_SESSION['ricaricaPagina'] = true;
            // Ricarica la pagina
            echo "<script>window.location.reload();</script>";
        }

        // Query che stampa tutti gli oggetti (query principale)
        $sql = 'SELECT * FROM PARTECIPAZIONE';

        $statement = $conn->query($sql);

        if ($statement->rowCount() > 0) {
            // Tabella HTML
            echo "<table>
                    <tr>
                        <th>ID Atleta</th>
                        <th>ID Competizione</th>
                        <th>Punteggio</th>
                        <th>Posizione</th>
                    </tr>";

            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['id_atleta']}</td>
                        <td>{$row['id_competizione']}</td>
                        <td>{$row['punteggio']}</td>
                        <td>{$row['posizione']}</td>
                    </tr> ";
            }

            echo "</table>";

            // Form per il filtro
            
            echo"<form method='POST'>";
            //echo "<form method='POST'>
             echo"<label for='my_html_select_box'>FILTRA PER PREZZO: </label>    
                    <select name='my_html_select_box'>
                        <option>Prezzo: Crescente</option>
                        <option>Prezzo: Decrescente</option>
                    </select>
                    <button type='submit'>Filtra</button><br><br>";

            echo "</form>"; //fine form filtri fornitori e prezzi

            echo"<button onclick='redirectToPage(\"aggiungi_oggetto.php\")'>Aggiungi oggetto</button><br><br>";
            echo"<button onclick='redirectToPage(\"elimina_oggetto.php\")'>Elimina oggetto</button><br><br>";
            echo"<button onclick='redirectToPage(\"aggiungi_oggetto.php\")'>Modifica oggetto</button>";


        } else {
            // Messaggio se la query non ha prodotto risultati
            echo "Nessun risultato trovato";
        }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    } finally {
        // Chiudi la connessione in ogni caso
        $conn = null;
    }

    echo "</body>
        </html>";
} else {
    echo "Accesso non consentito";
}
?>

<script>
    function redirectToPage(page) {
        window.location.href = page;
    }
</script>
