<?php
    require "credenziali.php"; //per tenere le credenziali di connessione al database
    //start della sessione e cript della password
    session_start();
    $name = $_POST['utente']; 
    $pass = $_POST['password']; 


    //instauro la connessione
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query per selezionare l'utente dal database in base all'username
    $stmt = $conn->prepare("SELECT * FROM utenti WHERE nome = :username");
    $stmt->bindParam(':username', $name);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $pass == $user['password']) { 
        $_SESSION["UTENTE"]=$name;
        header("location:partecipazioni.php");
        exit();
    }
    else{
        unset($_SESSION["UTENTE"]);
        echo "non autenticato ";
    }

?>