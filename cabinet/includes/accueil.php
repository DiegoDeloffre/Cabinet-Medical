<?php
	session_start()
?>
<!DOCTYPE html>
<html>
<?php 
	require 'style.html';
?>
<body class="m-5">
		
	<?php
	if( isset($_SESSION['Id'])){
	$error="";
	$success="";
	echo'<div class="d-flex justify-content-center mb-4">			
		<form action="accueil.php" method="post">
			<div class="p-0">
				<button class="btn btn-dark" type="submit" name="patient">Patient</button>
				<button class="btn btn-dark" type="submit" name="medecin">Medecin</button>
				<button class="btn btn-dark" type="submit" name="rdv">Rendez-vous</button>
				<button class="btn btn-dark" type="submit" name="stat">Statistiques</button>
			</div>
		</form>
					
		<div class="d-flex justify-content-end col-2">
			<form action="logout.php" method="post">
				<button class="btn btn-danger" type="submit" name="logout">Déconnexion</button>
			</form>
		</div>
	</div>';
			

			if(isset($_POST['medecin'])){
				include 'medecin.php';
				echo "<div class=\"d-flex justify-content-center\"><button class=\"btn btn-success\" onclick=window.location.href='ajoutMed.php';><i class=\"fas fa-plus mr-2\"></i>Nouveau Medecin</button></div>";
			}
			else if(isset($_POST['rdv'])){
				include 'rdv.php';
				echo "<div class=\"d-flex justify-content-center\"><button class=\"btn btn-success\" onclick=window.location.href='ajoutRdv.php';><i class=\"fas fa-plus mr-2\"></i>Nouveau Rendez-vous</button></div>";
			}
			else if(isset($_POST['stat'])){
				include 'stat.php';
			}
			else if(isset($_POST['patient'])){
				include 'patient.php';
				echo "<div class=\"d-flex justify-content-center\"><button class=\"btn btn-success\" onclick=window.location.href='ajoutPat.php';><i class=\"fas fa-plus mr-2\"></i>Nouveau Patient</button></div>";

			}
			else{
				if(isset($_GET['error'])){
					$error=$_GET["error"];
					if($error=="emptyfields"){
						echo "<div class=\"alert alert-danger d-flex justify-content-center\">Veuillez remplir tout les champs</div>";
					}
					else if($error=="sqlerror"){
						echo "<div class=\"alert alert-danger d-flex justify-content-center\">Erreur SQL</div>";
					}
					else if($error=="patientAlreadyExist"){
						echo "<div class=\"alert alert-danger d-flex justify-content-center\">Le patient existe déjà</div>";
					}
					else if($error=="medecinAlreadyExist"){
						echo "<div class=\"alert alert-danger d-flex justify-content-center\">Le médecin existe déjà</div>";
					}
					else if($error=="suppressionImpossible"){
						echo "<div class=\"alert alert-danger d-flex justify-content-center\">Le médecin ne peut pas être supprimé car il est le médecin référent d'au moins un patient</div>";
					}
					else if($error=="medecinAlreadyRdv"){
						echo "<div class=\"alert alert-danger d-flex justify-content-center\">Le médecin de ce patient à déjà un rendez-vous pour cet horaire</div>";
					}
					else if($error=="rdvEmpièteApres"){
						echo "<div class=\"alert alert-danger d-flex justify-content-center\">Le rendez-vous empiète sur celui d'après</div>";
					}
					else if($error=="rdvEmpièteAvant"){
						echo "<div class=\"alert alert-danger d-flex justify-content-center\">Le rendez-vous empiète sur celui d'avant</div>";
					}
				}
				else if(isset($_GET['success'])){
					$success=$_GET["success"];
					if($success=="suppressionPat"){
						echo "<div class=\"alert alert-primary d-flex justify-content-center\">Le patient a bien été supprimé ainsi que ses rendez-vous</div>";
					}
					else if($success=="suppressionMed"){
						echo "<div class=\"alert alert-primary d-flex justify-content-center\">Le médecin a bien été supprimé</div>";
					}
					else if($success=="suppressionRdv"){
						echo "<div class=\"alert alert-primary d-flex justify-content-center\">Le rendez-vous a bien été supprimé</div>";
					}
					else if($success=="ajoutPat"){
						echo "<div class=\"alert alert-primary d-flex justify-content-center\">Le patient a bien été ajouté</div>";
					}
					else if($success=="ajoutMed"){
						echo "<div class=\"alert alert-primary d-flex justify-content-center\">Le médecin a bien été ajouté</div>";
					}
					else if($success=="ajoutRdv"){
						echo "<div class=\"alert alert-primary d-flex justify-content-center\">Le rendez-vous a bien été ajouté</div>";
					}
					else if($success=="modifPat"){
						echo "<div class=\"alert alert-primary d-flex justify-content-center\">Le patient a bien été modifié</div>";
					}
					else if($success=="modifMed"){
						echo "<div class=\"alert alert-primary d-flex justify-content-center\">Le medecin a bien été modifié</div>";
					}
					else if($success=="login"){
						echo "<div class=\"alert alert-primary d-flex justify-content-center\">Bienvenue ".$_SESSION['Id']."</div>";
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
