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
	<!--<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" />
	<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.10/integration/jqueryui/dataTables.jqueryui.css" />-->
	<link rel="stylesheet" type="text/css" href="src/datatables.min.css"/>>
	<link rel="stylesheet" type="text/css" media="all" href="css/material.min.css" />
	<link rel="stylesheet" type="text/css" media="all" href="src/datatables.material.min.css" />
	<!-- <link rel="stylesheet" type="text/css" media="all" href="src/Buttons-1.1.2/css/buttons.dataTables.min.css" /> -->
	<link rel="stylesheet" type="text/css" media="all" href="css/styles.css" />

	<!--<script type="text/javascript" src="//code.jquery.com/jquery-1.12.0.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="//cdn.datatables.net/plug-ins/1.10.10/integration/jqueryui/dataTables.jqueryui.js"></script>
	<script type="text/javascript" src="//cdn.datatables.net/1.10.11/js/dataTables.material.min.js"></script>

	<script type="text/javascript" src="//cdn.datatables.net/buttons/1.1.2/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" src="//cdn.datatables.net/buttons/1.1.2/js/buttons.print.min.js"></script>
	<script type="text/javascript" src="//cdn.datatables.net/select/1.1.2/js/dataTables.select.min.js"></script>-->
	<script type="text/javascript" src="src/datatables.min.js"></script>
	<script type="text/javascript" src="js/material.min.js"></script>
	<script type="text/javascript" src="src/datatables.material.min.js"></script>
	<!-- <script type="text/javascript" src="src/Buttons-1.1.2/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" src="src/Buttons-1.1.2/js/buttons.html5.min.js"></script>
	<script type="text/javascript" src="src/Buttons-1.1.2/js/buttons.flash.min.js"></script> -->
</head>
	
