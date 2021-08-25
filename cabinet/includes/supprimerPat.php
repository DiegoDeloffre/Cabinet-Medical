<?php
	
	require 'dbh.php';
	session_start();

	if(isset($_POST['suppr'])){
		$id = $_POST['suppr'];

		/*suppression des rendez-vous du patient avant de le supprimer*/
		$req = "DELETE FROM rdv WHERE IdPatient=?;";
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

			/*suppression du patient*/
			$req = "DELETE FROM patient WHERE IdPatient=?;";
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
				echo 'window.location.href = "accueil.php?success=suppressionPat";';
				echo '</script>';
			}
			mysqli_stmt_close($stmt);
			mysqli_close($link);
		}	
	}
?>