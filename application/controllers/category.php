<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category extends CI_Controller {

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

	public function ajax_list($limit=0)
	{
		$post = $this->input->post();
		$columns = array();
		$columns = array(
			array( 'db' => 'name', 'dt' => 0 ),
			array( 'db' => 'description',  'dt' => 1 ),
			array( 'db' => 'id',
					'dt' => 2,
					'formatter' => function( $e, $row ) {
						return '<a href="'.site_url('/category/edit/'.$e).'" class="fa fa-edit"></a> / <a href="javascript:void(0);" onclick="delete_category('.$e.')" class="fa fa-trash-o"></a>';
					}
			),
		);
		echo json_encode( SSP::simple( $post, CATEGORY, "id", $columns ) );exit;
	}

	public function add()
	{
		$post = $this->input->post();
		if ($post) {
			#pr($post);
			$this->load->library('form_validation');
			$this->form_validation->set_rules('name', 'Product Category', 'trim|required');
		
			if ($this->form_validation->run() !== false) {
				$data = array('name' => $post['name'],
								'description' => $post['description']
							);
				$ret = $this->common_model->insertData(CATEGORY, $data);

				if ($ret > 0) {
					$flash_arr = array('flash_type' => 'success',
										'flash_msg' => 'Category added successfully.'
									);
				}else{
					$flash_arr = array('flash_type' => 'error',
										'flash_msg' => 'An error occurred while processing.'
									);

				}
				$this->session->set_flashdata($flash_arr);
				redirect("category");
			}
			$data['error_msg'] = validation_errors();
		}
		$data['view'] = "add_edit";
		$this->load->view('content', $data);
	}


	public function edit($id)
	{
		if ($id == "" || $id <= 0) {
			redirect('category');
		}

		$where = 'id = '.$id;

		$post = $this->input->post();
		if ($post) {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name', 'Product Category', 'trim|required');
		
		if ($this->form_validation->run() !== false) {
				$data = array('name' => $post['name'],
								'description' => $post['description']
							);
				$ret = $this->common_model->updateData(CATEGORY, $data, $where);

				if ($ret > 0) {
					$flash_arr = array('flash_type' => 'success',
										'flash_msg' => 'Category updated successfully.'
									);

				}else{
					$flash_arr = array('flash_type' => 'error',
										'flash_msg' => 'An error occurred while processing.'
									);
				}
				$this->session->set_flashdata($flash_arr);
				redirect("category");
			}
			$data['error_msg'] = validation_errors();

		}

		$data['category'] = $category = $this->common_model->selectData(CATEGORY, '*', $where);

		if (empty($category)) {
			redirect('category');
		}
		$data['view'] = "add_edit";
		$this->load->view('content', $data);
	}


	public function delete()
	{
		$post = $this->input->post();

		if ($post) {
			$ret = $this->common_model->deleteData(CATEGORY, array('id' => $post['id'] ));
			if ($ret > 0) {
				echo "success";
				#echo success_msg_box('Category deleted successfully.');

			}else{
				echo "error";
				#echo error_msg_box('An error occurred while processing.');
			}
		}
	}

}
