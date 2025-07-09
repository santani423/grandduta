<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<?php echo form_open('home/login');?>

		<input type="text" name="username" placeholder="Nama Pengguna" required>
		<div class="bar">
			<i></i>
		</div>
		<input type="password" name="password" placeholder="Kata Sandi" required>
		<button>Sign in</button>
		
<?php
// hanya untuk menampilkan informasi saja
if(isset($login_info))
{
	echo "<span style='background-color:#eee;padding:3px;'>";
	echo $login_info;
	echo '</span>';
}
?>

<?php echo form_close();