<?php
    require "connection.php";
    $errorMessage = "";

    if(isset($_POST["reset"])){
        
        $email = $_POST["email"];
        $reset_token = md5(rand());

        $stmt = $conn -> prepare("UPDATE users SET reset_token=:reset_token WHERE email=:email");
        $stmt ->bindParam(":reset_token", $reset_token);
        $stmt ->bindParam(":email", $email);
        $stmt ->execute();

        if($stmt->execute()) {
            $reset_link = "http://localhost:8080/Social_App/reset_pwd.php";
            $reset_link .= "?email=" . $email;
            $reset_link .= "&reset_token=" . $reset_token;
    
            $errorMessage = "Mail wurde verschickt! ";
            
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
        <h2 class="text-center mb-4">Passwort zurücksetzen</h2>

         <!-- Fehlernachricht wird hier angezeigt, wenn vorhanden -->
         <?php if ($errorMessage): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="./reset_mail_send.php">
          
            <div class="mb-3">
            <div class="mb-3">
                <label for="email" class="form-label">E-Mail</label>
                <input type="email" class="form-control" id="email" placeholder="E-Mail eingeben" name="email" required>
            </div>
            </div>

           
            <button type="submit" class="btn btn-primary w-100" name="reset">Senden</button>

            <?php if ($errorMessage): ?>
            <div class="alert alert-light mt-5" role="alert">
                <?php echo $reset_link; ?>
            </div>
        <?php endif; ?>
           
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>