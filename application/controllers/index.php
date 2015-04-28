<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller {

	function __construct(){
		parent::__construct();
	}

	public function index()
	{
		$session = $this->session->userdata('user_session');
		#pr($session,1);
		if (isset($session['id'])) {
			redirect(base_url()."dashboard");
		}

		$data = array();
		$post = $this->input->post();
		if ($post) {
			$error = array();
			$e_flag=0;
			if(trim($post['userid']) == ''){
				$error['userid'] = 'Please enter userid.';
				$e_flag=1;
			}
			if(trim($post['password']) == ''){
				$error['password'] = 'Please enter password.';
				$e_flag=1;
			}

			if ($e_flag == 0) {
				$where = array('email' => $post['userid'],
								'password' => sha1($post['password'])
							);

				$user = $this->common_model->selectData(USER, '*', $where);
				if (count($user) > 0) {
					# create session
					$data = array('id' => $user[0]->id,
									'name' => $user[0]->name,
									'email' => $user[0]->email,
									'role' => $user[0]->du_role,
								 );
				
					$this->session->set_userdata('user_session',$data);

					redirect('dashboard');
				}else{
					$error['invalid_login'] = "Invalid email or password";
				}
			}

			$data['error_msg'] = $error;


		}

		$this->load->view('index/index', $data);
	}


	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url());
	}



	public function forgotpassword()
	{
		$data = '';
		$post = $this->input->post();
		if ($post) {
			$error = array();
			$e_flag=0;
			if(!valid_email(trim($post['email'])) && trim($post['email']) == ''){
				$error['email'] = 'Please enter email.';
				$e_flag=1;
			}

			if ($e_flag == 0) {
				$where = array('email' => trim($post['email']));
				$user = $this->common_model->selectData(USER, '*', $where);
				if (count($user) > 0) {

					$newpassword = random_string('alnum', 8);
					$data = array('password' => sha1($newpassword));
					$upid = $this->common_model->updateData(USER,$data,$where);

					$emailTpl = $this->load->view('email_templates/template', array('email'=>'admin_forgot_password','username'=>$user[0]->name,'password'=>$newpassword), true);

					$ret = sendEmail($user[0]->email, SUBJECT_LOGIN_INFO, $emailTpl, FROM_EMAIL, FROM_NAME);
					if ($ret) {
						$flash_arr = array('flash_type' => 'success',
										'flash_msg' => 'Login details sent successfully.'
									);
					}else{
						$flash_arr = array('flash_type' => 'error',
										'flash_msg' => 'An error occurred while processing.'
									);
					}
					$data['flash_msg'] = $flash_arr;
				}else{
					$error['email'] = "Invalid email address.";
				}
			}
			$data['error_msg'] = $error;
		}
		$this->load->view('index/forgotpassword', $data);
	}

}
