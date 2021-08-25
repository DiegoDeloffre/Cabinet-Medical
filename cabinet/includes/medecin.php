
<!DOCTYPE html>
<html>
<?php 
	require 'style.html';
	if( isset($_SESSION['Id'])){
?>


<body>
	<div class="d-flex justify-content-center">
	<table border="2" class="table col-6">
		<thead class="thead-dark">
			<th>Civilité</th>
			<th>Nom</th>
			<th>Prenom</th>
			<th>Modifier</th>
			<th>Supprimer</th>
		</thead>
		<tbody>
		<?php
			require 'dbh.php';
			$req=("SELECT * FROM medecin");
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
					<td>
						<div class="d-flex justify-content-center">
						<form action='modifierMed.php' method='post'>
							<button type="submit" name="modif" value="<?php echo $row['IdMedecin'];?>"><i class="fas fa-cog"></i></button>
						</form>
						</div>
					</td>
					<td>
						<div class="d-flex justify-content-center">
						<form action='supprimerMed.php' method='post'>
							<button type="submit" name="suppr" value="<?php echo $row['IdMedecin'];?>"><i class="fas fa-trash"></i></button>
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
	</div>
</body>
</html>