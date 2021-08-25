<?php
	require 'dbh.php';
?>
<!DOCTYPE html>
<html>
<?php 
	require 'style.html';
?>


<body class="m-5">
<div class="row d-flex justify-content-center mb-3">
	<h1>Ajouter un rendez-vous</h1>
</div>
<!--Formulaire d'ajout de rendez-vous-->
<div class="col-12 d-flex justify-content-center">
	<form action="ajoutRdv.php" method="post" class="col-7 m-0 p-0 myform">
		<div class="d-flex justify-content-end">
			<button type="submit" name="annuler" class="btn btn-danger ml-0 mybtn"><i class="fas fa-times"></i></button>
		</div>
		<div class="form-group d-flex justify-content-center">
			<label for="patient" class="col-3">Patient</label>
			<div class="col-5 d-inline-flex">
				<!-- selection du patient parmis ceux de la BD-->
				<select class="form-control" name="patient">
					<?php 
						$req=("SELECT * FROM patient");
						if( !$resquery=mysqli_query($link,$req) ){ 
							die("Error:".mysqli_errno($link).":".mysqli_error($link));     
						}
						else { 
							while ($row = mysqli_fetch_assoc($resquery)) { 
					?>
								<option value="<?php echo $row['IdPatient'];?>"><?php echo $row['Nom']." ".$row['Prenom'];?></option>
					<?php
							}  
						}
					?>
				</select>
			</div>
		</div>
		<div class="form-group d-flex justify-content-center">
			<label for="date" class="col-3">Date</label>
			<input type="date" name="date" class="col-5">
		</div>
		<div class="form-group d-flex justify-content-center">
			<label for="heure" class="col-3">Heure</label>
			<input type="time" min="09:00" max="18:00" name="heure" class="col-5">
		</div>
		<div class="form-group d-flex justify-content-center">
			<label for="duree" class="col-3">Durée</label>
			<div class="col-5 d-inline-flex">
				<select class="form-control" name="duree">
					<option value="00:15">15 minutes</option>
				    <option value="00:30" selected>30 minutes</option>
				    <option value="00:45">45 minutes</option>
				</select>
			</div>
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
			/*traitement bouton ajouter*/
			if(isset($_POST['ajout'])){
				$patient = $_POST['patient'];
				$date = $_POST['date'];
				$heure = $_POST['heure'];
				$duree = $_POST['duree'];

				

				/*test champs vides*/
				if(empty($patient) || empty($date) || empty($heure) || empty($duree)){
					echo '<script type="text/javascript">';
					echo 'window.location.href = "accueil.php?error=emptyfields";';
					echo '</script>';
					exit();
				}
				/*test medecin référent a déjà un rdv*/
				else {
					$req = "SELECT * FROM rdv WHERE DateRdv=? AND HeureRdv=? AND IdMedecin=(SELECT IdMedecin FROM patient WHERE IdPatient=?);";
					$stmt = mysqli_stmt_init($link);
					if(!mysqli_stmt_prepare($stmt, $req)){

						echo '<script type="text/javascript">';
						echo 'window.location.href = "accueil.php?error=sqlerror";';
						echo '</script>';
						exit();
					}
					else{
						mysqli_stmt_bind_param($stmt, "ssi", $date, $heure, $patient);
						mysqli_stmt_execute($stmt);
						$result = mysqli_stmt_get_result($stmt);
						if($row = mysqli_fetch_assoc($result)){
							echo '<script type="text/javascript">';
							echo 'window.location.href = "accueil.php?error=medecinAlreadyRdv";';
							echo '</script>';
							exit();
						}
						/*test horaires valides*/
						/*select du rendez-vous avant celui que l'on veut insert*/
						else{
							$req = "SELECT * FROM rdv WHERE IdMedecin=(SELECT IdMedecin FROM patient WHERE IdPatient=?) AND DateRdv=? AND HeureRdv < ? ORDER BY HeureRdv DESC LIMIT 1;";
							$stmt = mysqli_stmt_init($link);
							if(!mysqli_stmt_prepare($stmt, $req)){
								echo '<script type="text/javascript">';
								echo 'window.location.href = "accueil.php?error=sqlerror";';
								echo '</script>';
								exit();
							}
							else{
								mysqli_stmt_bind_param($stmt, "iss", $patient, $date, $heure);
								mysqli_stmt_execute($stmt);
								$result = mysqli_stmt_get_result($stmt);
								$row = mysqli_fetch_assoc($result);
								if(is_null($row)){
									/*aucun rdv avant*/
									/* appel methode pour verifier le potentiel rdv d'apres*/
									verifFuture($patient,$date,$heure,$duree);
								}
								/*test data*/
								else{
									
									$hour=$row['HeureRdv'];
									$duration=$row['DureeRdv'];
									list($hours, $minutes, $seconds) = sscanf($duration, '%d:%d:%d');
									$interval = new DateInterval(sprintf('PT%dH%dM%dS', $hours, $minutes, $seconds));
									$dateTime = new DateTime($hour);
									$dateTime->add($interval);
									$dateTimeC = new DateTime($heure);
									if($dateTime->format('H:i:s') < $dateTimeC->format('H:i:s')){
										/*le rendez-vous n'empiète pas sur celui d'avant*/
										/*appel methode pour verifier le potentiel rdv d'apres*/
										verifFuture($patient,$date,$heure,$duree);
									}
									else if($dateTime->format('H:i:s') > $dateTimeC->format('H:i:s')){
										/*le rendez-vous empiète sur celui d'avant*/
										/*on redirige avec un message d'erreur*/
										echo '<script type="text/javascript">';
										echo 'window.location.href = "accueil.php?error=rdvEmpièteAvant";';
										echo '</script>';
										exit();
									}
									else{
										/*le rendez-vous n'empiète pas sur celui d'avant*/
										/*appel methode pour verifier le potentiel rdv d'apres*/
										verifFuture($patient,$date,$heure,$duree);
									}
								}
							}
						}
					}	
				}
			}

			/*verifie si le rendez-vous voulant etre insert n'empiète pas sur le rendez-vous suivant*/
			function verifFuture($patient, $date, $heure, $duree){
				require 'dbh.php';

				/*select du id medecin pour plus tard*/
				$medecin;
				$req="SELECT IdMedecin FROM patient WHERE IdPatient=?";
				$stmt = mysqli_stmt_init($link);
				if(!mysqli_stmt_prepare($stmt, $req)){
					echo '<script type="text/javascript">';
					echo 'window.location.href = "accueil.php?error=sqlerror";';
					echo '</script>';
					exit();
				}
				else{
					mysqli_stmt_bind_param($stmt, "i", $patient);
					mysqli_stmt_execute($stmt);
					$result = mysqli_stmt_get_result($stmt);
					$row = mysqli_fetch_assoc($result);
					$medecin = $row['IdMedecin'];
				}

				$req = "SELECT * FROM rdv WHERE IdMedecin=(SELECT IdMedecin FROM patient WHERE IdPatient=?) AND DateRdv=? AND HeureRdv > ? ORDER BY HeureRdv ASC LIMIT 1;";
				$stmt = mysqli_stmt_init($link);
				if(!mysqli_stmt_prepare($stmt, $req)){
									
				}
				else{
					mysqli_stmt_bind_param($stmt, "iss", $patient, $date, $heure);
					mysqli_stmt_execute($stmt);
					$result = mysqli_stmt_get_result($stmt);
					$row = mysqli_fetch_assoc($result);
					if(is_null($row)){
						/*aucun rdv après*/
						/*insert*/
						insertRdv($medecin,$patient,$date,$heure,$duree);
					}
					/*test data*/
					else{
						$hour=$row['HeureRdv'];
						list($hours, $minutes, $seconds) = sscanf($duree, '%d:%d:%d');
						$interval = new DateInterval(sprintf('PT%dH%dM%dS', $hours, $minutes, $seconds));
						$dateTime = new DateTime($hour);
						$dateTimeC = new DateTime($heure);
						$dateTimeC->add($interval);
						if($dateTimeC->format('H:i:s') < $dateTime->format('H:i:s')){
							/*le rendez-vous n'empiète pas sur celui d'après*/
							/*insert*/
							insertRdv($medecin,$patient,$date,$heure,$duree);				
						}
						else if($dateTimeC->format('H:i:s') > $dateTime->format('H:i:s')){
							/*le rendez-vous empiète sur celui d'après*/
							/*on redirige avec un message d'erreur*/
							echo '<script type="text/javascript">';
							echo 'window.location.href = "accueil.php?error=rdvEmpièteApres";';
							echo '</script>';
							exit();
						}
						else{
							/*le rendez-vous n'empiète pas sur celui d'après*/
							/*insert*/
							insertRdv($medecin,$patient,$date,$heure,$duree);
						}
					}
				}
			}

			/*insert*/
			function insertRdv($medecin,$patient,$date,$heure,$duree){
				require 'dbh.php';
				$req = "INSERT INTO rdv(IdMedecin,IdPatient,DateRdv,HeureRdv,DureeRdv) VALUES(?,?,?,?,?);";
				$stmt = mysqli_stmt_init($link);
				if(!mysqli_stmt_prepare($stmt, $req)){
					echo '<script type="text/javascript">';
					echo 'window.location.href = "accueil.php?error=sqlerror";';
					echo '</script>';
					exit();
				}		
				else{
					mysqli_stmt_bind_param($stmt, "iisss", $medecin ,$patient,$date,$heure,$duree);
					mysqli_stmt_execute($stmt);
					echo '<script type="text/javascript">';
					echo 'window.location.href = "accueil.php?success=ajoutRdv";';
					echo '</script>';
				}
				mysqli_stmt_close($stmt);
				mysqli_close($link);
			}
?>

</body>
</html>