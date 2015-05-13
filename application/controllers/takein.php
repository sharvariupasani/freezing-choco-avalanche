<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Takein extends CI_Controller {

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
			array( 'db' => 's_status',  'dt' => 7 ),
			array( 'db' => 's_id',
					'dt' => 8,
					'formatter' => function( $d, $row ) {
						if ($this->user_session['role'] == 'd')
						return '<i class="fa fa-edit"></i> / <i class="fa fa-trash-o"></i>';
						else
						return '<a href="'.site_url('/takein/edit/'.$d).'" class="fa fa-edit"></a> / <a href="javascript:void(0);" onclick="delete_deal('.$d.')" class="fa fa-trash-o"></a>';
					}
			),
		);
		
		$join[] = array(CUSTOMER,"c_id = s_custid");

		echo json_encode( SSP::simple( $post, SERVICE, "s_id", $columns ,$join,$custom_where ));exit;
	}

	public function add()
	{
		$data['view'] = "add_edit";
		$this->load->view('content', $data);
	}

	public function edit($id)
	{	
		$data['view'] = "add_edit";
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
			}else{
				echo "error";
			}
		}
	}
}
