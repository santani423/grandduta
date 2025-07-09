<?php
/**
 * Login_model Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Login_model extends CI_Model {
	/**
	 * Constructor
	 */
	function Login_model()
	{
		parent::__Construct();
	}
	
	// Inisialisasi nama tabel user
	var $table = 'user';
	
	/**
	 * Cek tabel user, apakah ada user dengan username dan password tertentu
	 */
function check_user($username, $password)
	{
		$query = $this->db->get_where($this->table, array('user_username' => $username, 'user_password' => md5($password)), 1, 0);
		
		echo $query->num_rows(); 
		
		if ($query->num_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
//	function get_posisi_all()
//	{
//		$sql = "select * from tbl_posisi";
//		return 	$this->db->query($sql);
//	}
	
	function getLoket($username)
	{
		//$result = array();

		$sql = "select usrProfil from s_user where usrNama = '" . $username ."'";
		return $this->db->query($sql);
        
	}
}
// END Login_model Class

/* End of file login_model.php */ 
/* Location: ./system/application/model/login_model.php */