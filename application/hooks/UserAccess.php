<?php
require_once (BASEPATH."../application/config/access.php");

class UserAccessManager
{
    private $CI;

    function __construct()
    {
        $this->CI = &get_instance();
		
        if(!isset($this->CI->session)){ 
              $this->CI->load->library('session');
              $this->CI->load->library('router');
        }
    }

   function checkUserAccess()
   {
		$class = $this->CI->router->fetch_class();

		if ($class == "index") return;

		$method = $this->CI->router->fetch_method();

		if (!$this->hasAccess($class,$method))
			redirect(base_url());
    }

	function hasAccess($class,$method)
	{
		global $access;
		$user = $this->CI->session->userdata('user_session');
		$role = $user['role'];
		$page= $access[$class][$method]; 


		if (!isset($user['role'])) 
			return false;

		if (!in_array($role,$page))
			return false;

		return true;
	}
}

?>