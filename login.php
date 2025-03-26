<?php
    require "connection.php";
    $errorMessage = "";

    if(isset($_POST["submit"])){
        
        $username = $_POST["username"];
        $password = $_POST["password"];

        $stmt = $conn -> prepare("SELECT * FROM users WHERE username=:username");
        $stmt ->bindParam(":username", $username);
        $stmt ->execute();
        $userExists = $stmt ->fetch(PDO::FETCH_ASSOC);

            if($userExists) {
            $passwordHashed = $userExists["password"];
            $checkPassword = password_verify($password, $passwordHashed);

            if($checkPassword === true) {
                header("Location: home.php");
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/style.css">
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
    <div class="login-container p-4 rounded shadow">
        <div class="mb-3 d-flex align-items-center justify-content-center ">
            <img src="./img/Owl_logo.svg" height="60px" alt="">
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
            </div>
           
            <button type="submit" class="btn btn-primary w-100" name="submit">Anmelden</button>
            <div class="mt-3 text-center">
                <p>Hast du noch kein Konto? <a href="./register.php">Registrieren</a></p>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>