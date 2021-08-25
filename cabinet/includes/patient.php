<?php
	
?>
<!DOCTYPE html>
<html>
<?php 
	require 'style.html';
	if(isset($_SESSION['Id'])){
?>


<body>
	<table border="2" class="table">
		<thead class="thead-dark">
			<th>Civilité</th>
			<th>Nom</th>
			<th>Prenom</th>
			<th>Adresse</th>
			<th>Ville</th>
			<th>Code Postal</th>
			<th>Date de Naissance</th>
			<th>Lieu de Naissance</th>
			<th>Numéro de Sécu</th>
			<th>Medecin Référent</th>
			<th>Modifier</th>
			<th>Supprimer</th>
		</thead>
		<tbody>
		<?php
			require 'dbh.php';
			$req=("SELECT p.IdPatient, p.Civilite, p.Nom, p.Prenom, p.Adresse, p.Ville, p.CodePostal, DATE_FORMAT(p.DateNaissance,'%d/%m/%Y') as DateNaissance, p.LieuNaissance, p.NumSecu, m.Nom as NomMedecin,m.Prenom as PrenomMedecin FROM patient p, medecin m WHERE m.IdMedecin=p.IdMedecin");
			if( !$resquery=mysqli_query($link,$req) ){ 
				die("Error:".mysqli_errno($link).":".mysqli_error($link));     
			}
			else { 
				while ($row = mysqli_fetch_assoc($resquery)) { 
		?>
				<tr>
					
					<td><?php echo $row['Civilite'];?></td>
					<td><?php echo $row['Nom'];?></td>
					<td><?php echo $row['Prenom'];?></td>
					<td><?php echo $row['Adresse'];?></td>
					<td><?php echo $row['Ville'];?></td>
					<td><?php echo $row['CodePostal'];?></td>
					<td><?php echo $row['DateNaissance'];?></td>
					<td><?php echo $row['LieuNaissance'];?></td>
					<td><?php echo $row['NumSecu'];?></td>
					<td><?php echo $row['NomMedecin']." ".$row['PrenomMedecin'];?></td>
					<td>
						<div class="d-flex justify-content-center">
						<form action='modifierPat.php' method='post'>
							<button type="submit" name="modif" value="<?php echo $row['IdPatient'];?>"><i class="fas fa-cog"></i></button>
						</form>
						</div>
					</td>
					<td>
						<div class="d-flex justify-content-center">
						<form action='supprimerPat.php' method='post'>
							<button type="submit" name="suppr" value="<?php echo $row['IdPatient'];?>"><i class="fas fa-trash"></i></button>
						</form>
						</div>
					</td>

				</tr>
		<?php
				}     
			}
	}
	else{
		echo '<script type="text/javascript">';
		echo 'window.location.href = "../index.php";';
		echo '</script>';
	}
		?>	
		</tbody>	
	</table>
</body>
</html>