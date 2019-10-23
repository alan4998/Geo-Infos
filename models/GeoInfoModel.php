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

/********** R E C U P E R A T I O N   M O T   D E   P A S S E ****************************************/

/* Lorsque l'on appuie sur le button pour recuperer le mot de passe */
if (isset($_POST['recup_csubmit'])) 
{
	if(!empty($_POST['recup_mail']))
	{
		$recup_mail = htmlspecialchars($_POST['recup_mail']);
		if(filter_var($recup_mail,FILTER_VALIDATE_EMAIL))
		{
			$mailexist = $bdd->prepare('SELECT id,Nom,Prenom FROM utilisateurs WHERE mail = ?');
			$mailexist->execute(array($recup_mail)); 
			$mailexist_count = $mailexist->rowCount();
			if($mailexist_count == 1)
			{
				$res = $mailexist->fetch();
				$recup_nom = $res['Nom'];
				$recup_prenom = $res['Prenom'];

				$_SESSION['recup_mail'] = $recup_mail;
				$recup_code = "";
				for ($i=0; $i < 8; $i++) 
				{ 
					$recup_code .= mt_rand(0,9);
				}

				$mail_recup_exist = $bdd->prepare('SELECT id FROM recuperation WHERE mail = ?');
				$mail_recup_exist->execute(array($recup_mail));
				$mail_recup_exist = $mail_recup_exist->rowCount();

				if($mail_recup_exist == 1)
				{
					$recup_insert = $bdd->prepare('UPDATE recuperation SET code = ? WHERE mail = ?');
					$recup_insert->execute(array($recup_code,$recup_mail));	
				}
				else
				{
					$recup_insert = $bdd->prepare('INSERT INTO recuperation(mail,code,confirme) VALUES (?,?,?)');
					$recup_insert->execute(array($recup_mail,$recup_code,0));	
				}
				/************** FORME DU MAIL *******************/

				$header="MIME-Version: 1.0\r\n";
				$header.='From:"GeoInfo.com"<support@primfx.com>'."\n";
				$header.='Content-Type:text/html; charset="utf-8"'."\n";
				$header.='Content-Transfer-Encoding: 8bit';
       
				$message = '
				<html>
				<head>
					<title>Récupération de mot de passe</title>
					<meta charset="utf-8" />
				</head>
				<body>
					<div align="left">Bonjour <b>'.$recup_nom.' '.$recup_prenom.'</b>,</div><br />
					Voici votre code de récupération : <b>'.$recup_code.'</b><br /><br />
					A bientôt 
					<br>
					<br>
					Ceci est un email automatique, merci de ne pas y répondre
					</div>
				</body>
				</html>
				';

				mail($recup_mail,"Récupération de mot de passe",$message,$header);
				header("Location:recuperation.php?section=code");
			}
			else
			{
				$erreur3 = "Cette adresse mail n'est pas enregistrée !";
			}
		}
		else
		{
			$erreur3 = "Adresse mail invalide !";
		}

	}
	else
	{
		$erreur3 = "Veuillez entrer votre adresse mail !";
	}
}