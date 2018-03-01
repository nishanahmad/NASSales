<html>
<head>
<div align="center">
<br><br><br>
<img src="../NASSales/images/logoBlack.png"  width='300px' height='50px'>
</div>

<body>
<title>User Login</title>
<link rel="stylesheet" type="text/css" href="css/loginPage.css" />
</head>

<form class="form-4" method="post" action="user_login_session.php">
    <div align="center">
	<h1>USER LOGIN </h1>
	</div>
<?php 
	if(isset($_GET['message']))
	{
		echo '<font style="color:#D20101;text-shadow: none;font-weight:bold;font-size:20px;">'.$_GET['message'].'</font>';
	}
?>		
    <p>
        <label for="login">Username</label>
        <input type="text" name="user_name" placeholder="Username" required>
    </p>
    <p>
        <label for="password">Password</label>
        <input type="password" name='password' placeholder="Password" required> 
    </p>

    <p>
        <input type="submit" name="submit" value="Continue"
    </p>       
</form>
</body>
</html>

