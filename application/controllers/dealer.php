<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dealer extends CI_Controller {

	function __construct(){
		parent::__construct();

		is_login();

		$this->user_session = $this->session->userdata('user_session');

		if (!@in_array("dealer", @array_keys(config_item('user_role')[$this->user_session['role']])) && $this->user_session['role'] != 'a') {
			redirect("dashboard");
		}
	}

	public function index()
	{
		#pr($this->user_session);
		$data['view'] = "index";
		$this->load->view('content', $data);
	}

	public function ajax_list($limit=0)
	{
		$post = $this->input->post();
		$columns = array();
		$columns = array(
			array( 'db' => 'du_uname', 'dt' => 0 ),
			array( 'db' => 'de_name', 'dt' => 1 ),
			array( 'db' => 'de_contact', 'dt' => 2 ),
			array( 'db' => 'de_email', 'dt' => 3 ),
			array( 'db' => 'de_address',  'dt' => 4 ),
			array( 'db' => 'de_city',  'dt' => 5 ),
			array( 'db' => 'de_state',  'dt' => 6 ),
			array( 'db' => 'de_zip',  'dt' => 7 ),
			array( 'db' => 'de_autoid',
					'dt' => 8,
					'formatter' => function( $d, $row ) {
						return '<a href="'.site_url('/dealer/edit/'.$d).'" class="fa fa-edit"></a> / <a href="javascript:void(0);" onclick="delete_dealer('.$d.')" class="fa fa-trash-o"></a>';
					}
			)
		);
		$join1 = array(DEAL_USER,'du_autoid = de_userid');
		echo json_encode( SSP::simple( $post, DEAL_DEALER, "de_autoid", $columns ,array($join1) ) );exit;
	}

	public function add()
	{
		$post = $this->input->post();
		if ($post) {
			#pr($post);
			$error = array();
			$e_flag=0;


			if ($post['de_userid'] == "") {
				$error['de_userid'] = 'Please select user.';
				$e_flag=1;
			}
			if ($post['de_name'] == "") {
				$error['de_name'] = 'Please dealer name.';
				$e_flag=1;
			}
			if ($post['de_url'] != "") {
				if (filter_var($post['de_url'], FILTER_VALIDATE_URL) === FALSE) {
					$error['de_url'] = 'Please enter valid URL.';
					$e_flag=1;
				}
			}
			if(!valid_email(trim($post['de_email'])) && trim($post['de_email']) == ""){
				$error['de_email'] = 'Please enter valid email.';
				$e_flag=1;
			}


			if ($e_flag == 0) {
				$data = array(
					'de_userid'=> $post['de_userid'],
					'de_name'=> $post['de_name'],
					'de_address'=> $post['de_address'],
					'de_disp_address'=> isset($post['de_disp_address'])?1:0,
					'de_city'=> $post['de_city'],
					'de_state'=> $post['de_state'],
					'de_zip'=> $post['de_zip'],
					'de_lat'=> $post['de_lat'],
					'de_long'=> $post['de_long'],
					'de_contact'=> $post['de_contact'],
					'de_email'=> $post['de_email'],
					'de_url'=> $post['de_url']
				);

				$ret = $this->common_model->insertData(DEAL_DEALER, $data);

				if ($ret > 0) {
					$flash_arr = array('flash_type' => 'success',
										'flash_msg' => 'Dealer added successfully.'
									);
				}else{
					$flash_arr = array('flash_type' => 'error',
										'flash_msg' => 'An error occurred while processing.'
									);
				}
				$this->session->set_flashdata($flash_arr);
				redirect("dealer");
			}
			$data['error_msg'] = $error;
		}
		$data['view'] = "add_edit";
		$data['users'] = $this->common_model->selectData(DEAL_USER, 'du_autoid,du_uname,du_email', array('du_role' => 'd'));
		$this->load->view('content', $data);
	}

	public function edit($id)
	{
		if ($id == "" || $id <= 0) {
			redirect('dealer');
		}

		$where = 'de_autoid = '.$id;

		$post = $this->input->post();
		if ($post) {
			$error = array();
			$e_flag=0;

			if ($post['de_userid'] == "") {
				$error['de_userid'] = 'Please select user.';
				$e_flag=1;
			}
			if ($post['de_userid'] == "") {
				$error['de_userid'] = 'Please select user.';
				$e_flag=1;
			}
			if ($post['de_url'] != "" && filter_var($post['de_url'], FILTER_VALIDATE_URL) === FALSE) {
			    $error['de_url'] = 'Please enter valid URL.';
				$e_flag=1;
			}
			if(!valid_email(trim($post['de_email'])) && trim($post['de_email']) == ""){
				$error['de_email'] = 'Please enter valid email.';
				$e_flag=1;
			}

			if ($e_flag == 0) {
				$data = array(
								'de_userid'=> $post['de_userid'],
								'de_name'=> $post['de_name'],
								'de_address'=> $post['de_address'],
								'de_city'=> $post['de_city'],
								'de_state'=> $post['de_state'],
								'de_zip'=> $post['de_zip'],
								'de_disp_address'=> isset($post['de_disp_address'])?1:0,
								'de_lat'=> $post['de_lat'],
								'de_long'=> $post['de_long'],
								'de_contact'=> $post['de_contact'],
								'de_email'=> $post['de_email'],
								'de_url'=> $post['de_url']
							);
				$ret = $this->common_model->updateData(DEAL_DEALER, $data, $where);

				if ($ret > 0) {
					$flash_arr = array('flash_type' => 'success',
										'flash_msg' => 'Dealer updated successfully.'
									);
				}else{
					$flash_arr = array('flash_type' => 'error',
										'flash_msg' => 'An error occurred while processing.'
									);
				}
				$this->session->set_flashdata($flash_arr);
				redirect("dealer");
			}
			$data['error_msg'] = $error;

		}

		$data['dealer'] = $dealer = $this->common_model->selectData(DEAL_DEALER, '*', $where);

		if (empty($dealer)) {
			redirect('dealer');
		}

		$data['users'] = $this->common_model->selectData(DEAL_USER, 'du_autoid,du_uname,du_email', array('du_role' => 'd'));
		$data['view'] = "add_edit";
		$this->load->view('content', $data);
	}

	public function delete()
	{
		$post = $this->input->post();

		if ($post) {
			$ret = $this->common_model->deleteData(DEAL_DEALER, array('de_autoid' => $post['id'] ));
			if ($ret > 0) {
				echo "success";
				#echo success_msg_box('Dealer deleted successfully.');;
			}else{
				echo "error";
				#echo error_msg_box('An error occurred while processing.');
			}
		}
	}


}
