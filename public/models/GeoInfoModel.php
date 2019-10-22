<?php

require 'config.php';

/************************************ I N S C R I P T I O N ****************************************/

if (isset($_POST['forminscription'])) 
{
	if(!empty($_POST['nom']) AND !empty($_POST['prenom']) AND !empty($_POST['email']) AND !empty($_POST['mdp']))
	{
		$nom = htmlspecialchars($_POST['nom']);
		$prenom = htmlspecialchars($_POST['prenom']);
		$email = htmlspecialchars($_POST['email']);
		$mdp = sha1($_POST['mdp']);

		$nomlength = strlen($nom);
		$prenomlength =strlen($prenom);
		if($nomlength <= 255)
		{
			if($prenomlength <= 255)
			{
				if(filter_var($email, FILTER_VALIDATE_EMAIL))
				{
					$reqmail = $bdd->prepare("SELECT * FROM utilisateurs WHERE mail = ?");
					$reqmail->execute(array($email));
					$mailexist = $reqmail->rowCount();
					if($mailexist == 0)
					{
						$insertmbr = $bdd->prepare("INSERT INTO utilisateurs(Nom, Prenom, mail, password) VALUES(?,?,?,?)");
						$insertmbr->execute(array($nom,$prenom,$email,$mdp));
						$erreur = "Votre compte a bien été créé !";
					}
					else
					{
						$erreur = "Adresse mail déjà utilisée !";
					}
				}
				else
				{
					$erreur = "Votre adresse mail n'est pas valide !";		
				}
			}
			else
			{
				$erreur = "Votre prénom ne doit pas dépasser 255 caractères !";
			}
		}
		else
		{
			$erreur = "Votre nom ne doit pas dépasser 255 caractères !";
		}
	}
	else
	{
		$erreur = "Tous les champs doivent être complétés !";
	}
}

/************************************ C O N N E C T I O N ****************************************/

if (isset($_POST['formconnection'])) 
{
	if(!empty($_POST['emailconnect']) AND !empty($_POST['mdpconnect']))
	{
		$mailconnect = htmlspecialchars($_POST['emailconnect']);
		$mdpconnect = sha1($_POST['mdpconnect']);

		$requser = $bdd->prepare("SELECT * FROM utilisateurs WHERE mail = ? AND password = ?");
		$requser->execute(array($mailconnect,$mdpconnect));
		$userexist = $requser->rowCount();
		if($userexist == 1)
		{
			$userinfo = $requser->fetch();
			$_SESSION['id'] = $userinfo['id'];
			$_SESSION['nom'] = $userinfo['Nom'];
			$_SESSION['prenom'] = $userinfo['Prenom'];
			$_SESSION['mail'] = $userinfo['mail'];
			header("Location: profil.php?id=".$_SESSION['id']);
		}
		else
		{
			$erreur2 = "Adresse mail ou mot de passe incorrect !";
		}
	}
	else
	{
		$erreur2 = "Tous les champs doivent être complétés !";
	}
}