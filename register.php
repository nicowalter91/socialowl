<?php
    require "connection.php";
    $errorMessage = "";

    if(isset($_POST["submit"])){
        
        $username = $_POST["username"];
        $email = $_POST["email"];
        $firstname = $_POST["firstname"];  // Vorname
        $lastname = $_POST["lastname"];    // Nachname
        $password = $_POST["password"];
        $passwordRepeat = $_POST["passwordRepeat"]; // Das zweite Passwort

        // Überprüfen, ob die Passwörter übereinstimmen
        if ($password !== $passwordRepeat) {
            $errorMessage = "Die Passwörter stimmen nicht überein.";
        } else {
            $passwordHash = PASSWORD_HASH($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("SELECT * FROM users WHERE username=:username OR email=:email");
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":email", $email);
            $stmt->execute();

            $userAlreadyExists = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!$userAlreadyExists){
                // Registrieren, wenn weder Benutzername noch E-Mail vergeben sind
                registerUser($username, $email, $firstname, $lastname, $passwordHash);
            } else {
                // Fehlernachricht anpassen, je nachdem, was schon vergeben ist
                if ($userAlreadyExists['username'] == $username && $userAlreadyExists['email'] == $email) {
                    $errorMessage = "Benutzername und E-Mail sind bereits vergeben.";
                } elseif ($userAlreadyExists['username'] == $username) {
                    $errorMessage = "Der Benutzername ist bereits vergeben.";
                } elseif ($userAlreadyExists['email'] == $email) {
                    $errorMessage = "Die E-Mail-Adresse ist bereits vergeben.";
                }
            }
        }
    }

    function registerUser($username, $email, $firstname, $lastname, $password){
        global $conn;
        $stmt = $conn->prepare("INSERT INTO users(username, email, firstname, lastname, password) VALUES(:username, :email, :firstname, :lastname, :password)");
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":firstname", $firstname);
        $stmt->bindParam(":lastname", $lastname);
        $stmt->bindParam(":password", $password);
        $stmt->execute();
        session_start();
        $_SESSION["username"];
        header("Location: login.php");
    }

?>


<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrieren</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css">

    <script>
        // Funktion zur Überprüfung, ob die Passwörter übereinstimmen
        function checkPasswords() {
            var password = document.getElementById('password').value;
            var passwordRepeat = document.getElementById('passwordRepeat').value;
            var message = document.getElementById('passwordMessage');
            
            if (password !== passwordRepeat) {
                message.style.display = 'block';  // Fehlermeldung anzeigen
                message.textContent = 'Die Passwörter stimmen nicht überein.';
                message.classList.add('text-danger');
                message.classList.remove('text-success');
            } else {
                message.style.display = 'block';  // Erfolgsnachricht anzeigen
                message.textContent = 'Die Passwörter stimmen überein.';
                message.classList.add('text-success');
                message.classList.remove('text-danger');
            }
        }
    </script>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
    <div class="login-container p-4 rounded shadow">
        <div class="mb-3 d-flex align-items-center justify-content-center ">
            <img src="./img/Owl_logo.svg" height="60px" alt="">
        </div>
        <h2 class="text-center mb-4">Registrieren</h2>

        <!-- Fehlernachricht wird hier angezeigt, wenn vorhanden -->
        <?php if ($errorMessage): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>

        <form action="./register.php" method="POST">
            <!-- Vorname und Nachname nebeneinander -->
            <div class="mb-3 row">
                <div class="col-md-6">
                    <label for="firstname" class="form-label">Vorname</label>
                    <input type="text" class="form-control" id="firstname"  name="firstname" required>
                </div>
                <div class="col-md-6">
                    <label for="lastname" class="form-label">Nachname</label>
                    <input type="text" class="form-control" id="lastname"  name="lastname" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">Benutzername</label>
                <input type="text" class="form-control" id="username"  name="username" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-Mail</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Passwort</label>
                <input type="password" class="form-control" id="password" name="password" required oninput="checkPasswords()">
            </div>
            <div class="mb-3">
                <label for="passwordRepeat" class="form-label">Passwort wiederholen</label>
                <input type="password" class="form-control" id="passwordRepeat" name="passwordRepeat" required oninput="checkPasswords()">
                <!-- Fehlermeldung wird hier angezeigt, wenn die Passwörter nicht übereinstimmen -->
                <small id="passwordMessage" style="display:none;"></small>
            </div>
           
            <button type="submit" class="btn btn-primary w-100" name="submit">Registrieren</button>
            <div class="mt-3 text-center">
                <p>Hast du bereits ein Konto? <a href="./login.php">Anmelden</a></p>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
