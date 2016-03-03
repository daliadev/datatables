<?php
	$ldap_server = "ldap://dc110.educ-for.local";
	$auth_user = "Administrator@pr";
	$auth_pass = "3duc-f0R";

	$base_dn = "OU=Sites,DC=pr,DC=educationetformation,DC=fr";
	$filter = "(&(objectClass=group)(name=" . $_GET['groupe'] . "))";
	$attribute = array("member");


	if (!($connect=@ldap_connect($ldap_server))) { 
		$connect_error = "Could not connect to ldap server";
		//die("Could not connect to ldap server"); 
	}
	ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, 3);
	ldap_set_option($connect, LDAP_OPT_REFERRALS, 0);
	if (!($bind=@ldap_bind($connect, $auth_user, $auth_pass))) {
		$connect_error = "Unable to bind to server"; 
		//die("Unable to bind to server"); 
	}

	if (!($search=@ldap_search($connect, $base_dn, $filter, $attribute))) {
		$connect_error = "Unable to search ldap server";
		//die("Unable to search ldap server"); 
	}

	$info = ldap_get_entries($connect, $search);

	$filter= "(&(objectClass=person)(userAccountControl=66048))";
	$attribute= array("givenName", "sn", "sAMAccountName", "description");

	$title = "Liste des fiches ";
	if (isset($_GET['groupe']) && !empty($_GET['groupe'])) {

		$title .= $_GET['groupe'];
	}

?>
<!DOCTYPE>
<html lang="fr">
<head>
	<meta charset="utf-8" />
	<title>Education et Formation</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	
	<link rel="stylesheet" type="text/css" href='https://fonts.googleapis.com/css?family=Roboto:400,300,700' />
	<link rel="stylesheet" type="text/css" href="src/datatables.min.css"/>
	<link rel="stylesheet" type="text/css" media="all" href="css/material.min.css" />
	<link rel="stylesheet" type="text/css" media="all" href="src/datatables.material.min.css" />
	<link rel="stylesheet" type="text/css" media="all" href="css/styles.css" />

	<script type="text/javascript" src="src/datatables.min.js"></script>
	<script type="text/javascript" src="js/material.min.js"></script>
	<script type="text/javascript" src="src/datatables.material.min.js"></script>
</head>
	
