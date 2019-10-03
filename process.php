<?php include 'database.php'; ?>
<?php
	$myfile=fopen("pass.txt","r");
	$pass=fread($myfile,filesize("pass.txt"));
	fclose($myfile);
	//pass stores the real password

	//use the below code to check if the password given is correct
	// if($user_pass==$pass)
	// {
	// 	//store to database and print from database
	// }
	// else
	// {
	// 	alert('Please enter a valid security code');
	// }
?>