<?php

require 'config.php';

if(isset($_GET['section']))
{
	$section = htmlspecialchars($_GET['section']);
}
else
{
	$section = "";
}

if(isset($_POST['verif_submit']))
{
	if(!empty($_POST['verif_code']))
	{
		$verif_code = htmlspecialchars($_POST['verif_code']);
		$verif_req = $bdd->prepare('SELECT id FROM recuperation WHERE mail = ? AND code = ?');
		$verif_req->execute(array($_SESSION['recup_mail'],$verif_code));
		$verif_req = $verif_req->rowCount();
		if($verif_req == 1)
		{
			$up_req = $bdd->prepare('UPDATE recuperation SET confirme = 1 WHERE mail = ?');
			$up_req->execute(array($_SESSION['recup_mail']));
			header("Location:recuperation.php?section=changemdp");
		}
		else
		{
			$error = "Code invalide";
		}
	}
	else
	{
		$error = "Veuillez entrer votre code de confirmation";
	}
}

if(isset($_POST['change_submit']))
{
	if(isset($_POST['change_mdp'],$_POST['change_mdpc']))
	{
		$verif_confirm = $bdd->prepare('SELECT confirme FROM recuperation WHERE mail = ?');
		$verif_confirm->execute(array($_SESSION['recup_mail']));
		$verif_confirm = $verif_confirm->fetch();
		$verif_confirm = $verif_confirm['confirme'];
		if($verif_confirm == 1)
		{	
			$mdp = htmlspecialchars($_POST['change_mdp']);
			$mdpc = htmlspecialchars($_POST['change_mdpc']);

			if(!empty($mdp) AND !empty($mdpc))
			{
				if($mdp == $mdpc)
				{
					$mdp = sha1($mdp);
					$ins_mdp = $bdd->prepare('UPDATE utilisateurs SET password = ? WHERE mail = ?');
					$ins_mdp->execute(array($mdp,$_SESSION['recup_mail']));
					$del_req = $bdd->prepare('DELETE FROM recuperation WHERE mail = ?');
					$del_req->execute(array($_SESSION['recup_mail']));
					header('Location:index.php');
				}
				else
				{
					$error = "Vos mots de passes ne correspondent pas !";
				}
			}
			else
			{
				$error = "Veuillez remplir tous les champs !";
			}
		}
		else
		{
			$error = "Veuillez valider votre mail grâce au code de vérification qui vous a été envoyé par mail";
		}
	}
	else
	{
		$error = "Veuillez remplir tous les champs !";
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>GEO-INFOS</title>
	<meta charset="utf-8">
</head>
<body>
	<h2>Récupération mot de passe</h2>
		<?php if($section=='code') { ?>
		Un code de vérification vous a été envoyé à l'adresse mail : <?= $_SESSION['recup_mail'] ?><br><br>
		<form method="POST">
			<input type="text"  placeholder="Code de vérification" name="verif_code" autocomplete="off"/><br><br>
			<input type="submit" value="Valider" name="verif_submit" /><br><br>
		</form>
		<?php } else if ($section=="changemdp") { ?>
		Nouveau mot de passe pour <?= $_SESSION['recup_mail'] ?><br><br>		
		<form method="POST">
			<input type="password"  placeholder="Nouveau mot de passe" name="change_mdp" autocomplete="off"/><br><br>
			<input type="password"  placeholder="Confirmation du mot de passe" name="change_mdpc" autocomplete="off"/><br><br>
			<input type="submit" value="Valider" name="change_submit" /><br><br>
		</form>
		<?php } ?>
	<?php if(isset($error)) { echo '<font color="red">'.$error."</font>";} ?>
</body>
</html>