<body>	
	
	<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">

		<header class="mdl-layout__header">
			<div class="mdl-layout__header-row">
				<span class="mdl-layout-title"><?php echo $title ?></span>
				<div class="mdl-layout-spacer"></div>
				<nav class="mdl-navigation mdl-layout">
					<a class="mdl-navigation__link" href="http://devsmb4dc">Retour</a>
				</nav>
			</div>
		</header>

		<main class="mdl-layout__content">
			<div class="page-content">
				
				<div class="mdl-grid">
					
					<div class="mdl-cell--1-col"></div>
					<div class="mdl-cell--middle mdl-cell--10-col">

						<table id="listbygroup" class="mdl-data-table mdl-js-data-table" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th class="mdl-data-table__cell--non-numeric">Nom</th>
									<th class="mdl-data-table__cell--non-numeric">Pr&eacute;nom</th>
									<th class="mdl-data-table__cell--non-numeric">Login</th>
									<th class="mdl-data-table__cell--non-numeric">Mot de passe</th>
								</tr>
							</thead>
					
							<tbody>

								<?php
								$j = 0;

								for ($i = 0; $i < $info[0]["member"]["count"]; $i++) {

									$base_dn=$info[0]["member"][$i];

									if (!($search=@ldap_read($connect, $base_dn, $filter, $attribute))) { die("Unable to search ldap server"); }
									$info2 = ldap_get_entries($connect, $search);

									if (!empty(array_filter($info2))) {
											
										echo '<tr id="row'.$j.'" class="selectable">';
											echo '<td class="mdl-data-table__cell--non-numeric">' . strtoupper($info2[0]['sn'][0]) . '</td>'; //NOM
											echo '<td class="mdl-data-table__cell--non-numeric">' . ucfirst($info2[0]['givenname'][0]) . '</td>'; //PRENOM
											echo '<td class="mdl-data-table__cell--non-numeric">' . $info2[0]['samaccountname'][0] . '</td>'; //LOGIN
											echo '<td class="mdl-data-table__cell--non-numeric">' . $info2[0]['description'][0] . '</td>'; //PWD
										echo '</tr>';

										$j++;
									}
								}
								?>

							</tbody>

						</table>

						<?php ldap_unbind($connect); ?>

					</div>

					<div class="mdl-cell--1-col"></div>

				</div>
			</div>
		</main>
	</div>



	<script type="text/javascript">

		$(function() {


			var self = this;
			var selectedRows = [];


			this.setPDFData = function(data, columnIdx) {
				//console.log(columnIdx + ' - ' + data);
			}

			/* Génération du pdf */
			this.createPDFPages = function(doc) {

				var content = [];
				
				for (var i = 0; i < selectedRows.length; i++) {

					var breakPage =  i > 0 ? 'before' : null;
					var name = selectedRows[i].name + ' ' + selectedRows[i].forname;

					/*** CONTENU D'UNE PAGE ***/
					var pageContent = [
						{
							// Logo Educfor
							image: 'data:image/jpeg;base64,/9j/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/sABFEdWNreQABAAQAAABQAAD/ 4QMpaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49 Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRh IHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29y ZSA1LjAtYzA2MCA2MS4xMzQ3NzcsIDIwMTAvMDIvMTItMTc6MzI6MDAgICAgICAg ICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8w Mi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0 PSIiIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIgeG1s bnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5z OnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3Vy Y2VSZWYjIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCBDUzUgV2lu ZG93cyIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo3RURFNDM4OEUxNDUxMUU1 QkJBQ0FERkQxMTczQjg4QiIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDo3RURF NDM4OUUxNDUxMUU1QkJBQ0FERkQxMTczQjg4QiI+IDx4bXBNTTpEZXJpdmVkRnJv bSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjdFREU0Mzg2RTE0NTExRTVCQkFD QURGRDExNzNCODhCIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjdFREU0Mzg3 RTE0NTExRTVCQkFDQURGRDExNzNCODhCIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8 L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+/+4AJkFk b2JlAGTAAAAAAQMAFQQDBgoNAAAHAAAACt0AAA2HAAAQb//bAIQAAgICAgICAgIC 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', 
							pageBreak: breakPage
						},
						{
							// Titre
							text: 'Charte de confidentialité :',
							margin: [20, 40, 20, 5]
						},
						{
							// Texte clause de confidentialité
							text: 'Ce document est personnel et ne doit pas être donné, ni prêté, à une autre personne, hormis à votre formateur. Vérifier que votre nom est correct. En cas d\'erreur, renseignez-vous auprès de votre responsable.',
							margin: [20, 5, 20, 20]
						},
						{
							// Nom - Prénom
							text: 'Votre nom : ' + name,
							margin: [80, 20, 80, 20]
						},
						{
							// Login
							text: 'Votre identifiant de session : ' + selectedRows[i].login,
							margin: [80, 20, 80, 20]
						},
						{
							// Mot de passe
							text: 'Votre mot de passe : ' + selectedRows[i].pass,
							margin: [80, 20, 80, 20]
						}
					];
					
					content.push(pageContent);
				}

				
				var docDefinition = {
					pageSize: 'A4',
					pageOrientation: 'portrait',
					pageMargins: [ 40, 60, 40, 60 ],
					content: [
						content
					]
				};
				doc.content = docDefinition.content;
				
			};

			

			/* Initialisation du tableau */
			var $table = $('#listbygroup').DataTable({
				language: {
					url: 'lang/french.json'
				},
				dom: 'lBftrip',
				select: {
					style: 'multi',
					items: 'rows',
					selector: 'selectable'
				},
				buttons: [{
					extend: 'pdf',
					text: 'Afficher/imprimer la selection',
					className: 'printPdfButton',
					extension: 'pdf',
					download: 'open',
					title : '<?php echo $title ?>',
					exportOptions: {
						rows: $('#row0, #row2'),
						modifier: {
							page: 'all'
						},
						format: {
							body: function (data, columnIdx, rowIdx) {
								//console.log(data + ' - ' + columnIdx + ' - ' + rowIdx);
								self.setPDFData(data, columnIdx);
							}
						}
					},
					header: false,
					footer: false,
					customize: function(doc) {
						self.createPDFPages(doc);
					}
				}]
			});
			

			$('.selectable').on('click', function(e) {

				$(this).toggleClass('selected');

				var rows = e.currentTarget.childNodes;
				var id = e.currentTarget.id.substring(3);

				var member = {
					id: id,
					name: rows[0].innerText,
					forname: rows[1].innerText,
					login: rows[2].innerText,
					pass: rows[3].innerText
				};

				
				var spliced = false;

				for (i = 0; i < selectedRows.length; i++) {

					var tabId = selectedRows[i].id;

					if (tabId == member.id) {

						selectedRows.splice(i, 1);
						spliced = true;
					}
				}

				if (!spliced)
				{
					selectedRows.push(member);
				}

				if (spliced && selectedRows.length == 0) 
				{
					e.preventDefault();
					alert("Aucune personne n'a été sélectionée.");
				}
			});

		});

	</script>

	<?php if (isset($connect_error) && !empty($connect_error)) : ?>
		
	<script type="text/javascript">
		alert('<?php echo $connect_error; ?>');
	</script>

	<?php endif; ?>

</body>

</html>