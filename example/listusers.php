<?php
//Example with multiple domains
include 'class.AuthLdap.php';
$domains = array(
array(
			'dn' => trim('dc=example1,dc=com'),
			'sd' => trim('EX1'),
			'servers' => trim('10.1.1.1,10.1.1.2'),
			'bind_dn' => trim('CN=doe\,john,dc=example1,dc=com'),
			'bind_pw' => trim('password')
		),

array(
			'dn' => trim('dc=example2,dc=com'),
			'sd' => trim('EX2'),
			'servers' => trim('10.2.1.1,10.2.1.2'),
			'bind_dn' => trim('CN=doe\,john,dc=example1,dc=com'),
			'bind_pw' => trim('password')
		)
		);


/**
 * This will loop through the multiple domains trying to start LDAP
 * using the credentials provided
 */
    $userlist          = array();
    $ldapinfo          = array();
    $combined_userlist = array(); 
foreach ($domains as $domain) {
	
//echo json_encode($domain);
		$ldap             = new AuthLdap();
		$ldap->serverType = 'ActiveDirectory';
		$ldap->server = preg_split('/;|,/', $domain['servers']);
		$ldap->dn = $domain['dn'];
		$ldap->domain = $domain['sd'];
		$ldap->searchUser = $domain['bind_dn'];
		$ldap->searchPassword = $domain['bind_pw'];

	if ($ldap->connect()) {
	$filter = "(&(sAMAccountType=805306368)(!(userAccountControl=514))(!(userAccountControl=66050))(mail=*))";
		if ($userlist = $ldap->getUsers(null, array('objectGUID','sAMAccountName','sn','givenName','displayName','mail'),$filter)){
			echo json_encode($userlist);
			$combined_userlist = array_merge($combined_userlist, $userlist);
	 }
    }
}

echo json_encode($combined_userlist);
