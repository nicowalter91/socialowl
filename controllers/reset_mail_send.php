<?php
require_once __DIR__ . '/../includes/config.php';
require_once INCLUDES . '/connection.php';
$errorMessage = "";

if (isset($_POST["reset"])) {

    $email = $_POST["email"];
    $reset_token = md5(rand());

    $stmt = $conn->prepare("UPDATE users SET reset_token=:reset_token WHERE email=:email");
    $stmt->bindParam(":reset_token", $reset_token);
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    if ($stmt->execute()) {
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
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="icon" href="<?= BASE_URL ?>/assets/img/Owl_logo.svg" type="image/x-icon">
</head>

<body class="d-flex align-items-center justify-content-center vh-100">
    <div class="login-container p-4 rounded shadow text-light bg-dark">
        <div class="mb-3 d-flex align-items-center justify-content-center ">
            <img src="<?= BASE_URL ?>/assets/img/Owl_logo.svg" height="60" alt="Social Owl Logo">
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
    <script src="<?= BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/script.js"></script>
</body>

</html>