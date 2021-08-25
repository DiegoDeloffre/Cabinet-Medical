<?php
	
	require 'dbh.php';
	session_start();

	if(isset($_POST['suppr'])){

		$id = $_POST['suppr'];
		/*on verifie si le medecin n'est pas le medecin referent de patients*/
		$req="SELECT * FROM patient WHERE IdMedecin=?;";
		$stmt = mysqli_stmt_init($link);
		if(!mysqli_stmt_prepare($stmt, $req)){
			echo '<script type="text/javascript">';
			echo 'window.location.href = "accueil.php?error=sqlerror";';
			echo '</script>';
			exit();
		}
		/*il est medecin referent d'au moins une patient donc on ne peut pas le supprimer*/
		else{
			mysqli_stmt_bind_param($stmt, "i", $id);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			if($row = mysqli_fetch_assoc($result)){
				echo '<script type="text/javascript">';
				echo 'window.location.href = "accueil.php?error=suppressionImpossible";';
				echo '</script>';
				exit();
			}
			else{
				

				/*suppression du medecin*/
				$req = "DELETE FROM medecin WHERE IdMedecin=?;";
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
					echo '<script type="text/javascript">';
					echo 'window.location.href = "accueil.php?success=suppressionMed";';
					echo '</script>';
				}
				mysqli_stmt_close($stmt);
				mysqli_close($link);
			}
		}
		
	}
?>