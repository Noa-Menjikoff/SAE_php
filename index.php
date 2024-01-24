<?php

    if(isset($_SESSION['user_id'])) {
        header("Location: questionnaires.php");
        exit;
    }

    require 'Action/auth.php';
?>

<!DOCTYPE html>
<html lang="fr">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="CSS/login.css" />
    <link rel="stylesheet" href="CSS/police.css" />
    <title>Connexion - Inscription</title>
    <script src="JS/login.js"></script>
    <script src="https://kit.fontawesome.com/b2318dca58.js" crossorigin="anonymous"></script>
<body>
	<div class="container" id="container">
		<div class="form-container sign-up-container">
			<form action="Action/auth.php" method="post">
				<h1>Créer un compte</h1>
				<span>Crée ton compte en utilisant ton adresse mail</span>
				<input name="username" type="text" placeholder="Name" />
				<input name="password" type="password" placeholder="Password" />
				<button type="submit" name="register" value="inscription">S'inscrire</button>
			</form>
		</div>
		<div class="form-container sign-in-container">
			<form action="Action/auth.php" method="post">
				<h1>Se connecter</h1>
				<span>Utilise ton email pour te connecter</span>
				<input name="username" type="text" placeholder="Name" />
				<input name="password" type="password" placeholder="Password" />
				<button type="submit" name="login" value="connexion">Se connecter</button>
			</form>
		</div>
		<div class="overlay-container">
			<div class="overlay">
				<div class="overlay-panel overlay-left">
					<h1 class="titre-gradiant">Content de te revoir</h1>
					<p>Pour rester connecter avec nous, connectes-toi en entrant tes données personnelles</p>
					<button class="ghost" id="signIn">Se connecter</button>
				</div>
				<div class="overlay-panel overlay-right">
					<h1 class="titre-gradiant">Salut !</h1>
					<p>Si tu souhaites acheter des billets, entres tes données personnelles pour t'inscrire !</p>
					<button class="ghost" id="signUp">S'inscrire</button>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
