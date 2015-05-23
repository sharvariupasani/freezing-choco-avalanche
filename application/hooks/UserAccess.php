<?php
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
		$user = $this->CI->session->userdata('user_session');

		if (!isset($user['role'])) {
			redirect(base_url());
		}

		$role = $user['role'];
		include_once ("access.php");
		$page= $access[$class][$method]; 
		if (!in_array($role,$page))
			redirect(base_url());
    }
}
?>