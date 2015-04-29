<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller {

	function __construct(){
		parent::__construct();

		is_login();

		$this->user_session = $this->session->userdata('user_session');

		if (!@in_array("users", @array_keys(config_item('user_role')[$this->user_session['role']])) && $this->user_session['role'] != 'a') {
			redirect("dashboard");
		}
	}

	public function index()
	{
		#pr($this->session->flashdata('flash_msg'));
		$data['view'] = "index";
		$this->load->view('content', $data);
	}

	public function ajax_list($limit=0)
	{
		$post = $this->input->post();

		$columns = array(
			array( 'db' => 'name', 'dt' => 0 ),
			array( 'db' => 'role',  'dt' => 1 ),
			array( 'db' => 'email',  'dt' => 2 ),
			array('db'        => 'creation_date',
					'dt'        => 3,
					'formatter' => function( $d, $row ) {
						return date( 'jS M y', strtotime($d));
					}
			),
			array( 'db' => 'id',
					'dt' => 4,
					'formatter' => function( $d, $row ) {
						return '<a href="'.site_url('/users/edit/'.$d).'" class="fa fa-edit"></a> <a href="javascript:void(0);" onclick="delete_user('.$d.')" class="fa fa-trash-o"></a>';
					}
			),
		);
		echo json_encode( SSP::simple( $post, USER, "id", $columns ) );exit;
	}

	public function add()
	{
		$post = $this->input->post();
		if ($post) {
			#pr($post);
			$error = array();
			$e_flag=0;

			if(!valid_email(trim($post['email'])) && trim($post['email']) == ""){
				$error['email'] = 'Please enter valid email.';
				$e_flag=1;
			}
			else{
				$is_unique_email = $this->common_model->isUnique(USER, 'email', trim($post['email']));
				if (!$is_unique_email) {
					$error['email'] = 'Email already exists.';
					$e_flag=1;
				}
			}

			if(trim($post['user_name']) == ''){
				$error['user_name'] = 'Please enter user name.';
				$e_flag=1;
			}
			if(trim($post['role']) == ''){
				$error['role'] = 'Please select role.';
				$e_flag=1;
			}
			
			
			if (trim($post['password']) != "") {
				if($post['password'] == $post['re_password'])
				{
					$psFlas = true;
				}
				else
				{
					$error['password'] = 'Password field does not match.';
					$e_flag=1;
				}
			}
			else
			{
				$error['password'] = 'Please enter password.';
				$e_flag=1;
			}

			if ($e_flag == 0) {
				$data = array('name' => $post['user_name'],
								'role' => $post['role'],
								'email' => $post['email'],
								'password' => sha1(trim($post['password'])),
							  );
				
				$ret = $this->common_model->insertData(USER, $data);

				if ($ret > 0) {
					$flash_arr = array('flash_type' => 'success',
										'flash_msg' => 'User added successfully.'
									);
				}else{
					$flash_arr = array('flash_type' => 'error',
										'flash_msg' => 'An error occurred while processing.'
									);
				}
				$this->session->set_flashdata($flash_arr);
				redirect("users");
			}
			$data['error_msg'] = $error;
		}
		$data['view'] = "add_edit";
		$this->load->view('content', $data);
	}

	public function edit($id)
	{
		if ($id == "" || $id <= 0) {
			redirect('users');
		}

		$where = 'id = '.$id;

		$post = $this->input->post();
		if ($post) {
			#pr($post);
			$error = array();
			$e_flag=0;

			if(!valid_email(trim($post['email'])) && trim($post['email']) == ""){
				$error['email'] = 'Please enter valid email.';
				$e_flag=1;
			}
			else{
				$is_unique_email = $this->common_model->isUnique(USER, 'email', trim($post['email']),"id <> ". $id);
				if (!$is_unique_email) {
					$error['email'] = 'Email already exists.';
					$e_flag=1;
				}
			}

			if(trim($post['user_name']) == ''){
				$error['user_name'] = 'Please enter user name.';
				$e_flag=1;
			}
			if(trim($post['role']) == ''){
				$error['role'] = 'Please select role.';
				$e_flag=1;
			}
			$psFlas = false;
			if (trim($post['password']) != "") {
				if($post['password'] == $post['re_password'])
				{
					$psFlas = true;
				}
				else
				{
					$error['password'] = 'Password field does not match.';
					$e_flag=1;
				}
			}

			if ($e_flag == 0) {
				$data = array('name' => $post['user_name'],
								'role' => $post['role'],
								'email' => $post['email']
							);
				if($psFlas)
					$data['password'] = sha1(trim($post['password']));
				$ret = $this->common_model->updateData(USER, $data, $where);

				if ($ret > 0) {
					$flash_arr = array('flash_type' => 'success',
										'flash_msg' => 'User updated successfully.'
									);
				}else{
					$flash_arr = array('flash_type' => 'error',
										'flash_msg' => 'An error occurred while processing.'
									);
				}
				$this->session->set_flashdata($flash_arr);
				redirect("users");
			}
			$data['error_msg'] = $error;
		}
		$data['user'] = $user = $this->common_model->selectData(USER, '*', $where);

		if (empty($user)) {
			redirect('users');
		}
		$data['view'] = "add_edit";
		$this->load->view('content', $data);
	}


	public function delete()
	{
		$post = $this->input->post();

		if ($post) {
			$ret = $this->common_model->deleteData(USER, array('id' => $post['id'] ));
			if ($ret > 0) {
				echo "success";
				#echo success_msg_box('User deleted successfully.');;
			}else{
				echo "error";
				#echo error_msg_box('An error occurred while processing.');
			}
		}
	}
}
