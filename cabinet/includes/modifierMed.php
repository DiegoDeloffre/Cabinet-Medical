<?php
	require 'dbh.php';
?>
<!DOCTYPE html>
<html>
<?php 
	require 'style.html';
	$id = $_POST['modif'];
	$req="SELECT * FROM medecin WHERE IdMedecin =?;";
	$stmt = mysqli_stmt_init($link);
	if(!mysqli_stmt_prepare($stmt, $req)){
		echo '<script type="text/javascript">';
		echo 'window.location.href = "accueil.php?error=sqlerror";';
		echo '</script>';
		exit();
	}
	else{
		mysqli_stmt_bind_param($stmt, "i", $id);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row = mysqli_fetch_assoc($result);
	}
?>


<body class="m-5">
<div class="row d-flex justify-content-center mb-3">
	<h1>Modifier un medecin</h1>
</div>
<!--Formulaire de modif de medecin-->
<div class="col-12 d-flex justify-content-center">
	<form action="modifierMed.php" method="post" class="col-7 m-0 p-0 myform">
		<div class="d-flex justify-content-end">
			<button type="submit" name="annuler" class="btn btn-danger ml-0 mybtn"><i class="fas fa-times"></i></button>
		</div>
		<div class="form-group d-flex justify-content-center pt-4">
			<label for="civilite" class="col-3">Civilité</label>
			<div class="col-5 d-inline-flex">
				<select class="form-control" name="civilite">
					<?php
						if($row['Civilite']=="Monsieur"){
					?>	
							<option value="Monsieur" selected>Monsieur</option>
				  			<option value="Madame">Madame</option>
				  	<?php
						}
						else{
					?>
							<option value="Monsieur">Monsieur</option>
				  			<option value="Madame" selected>Madame</option>
				  	<?php
						}
					?>
					
				</select>
			</div>
		</div>
		<div class="form-group d-flex justify-content-center">
			<label for="nom" class="col-3">Nom</label>
			<input type="text" name="nom" class="col-5" value="<?php echo $row['Nom'];?>">
		</div>
		<div class="form-group d-flex justify-content-center">
			<label for="prenom" class="col-3">Prenom</label>
			<input type="text" name="prenom" class="col-5" value="<?php echo $row['Prenom'];?>">
		</div>
		<div class="d-flex justify-content-center mb-4 mt-5">
			<button type="submit" name="modifier" class="btn btn-success" value="<?php echo $id; ?>"><i class="fas fa-check mr-2"></i>Modifier</button>
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
			/*traitement bouton modifier*/
			if(isset($_POST['modifier'])){
				$civilite = $_POST['civilite'];
				$nom = $_POST['nom'];
				$prenom = $_POST['prenom'];
				$id = $_POST['modifier'];

				/*test champs vides*/
				if(empty($civilite) || empty($nom) || empty($prenom)){
					echo '<script type="text/javascript">';
					echo 'window.location.href = "accueil.php?error=emptyfields";';
					echo '</script>';
					exit();
				}
				/*test */
				else{
					$req = "SELECT * FROM patient WHERE NumSecu=?;";
					$stmt = mysqli_stmt_init($link);
					if(!mysqli_stmt_prepare($stmt, $req)){
						echo '<script type="text/javascript">';
						echo 'window.location.href = "accueil.php?error=sqlerror";';
						echo '</script>';
						exit();
					}
					else{
						mysqli_stmt_bind_param($stmt, "s", $num_secu);
						mysqli_stmt_execute($stmt);
						$result = mysqli_stmt_get_result($stmt);
						$row = mysqli_fetch_assoc($result);
						if(!is_null($row['IdPatient']) && $row['IdPatient']!=$id){
							echo '<script type="text/javascript">';
							echo 'window.location.href = "accueil.php?error=numSecuUnavailable";';
							echo '</script>';
							exit();
						}
						/*insert*/
						else{
							$req = "UPDATE medecin SET Civilite=?,Nom=?,Prenom=? WHERE IdMedecin=?";
							$stmt = mysqli_stmt_init($link);
							if(!mysqli_stmt_prepare($stmt, $req)){
								echo '<script type="text/javascript">';
								echo 'window.location.href = "accueil.php?error=sqlerror";';
								echo '</script>';
								exit();
							}
							else{
								mysqli_stmt_bind_param($stmt, "sssi", $civilite,$nom,$prenom,$id);
								mysqli_stmt_execute($stmt);
								echo '<script type="text/javascript">';
								echo 'window.location.href = "accueil.php?success=modifMed";';
								echo '</script>';
							}
							mysqli_stmt_close($stmt);
							mysqli_close($link);
						}
					}
				}

				
			}



	?>

</body>
</html>