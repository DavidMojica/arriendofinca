<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>NETTUTS > Sign up</title>
	<link href="css/style.css" type="text/css" rel="stylesheet" />
</head>
<body>
	<!-- start header div -->	
	<div id="header">
		<h3>NETTUTS > Sign up</h3>
	</div>
	<!-- end header div -->	
	
	<!-- start wrap div -->	
	<div id="wrap">
	    <!-- start PHP code -->
	    <?php
	    
	    	mysql_connect("localhost", "tutorial", "password") or die(mysql_error()); // Connect to database server(localhost) with username and password. 
			mysql_select_db("registrations") or die(mysql_error()); // Select registration database. 
	    	if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])){
                // Verify data 
            }else{
                // Invalid approach 
            }
            if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])){
                // Verify data 


                $email = mysql_escape_string($_GET['email']); // Set email variable 
                $hash = mysql_escape_string($_GET['hash']); // Set hash variable 
            }



            $search = mysql_query("SELECT email, hash, active FROM users WHERE email='".$email."' AND hash='".$hash."' AND active='0'") or die(mysql_error()); 
            $match  = mysql_num_rows($search);
            $search = mysql_query("SELECT email, hash, active FROM users WHERE email='".$email."' AND hash='".$hash."' AND active='0'") or die(mysql_error()); 
            $match  = mysql_num_rows($search);
            echo $match; 

            if($match > 0){
                // We have a match, activate the account 
                // We have a match, activate the account 
                mysql_query("UPDATE users SET active='1' WHERE email='".$email."' AND hash='".$hash."' AND active='0'") or die(mysql_error());
                echo '<div class="statusmsg">Your account has been activated, you can now login</div>';
            }else{
                // No match -> invalid url or account has already been activated. 
            }
	    ?>
	    <!-- stop PHP Code -->
		
	</div>
	<!-- end wrap div -->	
</body>
</html>