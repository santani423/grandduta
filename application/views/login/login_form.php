<!-- <?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>



		

<?php echo form_close();

// hanya untuk menampilkan informasi saja
if(isset($login_info))
{
	echo '<div class="bar">';
	echo '<input type="text" name="username" value="'. $login_info .'" placeholder="">';
	
	echo '</div>';
}
?> 
-->

<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Estate Management System</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
			  /* background: linear-gradient(to bottom right, rgb(222, 128, 33), rgb(35, 186, 169));  */
			 background: linear-gradient(to bottom right,rgb(253, 232, 172),rgb(148, 168, 201));  
           /* background: linear-gradient(to bottom right, #ffe3c3, #d6f3f0); */
		   /* background: linear-gradient(to bottom right, #222831, #393e46); */
		   /* background: linear-gradient(to bottom right, #fdf1e3, #e6fdfb); */ */
            text-align: center;
            margin-top: 100px;
        }

        .login-box {
            max-width: 360px;
            margin: auto;
            padding: 40px;
            /* border: 1px solid #e0e0e0;
            border-radius: 8px; */
        }

        .logo img {
            max-width: 180px;
        }

        h2 {
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        p {
            font-size: 14px;
            color: #555;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 12px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #007a3d;
            color: white;
            border: none;
            padding: 10px 20px;
            font-weight: bold;
            border-radius: 4px;
            width: 100%;
            cursor: pointer;
        }

        button:hover {
            background-color: #005f2f;
        }

        .reset-link {
            margin-top: 20px;
        }

        .reset-link a {
            color: #007a3d;
            text-decoration: none;
        }

        .reset-link a:hover {
            text-decoration: underline;
        }
		

    </style>
</head>
<body>

<div class="login-box">
    <div class="logo">
        <img src="<?php echo base_url('img/logogdyangbaru-removebg-preview.png'); ?>" alt="SWID Logo">
    </div>
    <h2>Estate Management System</h2>
    <p>Please enter your username and password</p>

    <?php echo form_open('home/login'); ?>

        <label for="email" style="float:left;">Username</label>
        <input type="text" name="username" placeholder="User name" required>

        <label for="password" style="float:left;">Password</label>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Log In</button>

    <?php echo form_close(); ?>

    <div class="reset-link">
        <a href="<?php echo site_url('home/reset_password'); ?>">Reset Password</a>
    </div>

    <?php
    if (isset($login_info)) {
        echo '<p style="color:red; margin-top:15px;">' . $login_info . '</p>';
    }
    ?>
</div>

</body>
</html>
