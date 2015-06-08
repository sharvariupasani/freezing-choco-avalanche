<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Takein extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->user_session = $this->session->userdata('user_session');
	}

	public function index()
	{
		$data['view'] = "index";
		
		$session = $this->user_session;
		if ($session['role'] == 'd')
		{
			$data['is_dealer'] =  true;
		}

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
			array(
				'db'        => 's_status',
				'sort' => 's_status',
				'dt'        => 7,
				'formatter' => function( $d, $row ) {
					$id = $row['s_id'];
					$status = $d;
					$op = "";
					if (hasAccess("takein","updateStatus") && $status !='rejected')
						$op = '<a href="javascript:void(0);" onclick="update_status('.$id.')" class="label '.$status.'">'.$status ."</a>" ;
					else
						$op = '<a href="javascript:void(0);" class="label '.$status.'">'.$status ."</a>" ;
					return $op;
				}
			),
			array( 'db' => 'CONCAT(s_id,"|",s_custid,"|",IFNULL(s_invoiceid, ""))',
					'dt' => 8,
					'formatter' => function( $d, $row ) {
						
						list($id,$cust_id,$invoice_id) = explode("|",$d);
						$status = $row['s_status'];
						//print_r($row);exit;
						$op = array();
						
						if (hasAccess("takein","edit"))
						{
							$op[] = '<a href="'.site_url('/takein/edit/'.$id).'" class="fa fa-edit" title="Edit Takein"></a>';
						}

						if (hasAccess("takein","delete"))
							$op[] = '<a href="javascript:void(0);" onclick="delete_takein('.$id.')" class="fa fa-trash-o" title="Remove Takein"></a>';
						
						if ($status != "rejected")
						{
							if ($invoice_id == "")
							{
								if (hasAccess("invoice","add"))
								{
									$op[] = '<a href="'.site_url("/invoice/add/".$id).'" class="fa fa-save" title="Generate bill"></a>';
									$op[] = '<input type="checkbox" id="'.$id.'" class="'.$cust_id.'" onchange="mergeTakein(this)"></a>';
								}
							}
							else
							{
								if (hasAccess("invoice","edit"))
									$op[] = '<a href="'.site_url("/invoice/edit/".$invoice_id).'" class="fa fa-eye" title="View bill"></a>';
							}
						}
					
						if (hasAccess("takein","updateStatus") && $status =='taken')
							$op[] = '<a href="javascript:void(0);" onclick="update_status_popup('.$id.',\'rejected\')" class="fa fa-times-circle"></a>' ;

						return implode(" / ",$op);				
					}
			),
		);
		
		$join[] = array(CUSTOMER,"c_id = s_custid");
		$session = $this->user_session;
		$custom_where = array();

		if ($session['role'] == 'd')
			$custom_where = array("c_id"=>$session['cust_id']);

		echo json_encode( SSP::simple( $post, SERVICE, "s_id", $columns ,$join,$custom_where ));exit;
	}

	public function add()
	{
		$post = $this->input->post();
		$session = $this->user_session;
		if ($post) {

			$this->load->library('form_validation');
			$post['remark'] = implode("||",$post['remark']);

			$this->form_validation->set_rules('cust_id', 'Customer', 'trim|required');
			$this->form_validation->set_rules('phonename', 'Mobile Info', 'trim|required');
			$this->form_validation->set_rules('imei', 'IMEI', 'trim|required');
			//$this->form_validation->set_rules('remark', 'Mobile remark', 'trim|required');
			
			if ($session['role'] == 'd')
				 $post['cust_id'] =  $session['cust_id'];

			if ($this->form_validation->run() !== false) {
				
				$data = array('s_custid' => $post['cust_id'],
							's_phonename' => $post['phonename'],
							's_imei' => $post['imei'],
							's_remark' => $post['remark'],
							's_status' => "taken",
							's_takeinid' => $post['takein_id'],
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
		
		$data['is_dealer'] = false;
		if ($session['role'] == 'd')
		{
			$data['customer'] = $this->common_model->customerTitleById($session['cust_id']);
			$data['is_dealer'] =  true;
		}
				
		$data['takeinid'] =  $this->common_model->genTakeinId();

		$data['view'] = "add_edit";
		$this->load->view('content', $data);
	}

	public function edit($id)
	{	
		$data['view'] = "add_edit";
		$where = "s_id = ".$id;
		$session = $this->user_session;
		$post = $this->input->post();
		if ($post) {
			$this->load->library('form_validation');

			$this->form_validation->set_rules('cust_id', 'Customer', 'trim|required');
			$this->form_validation->set_rules('phonename', 'Mobile Info', 'trim|required');
			$this->form_validation->set_rules('imei', 'IMEI', 'trim|required');
			//$this->form_validation->set_rules('remark', 'Mobile remark', 'trim|required');
			
			if ($session['role'] == 'd')
				 $post['cust_id'] =  $session['cust_id'];

			if ($this->form_validation->run() !== false) {
				$post['remark'] = implode("||",$post['remark']);

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

		$data['is_dealer'] = false;
		if ($session['role'] == 'd')
				 $data['is_dealer'] =  true;

		$this->load->view('content', $data);
	}

	public function updateStatus()
	{
		$statusArray = array("taken","repaired","done");
		$post = $this->input->post();
		if ($post) {
			if (!$post['status'])
			{
				$ret = $this->common_model->selectData(SERVICE,"s_status", array('s_id' => $post['id'] ));
				$status = $ret[0]->s_status;
				$key = array_search($status,$statusArray);

				if ($key === FALSE)
					$key = 0;
				else
					$key++;

				$key = $key%3;
				$status = $statusArray[$key];
			}
			else
			{
				$status = $post['status'];
				$session = $this->user_session;
				if($session['pass'] != sha1($post['passkey']))
				{
					echo "error";exit;
				}
			}
			$data = array('s_status' => $status);

			if ($statusArray[$key] == "done" || $statusArray[$key] == "rejected")
				$data['s_deliverydate'] = date('Y-m-d');

			$ret = $this->common_model->updateData(SERVICE, $data, array('s_id' => $post['id'] ));

			if ($ret > 0) {
				echo "success";
			}else{
				echo "error";
			}
			exit;
		}
	}

	public function delete()
	{
		$post = $this->input->post();

		if ($post) {
			$ret = $this->common_model->deleteData(SERVICE, array('s_id' => $post['id'] ));
			if ($ret > 0) {
				echo "success";
			}else{
				echo "error";
			}
			exit;
		}
	}
}
