<?php
	$ldap_server = "ldap://dc110.educ-for.local";
	$auth_user = "Administrator@pr";
	$auth_pass = "3duc-f0R";

	$base_dn = "OU=Sites,DC=pr,DC=educationetformation,DC=fr";
	$filter = "(&(objectClass=group)(name=" . $_GET['groupe'] . "))";
	$attribute = array("member");


	if (!($connect=@ldap_connect($ldap_server))) { die("Could not connect to ldap server"); }
	ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, 3);
	ldap_set_option($connect, LDAP_OPT_REFERRALS, 0);
	if (!($bind=@ldap_bind($connect, $auth_user, $auth_pass))) { die("Unable to bind to server"); }

	if (!($search=@ldap_search($connect, $base_dn, $filter, $attribute))) { die("Unable to search ldap server"); }

	$info = ldap_get_entries($connect, $search);

	$filter= "(&(objectClass=person)(userAccountControl=66048))";
	$attribute= array("givenName", "sn", "sAMAccountName", "description");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Education et Formation</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	
	<link rel="stylesheet" type="text/css" href='https://fonts.googleapis.com/css?family=Roboto:400,300,700' />
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" />
	<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.10/integration/jqueryui/dataTables.jqueryui.css" />
	<link rel="stylesheet" type="text/css" href="css/styles.css" />

	<script type="text/javascript" src="//code.jquery.com/jquery-1.12.0.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="//cdn.datatables.net/plug-ins/1.10.10/integration/jqueryui/dataTables.jqueryui.js"></script>
	<!-- <script type="text/javascript" src="//cdn.datatables.net/plug-ins/1.10.10/i18n/French.json"></script> -->
	<script type="text/javascript" src="//cdn.datatables.net/1.10.11/js/dataTables.material.min.js"></script>

	<script type="text/javascript" src="//cdn.datatables.net/buttons/1.1.2/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" src="//cdn.datatables.net/buttons/1.1.2/js/buttons.print.min.js"></script>
	<script type="text/javascript" src="//cdn.datatables.net/select/1.1.2/js/dataTables.select.min.js"></script>
</head>
	
<body>	

	<div id="table-group" class="main">
		
		<!-- <button type="button" id="print-button" class="btn btn-print" onclick="" style="margin-bottom: 24px;">Print</button> -->
		
		<button type="button" class="btn btn-back" onclick="location.href='http://devsmb4dc';">Retour</button>
		
		<div style="clear: both;"></div>

		<!-- <div id="bloc-full"> -->
			<table id="listbygroup" class="display" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th align="left">Nom</th>
						<th align="left">Pr&eacute;nom</th>
						<th align="left">Login</th>
						<th align="left">Mot de passe</th>
					</tr>
				</thead>
		
				<tbody>

					<?php
					for ($i=0; $i<$info[0]["member"]["count"]; $i++) {
						$base_dn=$info[0]["member"][$i];

						if (!($search=@ldap_read($connect, $base_dn, $filter, $attribute))) { die("Unable to search ldap server"); }
						$info2 = ldap_get_entries($connect, $search);
						$test_if_empty = array_filter($info2);
							if (empty($test_if_empty)) {
									continue;
							}
						echo "<tr>";
						echo "<td>" . $info2[0]["sn"][0] . "</td>"; //NOM
						echo "<td>" . $info2[0]["givenname"][0] . "</td>"; //PRENOM
						echo "<td>" . $info2[0]["samaccountname"][0] . "</td>"; //LOGIN
						echo "<td>" . $info2[0]["description"][0] . "</td>"; //PWD
						echo "</tr>";
					}
					?>

				</tbody>

			</table>

			<?php ldap_unbind($connect); ?>

		<!-- </div> -->

	</div>


	<script type="text/javascript">

		$(document).ready(function() {

			var selectedRows = [];


			var table = $('#listbygroup').DataTable({
				select: {
					'api',
					items: 'rows'
				},
				language: {
					url: '//cdn.datatables.net/plug-ins/1.10.10/i18n/French.json'
				},
				dom: 'Bfrtip',
				buttons: [{
					extend: 'print',
					text: 'Imprimer la sélection',
					className: 'btn btn-print'
				}]
			});
			
			$('#listbygroup tbody').on('click', 'tr', function () {
				$(this).toggleClass('selected');
			});


			table.on('select', function(e, dt, type, indexes) {

				if (type === 'rows') {
					var data = table.rows(indexes).data().pluck('id');
					selectedRows.push(data);
				}

				console.log(indexes);
			});

			/*
			$('#print-button').on('click', function () {
				//$(this).toggleClass('selected');
			});
			*/
		});

	</script>

</body>

</html>