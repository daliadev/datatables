<?php
	if (isset($_POST['groupe']) && !empty($_POST['groupe'])) {
		header("Location: $_POST[groupe]");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Education et Formation</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

	<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/styles.css">
</head>


<body>

	<div id="bloc">
		<div id="form-header">Education et Formation<i class="fa fa-archive"></i></div>
		<div id="bloc-formu">
			<div class="fieldset-header">Sélectionnez votre centre</div>

			<select id="site" name="site" class="form-control">
				<option value="">--</option>

				<?php

				$ldap_server = "ldap://dc110.educ-for.local";
				$auth_user = "Administrator@pr";
				$auth_pass = "3duc-f0R";

				$base_dn = "OU=Sites,DC=pr,DC=educationetformation,DC=fr";
				$filter = "(&(objectClass=organizationalUnit)(description=*))";
				$attribute = array("name", "description");

				if (!($connect=@ldap_connect($ldap_server))) {
					die("Could not connect to ldap server");
				}

				if (!($bind=@ldap_bind($connect, $auth_user, $auth_pass))) {
					die("Unable to bind to server");
				}

				// search active directory

				if (!($search=@ldap_search($connect, $base_dn, $filter, $attribute))) {
					die("Unable to search ldap server");
				}

				$number_returned = ldap_count_entries($connect,$search);
				ldap_sort($connect, $search, 'description');
				$info = ldap_get_entries($connect, $search);

				echo '[';
				echo '{"" : "--" },';

				for ($i=0; $i<$info["count"]; $i++) {
					echo '<option value="' . $info[$i]["name"][0] . '">' . $info[$i]["description"][0] . '</option>';
				}

				?>

			</select>


			<form id="page-changer" action="" method="post">
				<label>Puis les actions de formations en cours</label>

				<select id="groupe" name="groupe" class="form-control">
					<option value="">--</option>
				</select>

				<input type=submit value="Valider" class="btn" id="submit" />

			</form>
		</div>
	</div>
	
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery.chained.remote.min.js"></script>

	<script type="text/javascript" charset="utf-8">

		$(function() {
			/* For jquery.chained.js */
			$("#groupe").remoteChained({
				parents : "#site",
				url : "/api/groupe.php"
			});
		})(jQuery);

	</script>

</body>
</html>