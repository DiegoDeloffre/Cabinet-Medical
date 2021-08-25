<?php
	session_start();
	require 'dbh.php';
?>
<!DOCTYPE html>
<html>
<?php 
	require 'style.html';
	if(isset($_SESSION['Id'])){
?>


<body class="m-5">
<div class="row d-flex justify-content-center mb-3">
	<h1>Ajouter un medecin</h1>
</div>
<!--Formulaire d'ajout de medecin-->
<div class="col-12 d-flex justify-content-center">
	<form action="ajoutMed.php" method="post" class="col-7 m-0 p-0 myform">
		<div class="d-flex justify-content-end">
			<button type="submit" name="annuler" class="btn btn-danger ml-0 mybtn"><i class="fas fa-times"></i></button>
		</div>
		<div class="form-group d-flex justify-content-center pt-4">
			<label for="civilite" class="col-3">Civilité</label>
			<div class="col-5 d-inline-flex">
				<select class="form-control" name="civilite">
					<option value="Monsieur">Monsieur</option>
				    <option value="Madame">Madame</option>
				</select>
			</div>
		</div>
		<div class="form-group d-flex justify-content-center">
			<label for="nom" class="col-3">Nom</label>
			<input type="text" name="nom" class="col-5">
		</div>
		<div class="form-group d-flex justify-content-center">
			<label for="prenom" class="col-3">Prenom</label>
			<input type="text" name="prenom" class="col-5">
		</div>
		<div class="d-flex justify-content-center mb-4 mt-5">
			<button type="submit" name="ajout" class="btn btn-success"><i class="fas fa-check mr-2"></i>Valider</button>
		</div>
	</form>
</div>

	<?php
			/*traitement bouton annuler*/
			if(isset($_POST['annuler'])){
				echo '<script type="text/javascript">';
				echo 'window.location.href = "accueil.php";';
				echo '</script>';
				exit();
			}
			/*traitement bouton ajout*/
			if(isset($_POST['ajout'])){
				$civilite = $_POST['civilite'];
				$nom = $_POST['nom'];
				$prenom = $_POST['prenom'];

				/*test champs vides*/
				if(empty($civilite) || empty($nom) || empty($prenom)){
					echo '<script type="text/javascript">';
					echo 'window.location.href = "accueil.php?error=emptyfields";';
					echo '</script>';
					exit();
				}
				/*test medecin existe deja*/
				else{
					$req = "SELECT * FROM medecin WHERE Nom=? AND Prenom=?;";
					$stmt = mysqli_stmt_init($link);
					if(!mysqli_stmt_prepare($stmt, $req)){
						echo '<script type="text/javascript">';
						echo 'window.location.href = "accueil.php?error=sqlerror";';
						echo '</script>';
						exit();
					}
					else{
						mysqli_stmt_bind_param($stmt, "ss", $nom, $prenom);
						mysqli_stmt_execute($stmt);
						$result = mysqli_stmt_get_result($stmt);
						if($row = mysqli_fetch_assoc($result)){
							echo '<script type="text/javascript">';
							echo 'window.location.href = "accueil.php?error=medecinAlreadyExist";';
							echo '</script>';
							exit();
						}
						/*insert*/
						else{

							$req = ("INSERT INTO medecin(Civilite,Nom,Prenom) VALUES(?,?,?);");
							$stmt = mysqli_stmt_init($link);
							if(!mysqli_stmt_prepare($stmt, $req)){
								echo '<script type="text/javascript">';
								echo 'window.location.href = "accueil.php?error=sqlerror";';
								echo '</script>';								
								exit();
							}
							else{
								mysqli_stmt_bind_param($stmt, "sss", $civilite,$nom,$prenom);
								mysqli_stmt_execute($stmt);
								echo '<script type="text/javascript">';
								echo 'window.location.href = "accueil.php?success=ajoutMed";';
								echo '</script>';							
							}
							mysqli_stmt_close($stmt);
							mysqli_close($link);
						}
					}
				}

				
			}


	}
	else{
		echo '<script type="text/javascript">';
		echo 'window.location.href = "../index.php";';
		echo '</script>';
	}
	?>

</body>
</html>