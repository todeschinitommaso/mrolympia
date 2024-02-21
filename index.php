<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <form action="login.php" method="POST">
        <h2>Login</h2>
        <label for="utente">Utente:</label>
        <input type="text" id="utente" name="utente" required>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>        
        
        <input type="submit" value="Accedi">
        <input type="button" value="Registrati" onclick="location.href='register.html';">
    </form>

</body>
</html>