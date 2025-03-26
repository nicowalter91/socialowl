<?php
    require "connection.php";
    $errorMessage = "";

    if(isset($_POST["submit"])){
        
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = PASSWORD_HASH($_POST["password"], PASSWORD_DEFAULT);

        $stmt = $conn -> prepare("SELECT * FROM users WHERE username=:username OR email=:email");
        $stmt ->bindParam(":username", $username);
        $stmt ->bindParam(":email", $email);
        $stmt ->execute();

        $userAlreadyExists = $stmt->fetch(PDO::FETCH_ASSOC);


        if(!$userAlreadyExists){
            //Registrieren
            registerUser($username, $email, $password);
        } else {
            //User existiert bereits
            $errorMessage = "Der Benutzername ist bereits vergeben.";
        }
    }

    function registerUser($username, $email, $password){
        global $conn;
        $stmt = $conn ->prepare("INSERT INTO users(username, email, password) VALUES(:username, :email, :password)");
        $stmt ->bindParam(":username", $username);
        $stmt ->bindParam(":email", $email);
        $stmt ->bindParam(":password", $password);
        $stmt ->execute();
        header("Location: home.php");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
            <div class="mb-3">
                <label for="username" class="form-label">Benutzername</label>
                <input type="text" class="form-control" id="username" placeholder="Benutzernamen eingeben" name="username" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-Mail</label>
                <input type="email" class="form-control" id="email" placeholder="E-Mail eingeben" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Passwort</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" placeholder="Passwort eingeben" name="password" required>
                </div>
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