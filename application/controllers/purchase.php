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
			array( 'db' => 'dd_name', 'dt' => 0 ),
			array( 'db' => 'dd_description',  'dt' => 1 ),
			array( 'db' => 'dd_originalprice',  'dt' => 2 ),
			array( 'db' => 'dd_discount',  'dt' => 3 ),
			array( 'db' => 'dd_listprice',  'dt' => 4 ),
			array(
				'db'        => 'dd_startdate',
				'dt'        => 5,
				'formatter' => function( $d, $row ) {
					return date( 'jS M y', strtotime($d));
				}
			),
			array(
				'db'        => 'dd_expiredate',
				'dt'        => 6,
				'formatter' => function( $d, $row ) {
					return date( 'jS M y', strtotime($d));
				}
			),
			array( 'db' => 'dd_status',  'dt' => 7 ),
			array( 'db' => '(select count(*) from deal_buyout where db_dealid=dd_autoid) as db_buycount',  'dt' => 8 ,'coloumn_name'=>'db_buycount'),
			array( 'db' => 'dd_autoid',
					'dt' => 9,
					'formatter' => function( $d, $row ) {
						if ($this->user_session['role'] == 'd')
						return '<i class="fa fa-edit"></i> / <i class="fa fa-trash-o"></i>';
						else
						return '<a href="'.site_url('/deal/edit/'.$d).'" class="fa fa-edit"></a> / <a href="javascript:void(0);" onclick="delete_deal('.$d.')" class="fa fa-trash-o"></a>';
					}
			),
		);

		$custom_where = array();
		if($this->user_session['role'] == 'd') {
			if(!$this->user_session['dealer_info'])
				$custom_where = array('dd_dealerid'=>0);
			else
				$custom_where = array('dd_dealerid'=>$this->user_session['dealer_info']->de_autoid);
		}

		echo json_encode( SSP::simple( $post, DEAL_DETAIL, "dd_autoid", $columns ,array(),$custom_where ));exit;
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
		
	}

	public function delete()
	{
	
	}
}
