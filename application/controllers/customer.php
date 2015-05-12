<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer extends CI_Controller {

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

	public function autocomplete()
	{
		$get = $this->input->get();
		if (!isset($get["term"])) exit;
		$tag = $get["term"];
		$tags = $this->common_model->getCustAutoSuggest($tag);
		echo $tags;exit;
	}

	public function ajax_list($limit=0)
	{
		$post = $this->input->post();

		$columns = array(
			array( 'db' => 'c_fname', 'dt' => 0 ),
			array( 'db' => 'c_lname',  'dt' => 1 ),
			array( 'db' => 'c_phone',  'dt' => 2 ),
			array( 'db' => 'c_email',  'dt' => 3 ),
			array( 'db' => 'c_address',  'dt' => 4 ),
			array( 'db' => 'c_city',  'dt' => 5 ),
			array('db'        => 'c_addeddate',
					'dt'        => 6,
					'formatter' => function( $d, $row ) {
						return date( 'jS M y', strtotime($d));
					}
			),
			array( 'db' => 'c_id',
					'dt' => 7,
					'formatter' => function( $d, $row ) {
						return '<a href="'.site_url('/customer/edit/'.$d).'" class="fa fa-edit"></a> <a href="javascript:void(0);" onclick="delete_customer('.$d.')" class="fa fa-trash-o"></a>';
					}
			),
		);
		echo json_encode( SSP::simple( $post, CUSTOMER, "c_id", $columns ) );exit;
	}

	public function add()
	{
		$post = $this->input->post();
		if ($post) {
			#pr($post);
			$error = array();
			$e_flag=0;

			$this->load->library('form_validation');
			$this->form_validation->set_rules('email', 'Email address', 'trim|required|valid_email',
									array(
										"required"=>"Please enter %s.",
										"valid_email"=>"Please enter valid %s.",
									)
			);

			$this->form_validation->set_rules('fname', 'First Name', 'trim|required');
			$this->form_validation->set_rules('lname', 'Last Name', 'trim|required');
			$this->form_validation->set_rules('phone', 'Mobile', 'trim|required');
			$this->form_validation->set_rules('address', 'Address', 'trim|required');
			
			if ($this->form_validation->run() !== false) {
			
				$type = "basic";
				if (isset($post['is_dealer']))
					$type = "dealer";

				$data = array('c_fname' => trim($post['fname']),
								'c_lname' => trim($post['lname']),
								'c_email' => trim($post['email']),
								'c_phone' => trim($post['phone']),
								'c_address' => trim($post['address']),
								'c_city' => trim($post['city']),
								'c_type' => $type,
							  );
				
				$ret = $this->common_model->insertData(CUSTOMER, $data);

				if ($ret > 0) {
					$flash_arr = array('flash_type' => 'success',
										'flash_msg' => 'Customer added successfully.'
									);
				}else{
					$flash_arr = array('flash_type' => 'error',
										'flash_msg' => 'An error occurred while processing.'
									);
				}
				$this->session->set_flashdata($flash_arr);
				redirect("customer");
			}
			$data['error_msg'] = validation_errors();//$error;
		}
		$data['view'] = "add_edit";
		$this->load->view('content', $data);
	}

	public function edit($id)
	{
		if ($id == "" || $id <= 0) {
			redirect('users');
		}

		$where = 'c_id = '.$id;

		$post = $this->input->post();
		if ($post) {

			$this->form_validation->set_rules('email', 'Email address', 'trim|required|valid_email',
									array(
										"required"=>"Please enter %s.",
										"valid_email"=>"Please enter valid %s.",
									)
			);

			$this->form_validation->set_rules('fname', 'First Name', 'trim|required');
			$this->form_validation->set_rules('lname', 'Last Name', 'trim|required');
			$this->form_validation->set_rules('phone', 'Mobile', 'trim|required');
			$this->form_validation->set_rules('address', 'Address', 'trim|required');

			if ($this->form_validation->run() !== false) {

				$type = "basic";
				if (isset($post['is_dealer']))
					$type = "dealer";

				$data = array('c_fname' => trim($post['fname']),
								'c_lname' => trim($post['lname']),
								'c_email' => trim($post['email']),
								'c_phone' => trim($post['phone']),
								'c_address' => trim($post['address']),
								'c_city' => trim($post['city']),
								'c_type' => $type,
							  );

				$ret = $this->common_model->updateData(CUSTOMER, $data, $where);

				if ($ret > 0) {
					$flash_arr = array('flash_type' => 'success',
										'flash_msg' => 'Customer updated successfully.'
									);
				}else{
					$flash_arr = array('flash_type' => 'error',
										'flash_msg' => 'An error occurred while processing.'
									);
				}
				$this->session->set_flashdata($flash_arr);
				redirect("customer");
			}
			$data['error_msg'] = validation_errors();
		}
		$data['customer'] = $customer = $this->common_model->selectData(CUSTOMER, '*', $where);

		if (empty($customer)) {
			redirect('customer');
		}
		$data['view'] = "add_edit";
		$this->load->view('content', $data);
	}


	public function delete()
	{
		$post = $this->input->post();

		if ($post) {
			/*If customer is dealer than also remove entry from user table.*/
			$customer = $this->common_model->selectData(CUSTOMER, 'c_type', array('c_id' => $post['id'] ));
			if ($customer[0]->c_type=="dealer")
			{
					$this->common_model->deleteData(USER, array('cust_id' => $post['id'] ));
			}

			$ret = $this->common_model->deleteData(CUSTOMER, array('c_id' => $post['id'] ));
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
