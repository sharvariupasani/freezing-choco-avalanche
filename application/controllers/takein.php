<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Takein extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->user_session = $this->session->userdata('user_session');
	}

	public function index()
	{
		$data['view'] = "index";
		$this->load->view('content', $data);
	}

	public function ajax_list($limit=0)
	{
		$post = $this->input->post();

		$columns = array(
			array( 'db' => 'c_fname', 'dt' => 0 ),
			array( 'db' => 'c_phone',  'dt' => 1 ),
			array( 'db' => 's_imei',  'dt' => 2 ),
			array( 'db' => 's_phonename',  'dt' => 3 ),
			array( 'db' => 's_remark',  'dt' => 4 ),
			array(
				'db'        => 's_creationdate',
				'dt'        => 5,
				'formatter' => function( $d, $row ) {
					return date( 'jS M y', strtotime($d));
				}
			),
			array(
				'db'        => 's_deliverydate',
				'dt'        => 6,
				'formatter' => function( $d, $row ) {
					return date( 'jS M y', strtotime($d));
				}
			),
			array( 'db' => 's_id',
					'dt' => 7,
					'formatter' => function( $d, $row ) {
						return '<a href="'.site_url('/takein/edit/'.$d).'" class="fa fa-edit"></a> / <a href="javascript:void(0);" onclick="delete_takein('.$d.')" class="fa fa-trash-o"></a>';
					}
			),
		);
		
		$join[] = array(CUSTOMER,"c_id = s_custid");

		echo json_encode( SSP::simple( $post, SERVICE, "s_id", $columns ,$join,$custom_where ));exit;
	}

	public function add()
	{
		$post = $this->input->post();
		if ($post) {
			$this->load->library('form_validation');

			$this->form_validation->set_rules('cust_id', 'Customer', 'trim|required');
			$this->form_validation->set_rules('phonename', 'Mobile Info', 'trim|required');
			$this->form_validation->set_rules('imei', 'IMEI', 'trim|required');
			$this->form_validation->set_rules('remark', 'Mobile remark', 'trim|required');

			if ($this->form_validation->run() !== false) {
				$data = array('s_custid' => $post['cust_id'],
							's_phonename' => $post['phonename'],
							's_imei' => $post['imei'],
							's_remark' => $post['remark'],
							);
				$ret = $this->common_model->insertData(SERVICE, $data);

				if ($ret > 0) {
					$flash_arr = array('flash_type' => 'success',
										'flash_msg' => 'Takein added successfully.'
									);
				}else{
					$flash_arr = array('flash_type' => 'error',
										'flash_msg' => 'An error occurred while processing!'
									);

				}
				$this->session->set_flashdata($flash_arr);
				redirect("takein");
			}
			$data['error_msg'] = validation_errors();
		}
		
		$data['view'] = "add_edit";
		$this->load->view('content', $data);
	}

	public function edit($id)
	{	
		$data['view'] = "add_edit";
		$where = "s_id = ".$id;
		if ($post) {
			$this->load->library('form_validation');

			$this->form_validation->set_rules('cust_id', 'Customer', 'trim|required');
			$this->form_validation->set_rules('phonename', 'Mobile Info', 'trim|required');
			$this->form_validation->set_rules('imei', 'IMEI', 'trim|required');
			$this->form_validation->set_rules('remark', 'Mobile remark', 'trim|required');

			if ($this->form_validation->run() !== false) {
				$data = array('s_custid' => $post['cust_id'],
							's_phonename' => $post['phonename'],
							's_imei' => $post['imei'],
							's_remark' => $post['remark'],
							);
				$ret = $this->common_model->updateData(SERVICE, $data,$where);

				if ($ret > 0) {
					$flash_arr = array('flash_type' => 'success',
										'flash_msg' => 'Takein added successfully.'
									);
				}else{
					$flash_arr = array('flash_type' => 'error',
										'flash_msg' => 'An error occurred while processing!'
									);

				}
				$this->session->set_flashdata($flash_arr);
				redirect("takein");
			}
			$data['error_msg'] = validation_errors();
		}

		$data['takein'] = $takein = $this->common_model->selectData(SERVICE, '*',$where);
		$data['customer'] = $this->common_model->customerTitleById($takein[0]->s_custid);
		if (empty($takein)) {
			redirect('takein');
		}
		$this->load->view('content', $data);
	}

	public function delete()
	{
		$post = $this->input->post();

		if ($post) {
			$ret = $this->common_model->deleteData(DEAL_DETAIL, array('dd_autoid' => $post['id'] ));
			if ($ret > 0) {
				echo "success";
			}else{
				echo "error";
			}
		}
	}
}
