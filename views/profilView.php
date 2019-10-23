<!DOCTYPE html>
<html>
<head>
	<title>GEO-INFOS</title>
	<meta charset="utf-8">
</head>
<body>
	<?php
	if(isset($_GET['id']) AND $_GET['id'] > 0)
		{
			$getid = intval($_GET['id']);
			$requser = $bdd->prepare('SELECT * FROM utilisateurs WHERE id=?');
			$requser->execute(array($getid));
			$userinfo = $requser->fetch();
		}
	?>


	<div align="left">
		<h2>Profil</h2>
		Nom : <?php echo $userinfo['Nom'] ?><br>
		Prénom : <?php echo $userinfo['Prenom'] ?><br>
		Mail : <?php echo $userinfo['mail'] ?><br>
		<?php
		if(isset($_SESSION['id']) AND $userinfo['id'] == $_SESSION['id'])
		{
		?>
		<a href="deconnection.php">Deconnection</a>
		<?php
		}
		?>
	</div>
</body>
</html>