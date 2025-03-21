<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrieren</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
    <div class="login-container p-4 rounded shadow">
        <h2 class="text-center mb-4">Registrieren</h2>
        <form>
            <div class="mb-3">
                <label for="username" class="form-label">Benutzername</label>
                <input type="text" class="form-control" id="username" placeholder="Benutzernamen eingeben" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-Mail</label>
                <input type="email" class="form-control" id="email" placeholder="E-Mail eingeben" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Passwort</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" placeholder="Passwort eingeben" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="confirmPassword" class="form-label">Passwort bestätigen</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="confirmPassword" placeholder="Passwort bestätigen" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Registrieren</button>
            <div class="mt-3 text-center">
                <p>Hast du bereits ein Konto? <a href="signin.php">Anmelden</a></p>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>