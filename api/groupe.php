<?php

	$ldap_server = "ldap://dc110.educ-for.local";
	$auth_user = "Administrator@pr";
	$auth_pass = "3duc-f0R";

	$base_dn = "OU=" . $_GET['site'] . ",OU=Sites,DC=pr,DC=educationetformation,DC=fr";
	$filter = "(objectClass=group)";
	$attribute = array("cn");


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
	$info = ldap_get_entries($connect, $search);
	echo '[';
	echo '{"" : "--" },';

	for ($i=0; $i<$info["count"]; $i++) {
		echo '{ "/members.php?groupe=' . $info[$i]["cn"][0] . '" : "' . $info[$i]["cn"][0] . '" }';
		if($i != ($info["count"] - 1)) {
			echo ',';
		}
	}
	echo ']';

?>