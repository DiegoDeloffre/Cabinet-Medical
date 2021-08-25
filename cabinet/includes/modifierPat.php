<?php
	require 'dbh.php';
?>
<!DOCTYPE html>
<html>
<?php 
	require 'style.html';
	$id = $_POST['modif'];
	$req="SELECT * FROM patient WHERE IdPatient =?;";
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
	<h1>Modifier un patient</h1>
</div>
<!--Formulaire de modif de patient-->
<div class="col-12 d-flex justify-content-center">
	<form action="modifierPat.php" method="post" class="col-7 m-0 p-0 myform">
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
		<div class="form-group d-flex justify-content-center">
			<label for="adresse" class="col-3">Adresse</label>
			<input type="text" name="adresse" class="col-5" value="<?php echo $row['Adresse'];?>">
		</div>
		<div class="form-group d-flex justify-content-center">
			<label for="ville" class="col-3">Ville</label>
			<input type="text" name="ville" class="col-5" value="<?php echo $row['Ville'];?>">
		</div>
		<div class="form-group d-flex justify-content-center">
			<label for="code" class="col-3">Code Postal</label>
			<input type="text" name="code" class="col-5" value="<?php echo $row['CodePostal'];?>">
		</div>
		<div class="form-group d-flex justify-content-center">
			<label for="date-naissance" class="col-3">Date de Naissance</label>
			<input type="date" name="date-naissance" class="col-5" value="<?php echo $row['DateNaissance'];?>">
		</div>
		<div class="form-group d-flex justify-content-center">
			<label for="lieu-naissance" class="col-3">Lieu de Naissance</label>
			<input type="text" name="lieu-naissance" class="col-5" value="<?php echo $row['LieuNaissance'];?>">
		</div>
		<div class="form-group d-flex justify-content-center">
			<label for="num-secu" class="col-3">Numéro Sécu</label>
			<input type="text" name="num-secu" class="col-5" value="<?php echo $row['NumSecu'];?>">
		</div>
		<div class="form-group d-flex justify-content-center">
			<label for="medecin-ref" class="col-3">Medecin Référent</label>
			<div class="col-5 d-inline-flex">

				<select class="form-control" name="medecin-ref">
					<?php 
						$req=("SELECT * FROM medecin");
					
						if( !$resquery=mysqli_query($link,$req) ){ 
							die("Error:".mysqli_errno($link).":".mysqli_error($link));     
						} else { 
							while ($med = mysqli_fetch_assoc($resquery)) { 
								if ($row['IdMedecin']==$med['IdMedecin']) {
					?>
									<option value="<?php echo $med['IdMedecin'];?>" selected><?php echo $med['Nom']." ".$med['Prenom'];?></option>
					<?php
								}
								else{
					?>
									<option value="<?php echo $med['IdMedecin'];?>"><?php echo $med['Nom']." ".$med['Prenom'];?></option>
					<?php
								}
							}     
						}
					?>
				</select>
			</div>
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
				$adresse = $_POST['adresse'];
				$ville = $_POST['ville'];
				$code = $_POST['code'];
				$date_naissance = $_POST['date-naissance'];
				$lieu_naissance = $_POST['lieu-naissance'];
				$num_secu = $_POST['num-secu'];
				$medecin_ref = $_POST['medecin-ref'];
				$id = $_POST['modifier'];

				/*test champs vides*/
				if(empty($civilite) || empty($nom) || empty($prenom) || empty($adresse) || empty($ville) || empty($code) || empty($date_naissance) || empty($lieu_naissance) || empty($num_secu) || empty($medecin_ref) ){
					echo '<script type="text/javascript">';
					echo 'window.location.href = "accueil.php?error=emptyfields";';
					echo '</script>';
					exit();
				}
				/*test num secu*/
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
							$req = "UPDATE patient SET Civilite=?,Nom=?,Prenom=?,Adresse=?,Ville=?,CodePostal=?,DateNaissance=?,LieuNaissance=?,NumSecu=?,IdMedecin=? WHERE IdPatient=?";
							$stmt = mysqli_stmt_init($link);
							if(!mysqli_stmt_prepare($stmt, $req)){
								echo '<script type="text/javascript">';
								echo 'window.location.href = "accueil.php?error=sqlerror";';
								echo '</script>';
								exit();
							}
							else{
								mysqli_stmt_bind_param($stmt, "sssssssssii", $civilite,$nom,$prenom,$adresse,$ville,$code,$date_naissance,$lieu_naissance,$num_secu,$medecin_ref,$id);
								mysqli_stmt_execute($stmt);

								$req="UPDATE rdv SET IdMedecin=? WHERE IdPatient=?";
								$stmt = mysqli_stmt_init($link);
								if(!mysqli_stmt_prepare($stmt, $req)){
									echo '<script type="text/javascript">';
									echo 'window.location.href = "accueil.php?error=sqlerror";';
									echo '</script>';
									exit();
								}
								else{
									mysqli_stmt_bind_param($stmt, "ii", $medecin_ref, $id);
									mysqli_stmt_execute($stmt);
									echo '<script type="text/javascript">';
									echo 'window.location.href = "accueil.php?success=modifPat";';
									echo '</script>';
								}
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