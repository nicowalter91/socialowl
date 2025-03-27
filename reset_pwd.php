<?php
    require 'connection.php';
    $errorMessage = "";
    $successMessage = "";

    // E-Mail und Token aus der URL holen
    if(isset($_GET['email']) && isset($_GET['reset_token'])) {
        $email = $_GET["email"];  
        $reset_token = $_GET['reset_token'];  
    }

    // Wenn das Formular abgesendet wird
    if(isset($_POST['reset'])) {

        // E-Mail und Passwörter aus dem Formular holen
        $email = $_POST["email"];
        $password = $_POST["password"];
        $passwordRepeat = $_POST["passwordRepeat"];

        // Überprüfen, ob die Passwörter übereinstimmen
        if ($password !== $passwordRepeat) {
            $errorMessage = "Die Passwörter stimmen nicht überein.";
        } else {
            $passwordHash = PASSWORD_HASH($password, PASSWORD_DEFAULT);

            try {
                // Passwort in der Datenbank aktualisieren und reset_token auf NULL setzen
                $result = updatePassword($passwordHash, $email, $reset_token);

                // Überprüfen, ob das Update erfolgreich war
                if ($result) {
                    $successMessage = "Passwort erfolgreich geändert";
                    header("Location: login.php"); 
                    exit();
                } else {
                    $errorMessage = "Fehler beim Zurücksetzen des Passworts. Möglicherweise existiert der Token nicht oder ist ungültig.";
                }
            } catch (PDOException $e) {
                // Fehlerbehandlung im Falle eines Datenbankfehlers
                $errorMessage = "Datenbankfehler: " . $e->getMessage();
            }
        }
    }

    
    function updatePassword($password, $email, $reset_token){
        global $conn;
        $stmt = $conn->prepare("UPDATE users SET password=:password, reset_token=NULL WHERE email=:email AND reset_token=:reset_token");
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":reset_token", $reset_token);
        return $stmt->execute();
    }
?>






<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/style.css">
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
        <h2 class="text-center mb-4">Passwort zurücksetzen</h2>

         <!-- Fehlernachricht wird hier angezeigt, wenn vorhanden -->
         <?php if ($errorMessage): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>

        <?php if ($successMessage): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $successMessage; ?>
            </div>
        <?php endif; ?>


        <form method="post" action="./reset_pwd.php">
          
            <div class="mb-3">
            <div class="mb-3">
                <label for="password" class="form-label">Neues Passwort</label>
                <input type="password" class="form-control" id="password" placeholder="Neues Passwort eingeben" name="password" required oninput="checkPasswords()">
            </div>
            <div class="mb-3">
                <label for="passwordRepeat" class="form-label">Neues Passwort wiederholen</label>
                <input type="password" class="form-control" id="passwordRepeat" placeholder="Neues Passwort wiederholen" name="passwordRepeat" required oninput="checkPasswords()">
                <!-- Fehlermeldung wird hier angezeigt, wenn die Passwörter nicht übereinstimmen -->
                <small id="passwordMessage" style="display:none;"></small>
                <input type="hidden" value="<?php echo $email ?>" name="email">
                <input type="hidden" value="<?php echo $reset_token ?>" name="reset_token">
            </div>
            </div>

           
            <button type="submit" class="btn btn-primary w-100" name="reset">Speichern</button>
           
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>