<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->user_session = $this->session->userdata('user_session');
	}

	public function index()
	{
		#pr($this->user_session);
		$data['view'] = "index";
		$this->load->view('content', $data);
	}

	public function statistics()
	{
		$data = array();
		$data['customers'] = $this->common_model->getCount(CUSTOMER);
		$data['takeins'] = $this->common_model->selectData(SERVICE,"s_status AS type,COUNT(s_id) AS cnt","","","","s_status");
		$data['products'] = $this->common_model->getCount(PRODUCT);
		$data['invoices'] = $this->common_model->getCount(INVOICE);

		echo json_encode($data);exit;
	}

	public function latestProduct()
	{
		$data = array();
		$data = $this->common_model->getLatestProducts();
		echo json_encode($data);exit;
	}

	public function latestTakein()
	{
		$data = array();
		$data = $this->common_model->getLatestTakeins();
		echo json_encode($data);exit;
	}
}
