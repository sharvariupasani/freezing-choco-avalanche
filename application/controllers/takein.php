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

	public function getinvoice()
	{
		//pr($this->user_session);
		$data['view'] = "invoice";
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
				#echo success_msg_box('Deal deleted successfully.');;
			}else{
				echo "error";
				#echo error_msg_box('An error occurred while processing.');
			}
		}
	}
}