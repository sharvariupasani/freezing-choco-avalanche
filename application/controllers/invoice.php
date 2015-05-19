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
			$this->form_validation->set_rules('sale_date', 'Date', 'trim|required');

			if ($this->form_validation->run() !== false) {
				$products = $post["product"];
				$services = $post["service"];
				$sale_date = $post["sale_date"];
				$total = 0;
				$amount = 0;
				$taxRate = 9.3;
				
				foreach ($products as $product)
				{
					$amount += $product["p_price"];
				}
				foreach ($services as $service)
				{
					$amount += $service["s_price"];
				}
				$tax = $amount * 9.3/100;
				$total = $amount + $tax;

				$data = array(
							'c_id' => $post['cust_id'],
							'amount' => $amount,
							'total' => $total,
							'sale_date' => date('Y-m-d', strtotime($sale_date))
							);
				$ret = $this->common_model->insertData(INVOICE, $data);

				if ($ret > 0) {
					// add entry of product in order table
					if ($products > 0)
					{
							foreach ($products as $product)
							{
									$this->common_model->addProductToOrder($product,$ret);
							}
					}
					
					// add service to product table.
					if ($services > 0)
					{
							foreach ($services as $service)
							{
									$this->common_model->addServiceToOrder($service,$ret);
							}
					}

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
		$post = $this->input->post();
		$data['view'] = "add_edit";
		$where = "id = ".$id;
		if ($post) {
			$this->load->library('form_validation');

			$this->form_validation->set_rules('cust_id', 'Customer', 'trim|required');
			$this->form_validation->set_rules('sale_date', 'Date', 'trim|required');

			if ($this->form_validation->run() !== false) {
				$products = $post["product"];
				$services = $post["service"];
				$sale_date = $post["sale_date"];
				
				$total = 0;
				$amount = 0;
				$taxRate = 9.3;
				
				foreach ($products as $product)
				{
					$amount += $product["p_price"];
				}
				foreach ($services as $service)
				{
					$amount += $service["s_price"];
				}
				
				$tax = ($amount * 9.3)/100;
				
				$total = $amount + $tax;
				
				$data = array(
							'c_id' => $post['cust_id'],
							'amount' => $amount,
							'total' => $total,
							'sale_date' => date('Y-m-d', strtotime($sale_date))
							);
				$ret = $this->common_model->updateData(INVOICE, $data,$where);

				if ($ret > 0) {
					// add entry of product in order table
					if ($products > 0)
					{
							foreach ($products as $product)
							{
									if(isset($product['p_oid']) && $product['p_oid'] == "")
										$this->common_model->addProductToOrder($product,$id);
							}
					}
					
					// add service to product table.
					if ($services > 0)
					{
							foreach ($services as $service)
							{
									if(isset($service['s_oid']) && $service['s_oid'] != "")
										$this->common_model->updateServiceToOrder($service);
									else
										$this->common_model->addServiceToOrder($service,$id);
							}
					}


					$flash_arr = array('flash_type' => 'success',
										'flash_msg' => 'Bill updated successfully.'
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
		$data['products'] = $this->common_model->productDetailByInvoiceId($invoice[0]->id);
		$data['services'] = $this->common_model->serviceDetailByInvoiceId($invoice[0]->id);
		
		if (empty($invoice)) {
			redirect('invoice');
		}
		$this->load->view('content', $data);
	}

	public function delete()
	{
		$post = $this->input->post();

		if ($post) {
			$ret = $this->common_model->deleteData(INVOICE, array('id' => $post['id'] ));
			if ($ret > 0) {
				echo "success";
				#echo success_msg_box('Deal deleted successfully.');;
			}else{
				echo "error";
				#echo error_msg_box('An error occurred while processing.');
			}
		}
	}

	public function deleteOrder()
	{
		$post = $this->input->post();

		if ($post) {
			$data = $this->common_model->selectData(ORDER, array('id' => $post['id'] ));

			if ($data[0]->order_type == 'product')
			{
				$qty = $this->common_model->updateProductQty($data[0]->p_id,$data[0]->quantity);
			}

			$ret = $this->common_model->deleteData(ORDER, array('id' => $post['id'] ));
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
