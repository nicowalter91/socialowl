<?php

require "connection.php";
$errorMessage = "";

if (isset($_POST["submit"])) {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=:username");
    $stmt->bindParam(":username", $username);
    $stmt->execute();
    $userExists = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userExists) {
        $passwordHashed = $userExists["password"];
        $checkPassword = password_verify($password, $passwordHashed);

        if ($checkPassword === true) {

            session_start();
            $_SESSION["username"] = $userExists["username"];

            if (isset($_POST["remember_me"]) && $_POST["remember_me"] == "1") {
                $remember_token = md5(rand());

                $stmt = $conn->prepare("UPDATE users SET remember_token=:remember_token WHERE username=:username");
                $stmt->bindParam(":username", $username);
                $stmt->bindParam(":remember_token", $remember_token);
                $stmt->execute();
                setcookie("remember_token", $remember_token, time() + (3600 * 24 * 365));
            }

            header("Location: welcome.php");
        } else {
            $errorMessage = "Falscher Benutzername oder Passwort";
        }
    }
}

?>



<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">

</head>

<body class="d-flex align-items-center justify-content-center vh-100">
    <div class="login-container p-4 rounded shadow text-light bg-dark">
        <div class="mb-3 d-flex align-items-center justify-content-center ">
            <img src="/Social_App/assets/img/Owl_logo.svg" height="60px" alt="">
        </div>
        <h2 class="text-center mb-4">Anmelden</h2>

        <!-- Fehlernachricht wird hier angezeigt, wenn vorhanden -->
        <?php if ($errorMessage): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="./login.php">
            <div class="mb-3">
                <label for="username" class="form-label">Benutzername</label>
                <input type="text" class="form-control" id="username" placeholder="Benutzername eingeben" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Passwort</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" placeholder="Passwort eingeben" name="password" required>
                </div>
                <div class="mt-1">
                    <p><a href="./reset_mail_send.php">Passwort vergessen?</a></p>
                </div>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="rememberMe" name="remember_me" value="1">
                <label class="form-check-label" for="rememberMe">Angemeldet bleiben</label>
            </div>

            <button type="submit" class="btn btn-primary w-100" name="submit">Anmelden</button>
            <div class="mt-3 text-center">
                <p>Hast du noch kein Konto? <a href="./register.php">Registrieren</a></p>
            </div>
        </form>
    </div>
    <script src="./js/bootstrap.bundle.min.js"></script>
</body>

</html>