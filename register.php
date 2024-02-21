<?php
require "credenziali.php"; //per tenere le credenziali di connessione al database

session_start();

$name = $_POST['nome']; 
$pass = $_POST['password']; 
$verifica = $_POST['verifica_password'];

// Controllo se esiste già un utente con lo stesso nome nel database
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT nome FROM utenti WHERE nome = :name";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    $utentiDB = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($utentiDB) > 0) { // se esiste almeno un utente con quel nome, lancio un errore
        echo "Nome utente già presente nel database";
        echo "<br><a href='register.html'>Torna indietro</a>"; // Link per tornare alla pagina di registrazione
    } else {
        if ($pass == $verifica) {
            $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

            // Query per inserire utente
            $sql = "INSERT INTO utenti (nome, password) VALUES (:name, :pass)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':pass', $pass);
            $stmt->execute();
            
            echo "Registrazione avvenuta con successo!";
            header("location: index.php"); // reindirizzamento alla pagina di login 
        } else {
            echo "Errore durante la registrazione, le password non coincidono";
            echo "<br><a href='register.html'>Torna indietro</a>"; // Link per tornare alla pagina di registrazione
        }
    }
} catch (PDOException $e) {
    echo "Errore durante la registrazione: " . $e->getMessage();
    echo "<br><a href='register.html'>Torna indietro</a>"; // Link per tornare alla pagina di registrazione
}
?>
