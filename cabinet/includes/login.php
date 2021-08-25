<?php

	if(isset($_POST['login-submit'])){
		
		require 'dbh.php';

		$id = $_POST['id'];
		$pwd = $_POST['pwd'];

		if(empty($id) || empty($pwd)){
			echo '<script type="text/javascript">';
			echo 'window.location.href = "../index.php?error=emptyfields";';
			echo '</script>';
			exit();
		}
		else{
			$req = "SELECT * FROM util WHERE IdUtil=?;";
			$stmt = mysqli_stmt_init($link);
			if(!mysqli_stmt_prepare($stmt, $req)){
				echo '<script type="text/javascript">';
				echo 'window.location.href = "../index.php?error=sqlerror";';
				echo '</script>';
				exit();
			}
			else{
				mysqli_stmt_bind_param($stmt, "s", $id);
				mysqli_stmt_execute($stmt);
				$result = mysqli_stmt_get_result($stmt);
				if($row = mysqli_fetch_assoc($result)){

					$pwdCheck = password_verify($pwd, $row['PwdUtil']);
					if($pwdCheck == false){
						echo '<script type="text/javascript">';
						echo 'window.location.href = "../index.php?error=wrongpwd";';
						echo '</script>';
						exit();
					}
					else if($pwdCheck == true){
						session_start();
						$_SESSION['Id']=$row['IdUtil'];
						echo '<script type="text/javascript">';
						echo 'window.location.href = "../includes/accueil.php?success=login";';
						echo '</script>';
						exit();

					}
					else{
						echo '<script type="text/javascript">';
						echo 'window.location.href = "../index.php?error=wrongpwd";';
						echo '</script>';
						exit();
					}

				}
				else{
					echo '<script type="text/javascript">';
					echo 'window.location.href = "../index.php?error=noUserFound";';
					echo '</script>';
					exit();
				}
			}
		}
	}
	else{
		echo '<script type="text/javascript">';
		echo 'window.location.href = "../index.php?error=noUserFound";';
		echo '</script>';
		header("Location:../index.php");
	}