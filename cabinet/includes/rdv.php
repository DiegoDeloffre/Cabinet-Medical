
<!DOCTYPE html>
<html>
<?php
	require 'style.html';
	require 'dbh.php';
?>

<body>

	<?php
			/*traitement du bouton ok*/
			if(isset($_POST['ok'])){
				include 'accueil.php';
	?>

				<form action="rdv.php" method="post" class="col-7 ml-5 p-0">
					<div class="form-group d-flex justify-content-center">
						<label class="col-3"></label>
							<div class="col-5 d-inline-flex">
								<select class="form-control" name="medecinSelect">
									<option value="all" selected><?php echo "Tous les medecins";?></option>
									<?php 
										require 'dbh.php';
										$req=("SELECT * FROM medecin");
									
										if( !$resquery=mysqli_query($link,$req) ){ 
											die("Error:".mysqli_errno($link).":".mysqli_error($link));     
										} else { 

											while ($med = mysqli_fetch_assoc($resquery)) { 
											
								?>
												
												<option value="<?php echo $med['IdMedecin'];?>"><?php echo $med['Nom']." ".$med['Prenom'];?></option>
								<?php
											
										}     
									}
								?>
								</select>


							</div>
							<button type="submit" name="ok" class="btn btn-success"><i class="fas fa-check mr-2"></i>OK</button>
					</div>
				</form>

				<div class="d-flex justify-content-center">
					<table border="2" class="table col-6">
						<thead class="thead-dark">
							<th>Patient</th>
							<th>Medecin</th>
							<th>Date</th>
							<th>Heure</th>
							<th>Durée</th>
							<th>Supprimer</th>
						</thead>
						<tbody>

		<?php

				$medecin = $_POST['medecinSelect'];
				if($medecin=='all'){
					$req=("SELECT r.IdRdv, p.IdPatient, p.Nom , p.Prenom, DATE_FORMAT(r.DateRdv,'%d/%m/%Y') as DateRdv, DATE_FORMAT(r.HeureRdv, '%H:%i') as HeureRdv, DATE_FORMAT(r.DureeRdv, '%i') as DureeRdv, m.Nom as NomMedecin, m.Prenom as PrenomMedecin FROM patient p, medecin m, rdv r WHERE m.IdMedecin=r.IdMedecin AND p.IdPatient=r.IdPatient ORDER BY DateRdv, HeureRdv");
				
					if( !$resquery=mysqli_query($link,$req) ){ 
						die("Error:".mysqli_errno($link).":".mysqli_error($link));     
					} 
					else { 
						while ($row = mysqli_fetch_assoc($resquery)) { 
			?>
							<tr>
								<td><?php echo $row['Nom']." ".$row['Prenom'];?></td>
								<td><?php echo $row['NomMedecin']." ".$row['PrenomMedecin'];?></td>
								<td><?php echo $row['DateRdv'];?></td>
								<td><?php echo $row['HeureRdv'];?></td>
								<td><?php echo $row['DureeRdv']." minutes";?></td>
								<td class="d-flex justify-content-center">
									<form action='supprimerRdv.php' method='post'>
										<button type="submit" name="suppr" value="<?php echo $row['IdRdv'];?>"><i class="fas fa-trash"></i></button>
									</form>
								</td>
							</tr>
			<?php
						}/*fin du while*/     
					}/*fin else*/
				}/*fin if medecin==all*/
				else{/*sinon requete avec un id de medecin*/

					$req=("SELECT r.IdRdv, p.IdPatient, p.Nom , p.Prenom, DATE_FORMAT(r.DateRdv,'%d/%m/%Y') as DateRdv, DATE_FORMAT(r.HeureRdv, '%H:%i') as HeureRdv, DATE_FORMAT(r.DureeRdv, '%i') as DureeRdv, m.Nom as NomMedecin, m.Prenom as PrenomMedecin FROM patient p, medecin m, rdv r WHERE m.IdMedecin=r.IdMedecin AND p.IdPatient=r.IdPatient AND m.IdMedecin=? ORDER BY DateRdv, HeureRdv");
					$stmt = mysqli_stmt_init($link);
					if(!mysqli_stmt_prepare($stmt, $req)){
						echo '<script type="text/javascript">';
						echo 'window.location.href = "accueil.php?error=sqlerror";';
						echo '</script>';
						exit();
					}
					else{
						mysqli_stmt_bind_param($stmt, "i", $medecin);
						mysqli_stmt_execute($stmt);
						$result = mysqli_stmt_get_result($stmt);
						while ($row = mysqli_fetch_assoc($result)) { 
		?>
							<tr>
								<td><?php echo $row['Nom']." ".$row['Prenom'];?></td>
								<td><?php echo $row['NomMedecin']." ".$row['PrenomMedecin'];?></td>
								<td><?php echo $row['DateRdv'];?></td>
								<td><?php echo $row['HeureRdv'];?></td>
								<td><?php echo $row['DureeRdv']." minutes";?></td>
								<td class="d-flex justify-content-center">
									<form action='supprimerRdv.php' method='post'>
										<button type="submit" name="suppr" value="<?php echo $row['IdRdv'];?>"><i class="fas fa-trash"></i></button>
									</form>
								</td>
							</tr>
		<?php
						} /*fin du while*/
					}/*fin du else*/
				}/*fin du "sinon requete avec id de medecin"*/
		?>
				</tbody>		
				</table>
				</div>
		<?php
				echo "<div class=\"d-flex justify-content-center\"><button class=\"btn btn-success\" onclick=window.location.href='ajoutRdv.php';><i class=\"fas fa-plus mr-2\"></i>Nouveau Rendez-vous</button></div>";
			}/*fin du if post ok*/
			else{/*on n'a pas appuyer sur ok affichage de base quand on arrive sur la page*/


		?>
				<form action="rdv.php" method="post" class="col-7 ml-5 p-0">
					<div class="form-group d-flex justify-content-center">
						<label class="col-3"></label>
							<div class="col-5 d-inline-flex">
								<select class="form-control" name="medecinSelect">
									<option value="all" selected><?php echo "Tous les medecins";?></option>
									<?php 
										require 'dbh.php';
										$req=("SELECT * FROM medecin");
									
										if( !$resquery=mysqli_query($link,$req) ){ 
											die("Error:".mysqli_errno($link).":".mysqli_error($link));     
										} else { 

											while ($med = mysqli_fetch_assoc($resquery)) { 
											
								?>
												
												<option value="<?php echo $med['IdMedecin'];?>"><?php echo $med['Nom']." ".$med['Prenom'];?></option>
								<?php
											
										}     
									}
								?>
								</select>


							</div>
							<button type="submit" name="ok" class="btn btn-success"><i class="fas fa-check mr-2"></i>OK</button>
					</div>
				</form>

				<div class="d-flex justify-content-center">
					<table border="2" class="table col-6">
						<thead class="thead-dark">
							<th>Patient</th>
							<th>Medecin</th>
							<th>Date</th>
							<th>Heure</th>
							<th>Durée</th>
							<th>Supprimer</th>
						</thead>
						<tbody>






		<?php

				$req=("SELECT r.IdRdv, p.IdPatient, p.Nom , p.Prenom, DATE_FORMAT(r.DateRdv,'%d/%m/%Y') as DateRdv, DATE_FORMAT(r.HeureRdv, '%H:%i') as HeureRdv, DATE_FORMAT(r.DureeRdv, '%i') as DureeRdv, m.Nom as NomMedecin, m.Prenom as PrenomMedecin FROM patient p, medecin m, rdv r WHERE m.IdMedecin=r.IdMedecin AND p.IdPatient=r.IdPatient ORDER BY DateRdv, HeureRdv");
				
			if( !$resquery=mysqli_query($link,$req) ){ 
				die("Error:".mysqli_errno($link).":".mysqli_error($link));     
			} 
			else { 
				while ($row = mysqli_fetch_assoc($resquery)) { 
		?>
					<tr>
						<td><?php echo $row['Nom']." ".$row['Prenom'];?></td>
						<td><?php echo $row['NomMedecin']." ".$row['PrenomMedecin'];?></td>
						<td><?php echo $row['DateRdv'];?></td>
						<td><?php echo $row['HeureRdv'];?></td>
						<td><?php echo $row['DureeRdv']." minutes";?></td>
						<td class="d-flex justify-content-center">
							<form action='supprimerRdv.php' method='post'>
								<button type="submit" name="suppr" value="<?php echo $row['IdRdv'];?>"><i class="fas fa-trash"></i></button>
							</form>
						</td>
					</tr>
		<?php
				}     
			}
			}




			
			
	
		?>
		
		</tbody>		
	</table>
	</div>

</body>
</html>