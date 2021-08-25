<!DOCTYPE html>
<html>
<?php
	require 'style.html';
	require 'dbh.php';
	if(isset($_SESSION['Id']) ){
	$row=0;

	function stat25($civilite){
		require 'dbh.php';
		$req = "SELECT * FROM patient WHERE Civilite=? AND DATE_FORMAT(FROM_DAYS(DATEDIFF(now(),DateNaissance)), '%Y') < 25";
		$stmt = mysqli_stmt_init($link);
		if(!mysqli_stmt_prepare($stmt, $req)){
			exit();
		}		
		else{
			mysqli_stmt_bind_param($stmt, "s", $civilite);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			$row = mysqli_num_rows($result);
			print($row);	
		}
		mysqli_stmt_close($stmt);
		mysqli_close($link);
	}
	

	function stat2550($civilite){
		require 'dbh.php';
		$req = "SELECT * FROM patient WHERE Civilite=? AND DATE_FORMAT(FROM_DAYS(DATEDIFF(now(),DateNaissance)), '%Y') > 25 AND DATE_FORMAT(FROM_DAYS(DATEDIFF(now(),DateNaissance)), '%Y') < 50";
		$stmt = mysqli_stmt_init($link);
		if(!mysqli_stmt_prepare($stmt, $req)){
			exit();
		}		
		else{
			mysqli_stmt_bind_param($stmt, "s", $civilite);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			$row = mysqli_num_rows($result);
			print($row);	
		}
		mysqli_stmt_close($stmt);
		mysqli_close($link);
	}

	function stat50($civilite){
		require 'dbh.php';
		$req = "SELECT * FROM patient WHERE Civilite=? AND DATE_FORMAT(FROM_DAYS(DATEDIFF(now(),DateNaissance)), '%Y') > 50";
		$stmt = mysqli_stmt_init($link);
		if(!mysqli_stmt_prepare($stmt, $req)){
			exit();
		}		
		else{
			mysqli_stmt_bind_param($stmt, "s", $civilite);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			$row = mysqli_num_rows($result);
			print($row);	
		}
		mysqli_stmt_close($stmt);
		mysqli_close($link);
	}
	
	
?>


<body>
	<div class="d-flex justify-content-center">
	<table border="2" class="table col-5">
		<thead class="thead-dark">
			<th>Tranche d'âge</th>
			<th>Nb Hommes</th>
			<th>Nb Femmes</th>
		</thead>
		<tbody>
				<tr>
					<td><?php echo "Moins de 25 ans";?></td>
					<td><?php stat25('Monsieur');?></td>
					<td><?php stat25('Madame');?></td>
				</tr>
				<tr>
					<td><?php echo "Entre 25 et 50 ans";?></td>
					<td><?php stat2550('Monsieur');?></td>
					<td><?php stat2550('Madame');?></td>
				</tr>
				<tr>
					<td><?php echo "Plus de 50 ans";?></td>
					<td><?php stat50('Monsieur');?></td>
					<td><?php stat50('Madame');?></td>
				</tr>		
		</tbody>		
	</table>
	</div>









	

	<div class="d-flex justify-content-center">
	<table border="2" class="table col-5">
		<thead class="thead-dark">
			<th>Medecin</th>
			<th>Nb Heures</th>
		</thead>
		<tbody>

			<?php



				$req=("SELECT m.Nom, m.Prenom, ROUND((SUM(r.DureeRdv)/100)/60,2) as Somme FROM medecin m, rdv r WHERE m.IdMedecin=r.IdMedecin GROUP BY Nom,Prenom");
				if( !$resquery=mysqli_query($link,$req) ){ 
					die("Error:".mysqli_errno($link).":".mysqli_error($link));
				} 
				else {
					while ($med = mysqli_fetch_assoc($resquery)) {
			?>

				<tr>
					<td><?php echo $med['Nom']." ".$med['Prenom'];;?></td>
					<td><?php echo $med['Somme'];?></td>
				</tr>
			<?php
					}
				}
			?>		
		</tbody>		
	</table>
	</div>
</body>
<?php
	}
	else{
		echo '<script type="text/javascript">';
		echo 'window.location.href = "../index.php";';
		echo '</script>';
	}
?>
</html>