<?php
session_start();
require_once 'database.php';

// Vérifie si l'utilisateur est déjà connecté
if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Créer une instance de la classe Database
    $db = new Database();
    $pdo = $db->connect();

    // Préparer la requête d'insertion
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");

    // Exécuter la requête
    $stmt->execute([$username, $password]);

    // Rediriger vers la page de connexion
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Inscription - ToDoList</title>
</head>
<body>

<div class="header">
    <h1>ToDoList</h1>
</div>

<div class="container">
    <h2>Inscription</h2>

    <?php if (isset($error)) : ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <form action="register.php" method="post">
        <label for="username">Nom d'utilisateur:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Mot de passe:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">S'inscrire</button>
    </form>

    <p>Vous avez déjà un compte ? <a href="login.php">Connectez-vous ici</a>.</p>
</div>

</body>
</html>
