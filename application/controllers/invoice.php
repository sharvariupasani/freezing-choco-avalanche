<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Invoice extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->user_session = $this->session->userdata('user_session');
	}

	public function index()
	{
		//pr($this->user_session);
		$data['view'] = "index";
		$this->load->view('content', $data);
	}

	public function getinvoice()
	{
		//pr($this->user_session);
		$data['view'] = "invoice";
		$this->load->view('content', $data);
	}

	public function printinvoice()
	{
		//pr($this->user_session);
		$data['view'] = "index";
		$data['view'] = "print";
		$data['noheader'] = "1";
		$data['nosidebar'] = "1";
		$data['nofooter'] = "1";
		$data['content_class'] = "invoice";
		$data['body_class'] = "";
		$this->load->view('content', $data);
	}

	public function ajax_list($limit=0)
	{
		$post = $this->input->post();
		$columns = array();
		$columns = array(
			array( 'db' => 'cu.c_fname', 'dt' => 0 ),
			array( 'db' => 'cu.c_phone',  'dt' => 1 ),
			array( 'db' => 'i.total',  'dt' => 2 ),
			array(
				'db'        => 'i.sale_date',
				'dt'        => 3,
				'formatter' => function( $d, $row ) {
					return date( 'jS M y', strtotime($d));
				}
			),
			array( 'db' => 'i.id',
					'dt' => 4,
					'formatter' => function( $d, $row ) {
						return '<a href="'.site_url('/invoice/edit/'.$d).'" class="fa fa-edit"></a> / <a href="javascript:void(0);" onclick="delete_invoice('.$d.')" class="fa fa-trash-o"></a>';
					}
			),
		);

		$join[] = array(CUSTOMER_CU,"cu.c_id = i.c_id");

		echo json_encode( SSP::simple( $post, INVOICE_I, "i.id", $columns ,$join,$custom_where ));exit;
	}

	public function add()
	{
		$post = $this->input->post();
		if ($post) {
			$this->load->library('form_validation');

			$this->form_validation->set_rules('cust_id', 'Customer', 'trim|required');
			$this->form_validation->set_rules('total', 'Total', 'trim|required');

			if ($this->form_validation->run() !== false) {
				$data = array(
							'c_id' => $post['cust_id'],
							'total' => $post['total']
							);
				$ret = $this->common_model->insertData(INVOICE, $data);

				if ($ret > 0) {
					$flash_arr = array('flash_type' => 'success',
										'flash_msg' => 'Invoice added successfully.'
									);
				}else{
					$flash_arr = array('flash_type' => 'error',
										'flash_msg' => 'An error occurred while processing!'
									);

				}
				$this->session->set_flashdata($flash_arr);
				redirect("invoice");
			}
			$data['error_msg'] = validation_errors();
		}
		$data['view'] = "add_edit";
		$this->load->view('content', $data);
	}

	public function edit($id)
	{	
		$data['view'] = "add_edit";
		$where = "id = ".$id;
		if ($post) {
			$this->load->library('form_validation');

			$this->form_validation->set_rules('cust_id', 'Customer', 'trim|required');
			$this->form_validation->set_rules('total', 'Total', 'trim|required');

			if ($this->form_validation->run() !== false) {
				$data = array('c_id' => $post['cust_id'],
							'total' => $post['total'],
							);
				$ret = $this->common_model->updateData(INVOICE, $data,$where);

				if ($ret > 0) {
					$flash_arr = array('flash_type' => 'success',
										'flash_msg' => 'Invoice added successfully.'
									);
				}else{
					$flash_arr = array('flash_type' => 'error',
										'flash_msg' => 'An error occurred while processing!'
									);

				}
				$this->session->set_flashdata($flash_arr);
				redirect("invoice");
			}
			$data['error_msg'] = validation_errors();
		}

		$data['invoice'] = $invoice= $this->common_model->selectData(INVOICE, '*',$where);
		$data['customer'] = $this->common_model->customerTitleById($invoice[0]->c_id);
		if (empty($invoice)) {
			redirect('invoice');
		}
		$this->load->view('content', $data);
	}

	public function delete()
	{
		if (!@in_array("delete", @config_item('user_role')[$this->user_session['role']]['deal']) && $this->user_session['role'] != 'a') {
			echo "redirect";
			exit;
		}

		$post = $this->input->post();

		if ($post) {
			$ret = $this->common_model->deleteData(DEAL_DETAIL, array('dd_autoid' => $post['id'] ));
			if ($ret > 0) {
				echo "success";
				#echo success_msg_box('Deal deleted successfully.');;
			}else{
				echo "error";
				#echo error_msg_box('An error occurred while processing.');
			}
		}
	}
}
