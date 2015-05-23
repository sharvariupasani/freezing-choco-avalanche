<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Purchase extends CI_Controller {

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

	public function ajax_list($id="")
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
						$op = array();
						if (hasAccess("purchase","edit"))
							$op[] = '<a href="'.site_url('/purchase/edit/'.$d).'" class="fa fa-edit"></a>';

						if (hasAccess("purchase","delete"))
							$op[] = '<a href="javascript:void(0);" onclick="delete_purchase('.$d.',true)" class="fa fa-trash-o"></a>';

						if (hasAccess("purchase","delete"))
							$op[] = '<a href="javascript:void(0);" onclick="delete_purchase('.$d.')" class="fa fa-times"></a>';

						return implode(" / ",$op);
					}
			),
		);

		$join = array();
		$join[] = array(PRODUCT_P,'p.id = pp.p_id');
		$join[] = array(CATEGORY_C,'p.cat_id = c.id');
		
		$where = "";
		if ($id != "")
			$where= "pp.p_id =".$id;
		
		echo json_encode( SSP::simple( $post, PURCHASE_PP, "pp.id", $columns ,$join,$where));exit;
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
		if ($id == "" || $id <= 0) {
			redirect('product');
		}
		$where = 'id = '.$id;

		$post = $this->input->post();
		if ($post) {
			$this->load->library('form_validation');

			$this->form_validation->set_rules('op', 'Opration', 'trim|required');
			$this->form_validation->set_rules('qty', 'Quantity', 'trim|required|integer');
			$this->form_validation->set_rules('vendor', 'Vendor', 'trim|required');

			$res = array();
			if ($this->form_validation->run() !== false) {
				$old_purchase = $this->common_model->selectData(PURCHASE, '*', $where);

				if (count($old_purchase) < 1) {
					redirect('purchase');
				}

				$old_qty = $old_purchase[0]->quantity;
				$qty = $post['qty'];
				if ($old_qty != $qty)
				{
					$ret = $this->common_model->selectData(PRODUCT,"stock_onhand",array("id"=>$old_purchase[0]->p_id));
					$stock_onhand = $ret[0]->stock_onhand;
					/* revert old qty from stock on hand */
					$old_qty = -1 * $old_qty;
					$stock_onhand = $stock_onhand + $old_qty;
					
					/* add new qty to stock on hand */
					$qty = ($post['op'] == 'plus')?$qty:(-1 * $qty);
					$stock_onhand = $stock_onhand + $qty;
					
					$data = array("stock_onhand"=>$stock_onhand);
					$ret = $this->common_model->updateData(PRODUCT,$data,array("id"=>$old_purchase[0]->p_id));
				}
				
				$data = array();
				$data['quantity'] = trim($qty);
				$data['vendor'] = trim($post['vendor']);
				$data['description'] = trim($post['description']);
				$ret = $this->common_model->updateData(PURCHASE, $data,$where);
				if ($ret > 0) {
					$flash_arr = array('flash_type' => 'success',
										'flash_msg' => 'Purchase updated successfully.'
									);

				}else{
					$flash_arr = array('flash_type' => 'error',
										'flash_msg' => 'An error occurred while processing.'
									);
				}
				$this->session->set_flashdata($flash_arr);
				redirect("purchase");
			}
			else
				$data['error_msg'] = validation_errors();
		}

		$data['purchase'] = $purchase = $this->common_model->getPurchaseById($id);	

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
			if ($post['flag'] != "")
			{
				$purchase = $this->common_model->selectData(PURCHASE,"*", array('id' => $post['id'] ));
				$purchase = $purchase[0];
				$qty = $purchase->quantity;
				$qty = -1 * $qty; // to add/remove from stock_onhand from product table.
				
				$where = array("id"=>$purchase->p_id);
				$data = array("stock_onhand"=>"stock_onhand + $qty");
				$ret = $this->common_model->updateData(PRODUCT,$data,$where,false);
			}
			else 
				$ret = true;

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
