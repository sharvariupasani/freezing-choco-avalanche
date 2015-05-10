<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Purchase extends CI_Controller {

	function __construct(){
		parent::__construct();

		is_login();

		$this->user_session = $this->session->userdata('user_session');

		if (!@in_array("deal", @array_keys(config_item('user_role')[$this->user_session['role']])) && $this->user_session['role'] != 'a') {
			redirect("dashboard");
		}

		/*if (!@in_array($this->router->fetch_method(), @config_item('user_role')[$this->user_session['role']]['deal']) && $this->user_session['role'] != 'a') {
			redirect("deal");
		}*/
	}

	public function index()
	{
		//pr($this->user_session);
		$data['view'] = "index";
		$this->load->view('content', $data);
	}

	public function ajax_list($limit=0)
	{
		$post = $this->input->post();
		$columns = array();
		$columns = array(
			array( 'alias' => 'cat_name','db' => 'c.name', 'dt' => 0 ),
			array( 'db' => 'p.brand',  'dt' => 1 ),
			array( 'db' => 'p.name',  'dt' => 2 ),
			array( 'db' => 'pp.vendor',  'dt' => 3 ),
			array( 'db' => 'pp.quantity',  'dt' => 4 ),
			array( 'db' => 'pp.description',  'dt' => 5 ),
			array(
				'db'        => 'pp.purchase_date',
				'dt'        => 6,
				'formatter' => function( $d, $row ) {
					return date( 'jS M y', strtotime($d));
				}
			),
			array( 'db' => 'pp.id',
					'dt' => 7,
					'formatter' => function( $d, $row ) {
						if ($this->user_session['role'] == 'd')
						return '<i class="fa fa-edit"></i> / <i class="fa fa-trash-o"></i>';
						else
						return '<a href="'.site_url('/purchase/edit/'.$d).'" class="fa fa-edit"></a> / <a href="javascript:void(0);" onclick="delete_purchase('.$d.')" class="fa fa-trash-o"></a>';
					}
			),
		);

		$join = array();
		$join[] = array(PRODUCT_P,'p.id = pp.p_id');
		$join[] = array(CATEGORY_C,'p.cat_id = c.id');

		echo json_encode( SSP::simple( $post, PURCHASE_PP, "pp.id", $columns ,$join));exit;
	}

	public function add()
	{
		$post = $this->input->post();
		if ($post) {
			$this->load->library('form_validation');

			$this->form_validation->set_rules('op', 'Opration', 'trim|required');
			$this->form_validation->set_rules('qty', 'Quantity', 'trim|required|integer');
			$this->form_validation->set_rules('vendor', 'Vendor', 'trim|required');
			$this->form_validation->set_rules('product_id', 'Product Id', 'trim|required|integer');
			$res = array();
			if ($this->form_validation->run() !== false) {
					$qty = abs($post['qty']);
					$qty = ($post['op'] == 'plus')?$qty:(-1 * $qty);
					$data = array();
					$data['p_id'] = trim($post['product_id']);
					$data['quantity'] = trim($qty);
					$data['vendor'] = trim($post['vendor']);
					$data['description'] = trim($post['description']);
					$ret = $this->common_model->insertData(PURCHASE, $data);
					if($ret > 0)
					{
						$where = array("id"=>$post['product_id']);
						$data = array("stock_onhand"=>"stock_onhand + $qty");
						$ret = $this->common_model->updateData(PRODUCT,$data,$where,false);
						if ($ret)
						{
							$res = array('status' => 'success',
										'message' => 'Product purchase added successfully.'
							);
						}else
						{
							$res = array('status' => 'error',
										'message' => 'Please remove purchase record and try again later.'
							);
						}
					}
					else
					{
						$res = array('status' => 'success',
										'message' => 'Error occured.'
									);
					}
			}
			else
			{
				$res= array('status' => "error","message" => validation_errors());
			}
			echo json_encode($res);
		}
	}

	public function edit($id)
	{
		$post = $this->input->post();
		if ($post) {

		}

		$data['purchase'] = $purchase = $this->common_model->selectData(PURCHASE, '*', $where);
		

		if (empty($purchase)) {
			redirect('purchase');
		}
		$data['view'] = "add_edit";
		$this->load->view('content', $data);
	}

	public function delete()
	{
		$post = $this->input->post();

		if ($post) {
			$res = array();

			$purchase = $this->common_model->selectData(PURCHASE,"*", array('id' => $post['id'] ));
			$purchase = $purchase[0];
			$qty = $purchase->quantity;
			$qty = -1 * $qty; // to add/remove from stock_onhand from product table.
			
			$where = array("id"=>$purchase->p_id);
			$data = array("stock_onhand"=>"stock_onhand + $qty");
			$ret = $this->common_model->updateData(PRODUCT,$data,$where,false);
			if ($ret)
			{
				$ret = $this->common_model->deleteData(PURCHASE, array('id' => $post['id'] ));
				if ($ret > 0) {
					$res = array('status' => 'success',
							'message' => 'Purchase removed successfully.'
					);

				}else{
					$res = array('status' => 'error',
							'message' => 'Please remove purchase record and try again later.'
							);
				}
				
			}else
			{
				$res = array('status' => 'error',
							'message' => 'Please remove purchase record and try again later.'
				);
			}
			echo json_encode($res);exit;
		}
	
	}
}
