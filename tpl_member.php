<?php
	
	$id = null;

	if (isset($_POST['id']) && !empty($_POST['id']))
	{
		$id = $_POST['id'];
	}
	if (isset($_POST['name']) && !empty($_POST['name']))
	{
		$name = $_POST['name'];
	}
	if (isset($_POST['forname']) && !empty($_POST['forname']))
	{
		$forname = $_POST['forname'];
	}
	if (isset($_POST['login']) && !empty($_POST['login']))
	{
		$login = $_POST['login'];
	}
	if (isset($_POST['pass']) && !empty($_POST['pass']))
	{
		$login = $_POST['pass'];
	}

	
	$page = $name;

	$results = array('error' => false, 'results' => $name);
	
	echo json_encode($results);
	exit();

	$template_page =
	$template_page = 'Ce document est personnel et ne doit pas être donner, ni prêter, à une autre personne, hormis à votre formateur. Vérifier que votre nom est correct. En cas d\'erreur, renseignez-vous auprès de votre responsable.

			<div class="center">
			
				<div class="section">
					<p class="info">Votre nom :</p>
					<p class="title" style="color: #212121;">Alain Durand</p>
				</div>

				<div class="section">
					<p class="title">Votre identifiant de session : </p>
					<p class="info">a.durand</p>
				</div>

				<div class="section">
					<p class="title">Votre mot de passe :</p>
					<p class="info">002145357</p>
				</div>
			
			</div>

		</div>

		<div class="footer">
			<p>&copy; Education et Formation 2016</p>
		</div>

	</div>';
?>
<!--
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Education et Formation</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

	<link rel="stylesheet" type="text/css" media="all" href='https://fonts.googleapis.com/css?family=Roboto:400,300,700' />
	<link rel="stylesheet" type="text/css" media="all" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" />
	<link rel="stylesheet" type="text/css" media="all" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" media="all" href="//cdn.datatables.net/plug-ins/1.10.10/integration/jqueryui/dataTables.jqueryui.css" />
	<link rel="stylesheet" type="text/css" media="all" href="css/styles.css" />
	<link rel="stylesheet" type="text/css" media="all" href="css/print.css" />

</head>
<body>

	<div class="main">

		<div class="header">

			<div class="logo">
				<img src="image/Logo-Education-et-Formation-print-ld.png">
			</div>
			
			<div class="icon">
				<img src="image/icon.png">
				<p>Fiche d'authentification</p>
			</div>
			
			<div style="clear: both;"></div>
		</div>

		<div class="content">
			
			<p class="intro">Ce document est personnel et ne doit pas être donner, ni prêter, à une autre personne, hormis à votre formateur. Vérifier que votre nom est correct. En cas d'erreur, renseignez-vous auprès de votre responsable.</p>

			<div class="center">
			
				<div class="section">
					<p class="info">Votre nom :</p>
					<p class="title" style="color: #212121;">Alain Durand</p>
				</div>

				<div class="section">
					<p class="title">Votre identifiant de session : </p>
					<p class="info">a.durand</p>
				</div>

				<div class="section">
					<p class="title">Votre mot de passe :</p>
					<p class="info">002145357</p>
				</div>
			
			</div>

		</div>

		<div class="footer">
			<p>&copy; Education et Formation 2016</p>
		</div>

	</div>    

</body>
</html>
-->
					