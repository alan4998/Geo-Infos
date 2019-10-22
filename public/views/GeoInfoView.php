<!DOCTYPE html>
<html>
<head>
	<title>GEO-INFOS</title>
	<meta charset="utf-8">
</head>
<body>
	<div align="left">
		<h2>Inscription</h2>
		<form method="POST" action="">
			<table>
				<tr>
					<td>
						<label for="nom">Nom :</label> 
					</td>
					<td>
						<input type="text" placeholder="nom" id="nom" name="nom">
					</td>
				</tr>
				<tr>
					<td>
						<label for="prénom">Prénom :</label> 
					</td>
					<td>
						<input type="text" placeholder="prénom" id="prenom" name="prenom">
					</td>
				</tr>
				<tr>
					<td>
						<label for="mail">Adresse mail :</label> 
					</td>
					<td>
						<input type="email" placeholder="Votre adresse mail" id="mail" name="email">
					</td>
				</tr>
				<tr>
					<td>
						<label for="mdp">Mot de passe :</label> 
					</td>
					<td>
						<input type="password" placeholder="Votre mot de passe" id="mdp" name="mdp">
					</td>
				</tr>
			</table>
			<br>
			<input type="submit" name="forminscription" value="Inscription" />
		</form>
		<?php
		if(isset($erreur))
		{
			echo '<font color="red">'.$erreur."</font>";
		} 
		?>
		<br>
		<h2>Connection</h2>
		<form method="POST" action="">
			<table>
				<tr>
					<td>
						<label for="mail">Adresse mail :</label> 
					</td>
					<td>
						<input type="email" placeholder="Votre adresse mail" id="mail" name="emailconnect">
					</td>
				</tr>
				<tr>
					<td>
						<label for="mdp">Mot de passe :</label> 
					</td>
					<td>
						<input type="password" placeholder="Votre mot de passe" id="mdp" name="mdpconnect">
					</td>
				</tr>
			</table>
			<br>
			<input type="submit" name="formconnection" value="Connection" />		
		</form>
		<?php
		if(isset($erreur2))
		{
			echo '<font color="red">'.$erreur2."</font>";
		} 
		?>		
		<br>
		<h2>Mot de passe oublié</h2>
		<form method="POST" action="">
			<table>
				<tr>
					<td>
						<label for="mail">Adresse mail :</label> 
					</td>
					<td>
						<input type="email" placeholder="Votre adresse mail" id="mail" name="emailtofind">
					</td>
				</tr>
			</table>
			<br>
			<input type="submit" name="recupmail" value="Récupérer mon mot de passe" />		
		</form>
		<?php
		if(isset($erreur3))
		{
			echo '<font color="red">'.$erreur3."</font>";
		} 
		?>		
	</div>
</body>
</html>