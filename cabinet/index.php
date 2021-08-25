<!DOCTYPE html>
<html>
	<?php 
		require 'includes/style.html';
	?>

	<body class="m-5" style="padding-top: 100px">
	<div class="row d-flex justify-content-center mb-3">
		<h1>Authentification</h1>
	</div>
	<a href="signup.html">Ici</a>
	<div class="col-12 d-flex justify-content-center mb-3">	
		<form action="includes/login.php" method="post" class="col-6 m-0 p-5" style="background-color: #EEEEEE; border: solid black 1px">
			<div class="form-group d-flex justify-content-center">
				<input type="text" name="id" autocomplete="off" autofocus class="col-5" placeholder="Identifiant" style="text-align: center;">
			</div>
			<div class="form-group d-flex justify-content-center">
				<input type="password" name="pwd" class="col-5" placeholder="Mot de passe" style="text-align: center;">
			</div>
			<div class="d-flex justify-content-center mb-3 mt-5">
				<button type="submit" name="login-submit" class="btn btn-success">Connexion<i class="fas fa-sign-in-alt ml-2"></i></button>
			</div>
		</form>
	</div>



	<?php
		if(isset($_GET['error'])){
			$error=$_GET['error'];
			if($error=="emptyfields"){
				echo "<div class=\"alert alert-danger d-flex justify-content-center\">Veuillez remplir tout les champs</div>";
			}
			else if($error=="noUserFound"){
				echo "<div class=\"alert alert-danger d-flex justify-content-center\">Aucun utilisateur avec cet identifiant existe</div>";
			}
			else if($error=="wrongpwd"){
				echo "<div class=\"alert alert-danger d-flex justify-content-center\">Le mot de passe est incorrect</div>";
			}
			else if($error=="sqlerror"){
				echo "<div class=\"alert alert-danger d-flex justify-content-center\">Erreur SQL</div>";
			}
		}
	?>		
	</body>
</html>		
		
