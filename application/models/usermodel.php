<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usermodel extends CI_Model
{
	public function get_menu_for_level($user_level)
	{
		$this->db->from('menu');
		$this->db->like('menu_allowed','+'.$user_level.'+');
		$result = $this->db->get();
		return $result;
	}
	function get_array_menu($id)
	{
		$this->db->select('menu_allowed');
		$this->db->from('menu');
		$this->db->where('menu_id',$id);
		$this->db->order_by('menu_id','asc');
		$data = $this->db->get();
		if($data->num_rows() > 0)
		{
			$row = $data->row();
			$level = $row->menu_allowed;
			$arr = explode('+',$level);
			return $arr;
		}
		else
		{
			die();
		}
	}
	
	public function get_one_passwd($id)
	{
		$this->db->where('user_username', $id);
		$result = $this->db->get('user');
	
		if ($result->num_rows() == 1)
		{
			return $result->row_array();
		}
		else
		{
			return array();
		}
	}
	

   public function updateuser($id,$pass)
    {
    	$sql = "UPDATE user SET user_password = '$pass' WHERE user_username ='$id'";   
    	return $this->db->query($sql);
    }
}