<body>	
	
	<!-- Always shows a header, even in smaller screens. -->
	<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">

		<header class="mdl-layout__header">
			<div class="mdl-layout__header-row">
				<!-- Title -->
				<span class="mdl-layout-title"><?php echo $title ?></span>
				<!-- Add spacer, to align navigation to the right -->
				<div class="mdl-layout-spacer"></div>
				<!-- Navigation. We hide it in small screens. -->
				<nav class="mdl-navigation mdl-layout--large-screen-only">
					<!-- <a class="mdl-navigation__link" href="">Link</a>
					<a class="mdl-navigation__link" href="">Link</a> -->
					<!-- <a id="print-fiches" class="mdl-navigation__link" href="#" title="Imprimer les fiches sélectionnées">Imprimer</a> -->
					<a class="mdl-navigation__link" href="http://devsmb4dc">Retour</a>
				</nav>
			</div>
		</header>
	<!-- 
		<div class="mdl-layout__drawer">
			<span class="mdl-layout-title">Title</span>
			<nav class="mdl-navigation">
				<a class="mdl-navigation__link" href="">Link</a>
				<a class="mdl-navigation__link" href="">Link</a>
				<a class="mdl-navigation__link" href="">Link</a>
				<a class="mdl-navigation__link" href="">Link</a>
			</nav>
		</div> -->

		<main class="mdl-layout__content">
			<div class="page-content"><!-- Your content goes here -->
				
				<div class="mdl-grid">
					
					<div class="mdl-cell--1-col"></div>
					<div class="mdl-cell--middle mdl-cell--10-col">

						<table id="listbygroup" class="mdl-data-table mdl-js-data-table" cellspacing="0" width="100%">
						<!-- <table id="listbygroup" class="display" cellspacing="0" width="100%"> -->
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
									//$test_if_empty = array_filter($info2);
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
	<!-- <div id="table-group" class="container"> -->
		
		<!-- <div class="row"> -->
			<!-- <button type="button" id="print-button" class="btn btn-print" onclick="" style="margin-bottom: 24px;">Print</button> -->
		
			<!-- <button type="button" class="btn btn-back" onclick="location.href='http://devsmb4dc';">Retour</button> -->
		<!-- </div> -->
		<!-- <div style="clear: both;"></div> -->

		<!-- <div class="row"> -->
			

		<!-- </div>

	</div> -->


	<script type="text/javascript">

		$(function() {

			var self = this;

			var selectedRows = [];


			this.createPDF = function(doc) {

				//console.log(doc.content[1]);

				for (var i = 0; i < selectedRows.length; i++) {
					
					doc.content[1] = {
						text: selectedRows[i].name
					} 

				}
				/*
				var cols = [];
				cols[0] = {text: 'Left part', alignment: 'left', margin:[20] };
				cols[1] = {text: 'Right part', alignment: 'right', margin:[0,0,20] };
				var objFooter = {};
				objFooter['columns'] = cols;
				doc['footer']=objFooter;
				doc.content.splice(1, 0, {
					margin: [0, 0, 0, 12],
					alignment: 'center',
					image: ''
				});
				*/
			};


			this.printSingle = function(win) {
				//win.document.body.innerHTML = 'coucou';
				//console.log(win.document.body);
			};


			/* Initialisation du tableau */
			var $table = $('#listbygroup').DataTable({
				language: {
					url: 'lang/french.json'
				},
				dom: 'lBftrip', //'Bfrtip',
				//'pageLength': 10,
				//'lengthMenu': [10, 25, 50, 75, 100],
				// responsive: true,
				select: {
					style: 'multi',
					items: 'rows',
					selector: 'selectable'
				},
				// select: true,
				
				buttons: [
				/*{
					extend: 'print',
					text: 'Imprimer la selection',
					className: 'printButton',
					//action : '',
					title : '',
					//message: function() {
					//	console.log('print');
					//},
					exportOptions: {
						modifier: {
							rows: $('.selected')
						}
					},
					header: false,
					footer: false,
					//autoPrint: false,
					customize: self.printSingle(window)
				},*/
				{
					extend: 'pdf',
					text: 'Imprimer la selection',
					className: 'printPdfButton',
					extension: 'pdf',
					download: 'open',
					//action : 'create',
					title : '<?php echo $title ?>',
					/*
					message: function() {
						console.log('print');
					},
					*/
					/*
					exportOptions: {
						modifier: {
							rows: $('.selected')
						}
					},
					*/
					header: false,
					footer: false,
					//autoPrint: false,
					customize: function(doc) {

						self.createPDF(doc);
						
						// Data URL generated by http://dataurl.net/#dataurlmaker
						/*
						var cols = [];
						cols[0] = {text: 'Left part', alignment: 'left', margin:[20] };
						cols[1] = {text: 'Right part', alignment: 'right', margin:[0,0,20] };
						var objFooter = {};
						objFooter['columns'] = cols;
						doc['footer']=objFooter;
						doc.content.splice(1, 0, {
							margin: [0, 0, 0, 12],
							alignment: 'center',
							image: 'data:image/png;base64,...',
						});
						*/
					}
				}]
			});
			


			/*
			$('#listbygroup tbody').on('click', 'tr', function () {
				$(this).toggleClass('selected');
			});
			*/
			/*
			$table.on('select', function(e, dt, type, indexes) {

				if (type === 'rows') {
					var data = table.rows(indexes).data().pluck('id');
					selectedRows.push(data);
				}

				console.log(indexes);
			});
			*/

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
			});

			// To print rows as many single pdf page
			/*
			$('#print-fiches').on('click', function(e) {

				if (selectedRows.length > 0) {

					for (i = 0; i < selectedRows.length; i++) {

						console.log(selectedRows[i]);
					}

					//window.print();
				}
				else {

					alert('Vous devez sélectionner au moins une personne de la liste pour pouvoir imprimer.');
				}
				
			});
			*/
		});

	</script>

	<?php if (isset($connect_error) && !empty($connect_error)) : ?>
		
	<script type="text/javascript">
		alert('<?php echo $connect_error; ?>');
	</script>

	<?php endif; ?>

</body>

</html>