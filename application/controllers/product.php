<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Product extends CI_Controller {

	function __construct(){
		parent::__construct();

		is_login();

		$this->user_session = $this->session->userdata('user_session');

		if (!@in_array("deal", @array_keys(config_item('user_role')[$this->user_session['role']])) && $this->user_session['role'] != 'a') {
			redirect("dashboard");
		}
	}

	public function index()
	{
		#pr($this->user_session);
		$data['view'] = "index";
		$this->load->view('content', $data);
	}

	public function ajax_list($limit=0)
	{
		$post = $this->input->post();
		$columns = array();
		$columns = array(
			array( 'alias' => 'c.name as cat_name','db' => 'c.name', 'dt' => 0 ),
			array( 'db' => 'p.brand',  'dt' => 1 ),
			array( 'db' => 'p.name',  'dt' => 2 ),
			array( 'db' => 'p.description',  'dt' => 3 ),
			array( 'db' => 'p.stock_onhand',  'dt' => 4 ),
			array( 'db' => 'p.price',  'dt' => 5 ),
			array( 'db' => 'p.id',
					'dt' => 6,
					'formatter' => function( $e, $row ) {
						return '<a href="'.site_url('/product/edit/'.$e).'" class="fa fa-edit"></a> / <a href="javascript:void(0);" onclick="delete_product('.$e.')" class="fa fa-trash-o"></a>';
					}
			),
		);
		
		$join = array();
		$join[] = array(CATEGORY_C,'p.cat_id = c.id');
		
		echo json_encode( SSP::simple( $post, PRODUCT_P, "p.id", $columns,$join ) );exit;
	}

	public function add()
	{
		$post = $this->input->post();
		if ($post) {
			#pr($post);
			$error = array();
			$e_flag=0;

			if(trim($post['name']) == ''){
				$error['name'] = 'Please enter Product Name.';
				$e_flag=1;
			}
			
			if(trim($post['category']) == ''){
				$error['category'] = 'Please select Product Category!';
				$e_flag=1;
			}
			
			if(trim($post['description']) == ''){
				$error['description'] = 'Please enter some Description!';
				$e_flag=1;
			}
			
			if(trim($post['brand']) == ''){
				$error['brand'] = 'Please enter Brand Name!';
				$e_flag=1;
			}
			
			if(trim($post['price']) == ''){
				$error['price'] = 'Please enter Product Price!';
				$e_flag=1;
			}
		
			if ($e_flag == 0) {
				$data = array('name' => $post['name'],
							'description' => $post['description'],
							'price' => $post['price'],
							'cat_id' => $post['category'],
							'brand' => $post['brand'],
							);
				$ret = $this->common_model->insertData(PRODUCT, $data);

				if ($ret > 0) {
					$flash_arr = array('flash_type' => 'success',
										'flash_msg' => 'Product added successfully.'
									);
				}else{
					$flash_arr = array('flash_type' => 'error',
										'flash_msg' => 'An error occurred while processing!'
									);

				}
				$this->session->set_flashdata($flash_arr);
				redirect("product");
			}
			$data['error_msg'] = $error;
		}
		$data['category'] = $category = $this->common_model->selectData(CATEGORY, '*', $where);
		$data['view'] = "add_edit";
		$this->load->view('content', $data);
	}


	public function edit($id)
	{
		if ($id == "" || $id <= 0) {
			redirect('product');
		}

		$where = 'id = '.$id;

		$post = $this->input->post();
		if ($post) {

			$error = array();
			$e_flag=0;
			if(trim($post['name']) == ''){
				$error['name'] = 'Please enter product name.';
				$e_flag=1;
			}
						
			if(trim($post['category']) == ''){
				$error['category'] = 'Please select a Category.';
				$e_flag=1;
			}
			
			if(trim($post['description']) == ''){
				$error['description'] = 'Please enter some Description';
				$e_flag=1;
			}
			
			if(trim($post['brand']) == ''){
				$error['brand'] = 'Please enter Brand Name';
				$e_flag=1;
			}
			
			if(trim($post['price']) == ''){
				$error['price'] = 'Please enter Product Price';
				$e_flag=1;
			}
			
			if ($e_flag == 0) {
				$data = array('name' => $post['name'],
							'description' => $post['description'],
							'price' => $post['price'],
							'cat_id' => $post['category'],
							'brand' => $post['brand'],
							);
				$ret = $this->common_model->updateData(PRODUCT, $data, $where);

				if ($ret > 0) {
					$flash_arr = array('flash_type' => 'success',
										'flash_msg' => 'Product updated successfully.'
									);

				}else{
					$flash_arr = array('flash_type' => 'error',
										'flash_msg' => 'An error occurred while processing.'
									);
				}
				$this->session->set_flashdata($flash_arr);
				redirect("product");
			}
			$data['error_msg'] = $error;

		}

		$data['product'] = $product = $this->common_model->selectData(PRODUCT, '*', $where);
		$data['category'] = $category = $this->common_model->selectData(CATEGORY, '*', $where);

		if (empty($product)) {
			redirect('product');
		}
		$data['view'] = "add_edit";
		$this->load->view('content', $data);
	}


	public function delete()
	{
		$post = $this->input->post();

		if ($post) {
			$ret = $this->common_model->deleteData(PRODUCT, array('id' => $post['id'] ));
			if ($ret > 0) {
				echo "success";
				#echo success_msg_box('product deleted successfully.');

			}else{
				echo "error";
				#echo error_msg_box('An error occurred while processing.');
			}
		}
	}
}
