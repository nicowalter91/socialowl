<?php
require_once __DIR__ . '/../includes/config.php';
require_once INCLUDES . '/connection.php';
$errorMessage = "";
$successMessage = "";

// E-Mail und Token aus der URL holen
if (isset($_GET['email']) && isset($_GET['reset_token'])) {
    $email = $_GET["email"];
    $reset_token = $_GET['reset_token'];
}

// Wenn das Formular abgesendet wird
if (isset($_POST['reset'])) {

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
            $result = updatePassword($passwordHash, $email);

            // Überprüfen, ob das Update erfolgreich war
            if ($result) {
                $successMessage = "Passwort erfolgreich geändert";
                // header("Location: login.php"); 
                // exit();
            } else {
                $errorMessage = "Fehler beim Zurücksetzen des Passworts. Möglicherweise existiert der Token nicht oder ist ungültig.";
            }
        } catch (PDOException $e) {
            // Fehlerbehandlung im Falle eines Datenbankfehlers
            $errorMessage = "Datenbankfehler: " . $e->getMessage();
        }
    }
}


function updatePassword($password, $email)
{
    global $conn;
    $null = NULL; // Setze auf NULL statt einem leeren String
    $stmt = $conn->prepare("UPDATE users SET password=:password, reset_token=:reset WHERE email=:email");
    $stmt->bindParam(":password", $password);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":reset", $null, PDO::PARAM_NULL);  // Übergabe von NULL
    return $stmt->execute();
}

?>






<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="icon" href="<?= BASE_URL ?>/assets/img/Owl_logo.svg" type="image/x-icon">
    <script>
        // Funktion zur Überprüfung, ob die Passwörter übereinstimmen
        function checkPasswords() {
            var password = document.getElementById('password').value;
            var passwordRepeat = document.getElementById('passwordRepeat').value;
            var message = document.getElementById('passwordMessage');

            if (password !== passwordRepeat) {
                message.style.display = 'block'; // Fehlermeldung anzeigen
                message.textContent = 'Die Passwörter stimmen nicht überein.';
                message.classList.add('text-danger');
                message.classList.remove('text-success');
            } else {
                message.style.display = 'block'; // Erfolgsnachricht anzeigen
                message.textContent = 'Die Passwörter stimmen überein.';
                message.classList.add('text-success');
                message.classList.remove('text-danger');
            }
        }
    </script>
</head>

<body class="d-flex align-items-center justify-content-center vh-100">
    <div class="login-container p-4 rounded shadow text-ligh bg-dark">
        <div class="mb-3 d-flex align-items-center justify-content-center ">
            <img src="<?= BASE_URL ?>/assets/img/Owl_logo.svg" height="60" alt="Social Owl Logo">
        </div>
        <h2 class="text-center text-light mb-4">Passwort zurücksetzen</h2>

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
                    <label for="password" class="form-label text-light">Neues Passwort</label>
                    <input type="password" class="form-control" id="password" placeholder="Neues Passwort eingeben" name="password" required oninput="checkPasswords()">
                </div>
                <div class="mb-3">
                    <label for="passwordRepeat" class="form-label text-light">Neues Passwort wiederholen</label>
                    <input type="password" class="form-control" id="passwordRepeat" placeholder="Neues Passwort wiederholen" name="passwordRepeat" required oninput="checkPasswords()">
                    <!-- Fehlermeldung wird hier angezeigt, wenn die Passwörter nicht übereinstimmen -->
                    <small id="passwordMessage" style="display:none;"></small>
                    <input type="hidden" value="<?php echo $email ?>" name="email">
                    <input type="hidden" value="<?php echo $reset_token ?>" name="reset_token">
                </div>
            </div>


            <button type="submit" class="btn btn-primary w-100" name="reset">Speichern</button>
            <div class="mt-3 text-center text-light">
                <p>Zurück zum <a href="./login.php">Login</a></p>
            </div>
        </form>
    </div>
    <script src="<?= BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/script.js"></script>

</body>

</html>