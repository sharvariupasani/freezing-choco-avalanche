<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Category extends CI_Controller {

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
			array( 'db' => 'dc_catname', 'dt' => 0 ),
			array( 'db' => 'dc_catdetails',  'dt' => 1 ),
			array(
				'db'        => 'dc_createdate',
				'dt'        => 2,
				'formatter' => function( $d, $row ) {
					return date( 'jS M y', strtotime($d));
				}
			),
			array( 'db' => 'CONCAT(dc_catid,"|",dc_status)',
					'dt' => 3,
					'formatter' => function( $d, $row ) {
						list($id,$status) = explode("|",$d);
						$status = ($status == "1")?"active":"inactive";
						return '<a href="'.site_url('/category/edit/'.$id).'" class="fa fa-edit"></a> / <a href="javascript:void(0);" onclick="delete_category('.$id.')" class="fa fa-trash-o"></a> / <a href="javascript:void(0);" data-dc_catid="'.$id.'" class="fa fa-eye deal-category-status '.$status.'" title="'.$status.'" alt="'.$status.'"></a>';
					}
			),
		);
		echo json_encode( SSP::simple( $post, DEAL_CATEGORY, "dc_catid", $columns ) );exit;
	}

	public function add()
	{
		$post = $this->input->post();
		if ($post) {
			#pr($post);
			$error = array();
			$e_flag=0;

			if(trim($post['dc_catname']) == ''){
				$error['dc_catname'] = 'Please enter category name.';
				$e_flag=1;
			}
			if(trim($post['dc_catdetails']) == ''){
				$error['dc_catdetails'] = 'Please enter category detail.';
				$e_flag=1;
			}

			if ($_FILES['category_picture']['error'] > 0) {
				$error['category_picture'] = 'Error in image upload.';
				$e_flag=1;
			}

			if ($_FILES['category_picture']['error'] == 0) {
				$config['overwrite'] = TRUE;
				$config['upload_path'] = DOC_ROOT_CATEGORY_IMG;
				$config['allowed_types'] = 'gif|jpg|png|bmp|jpeg';

				$img_arr = explode('.',$_FILES['category_picture']['name']);
				$img_arr = array_reverse($img_arr);

				$config['file_name'] = $post['dc_catimg'] = time()."_img.".$img_arr[0];

				$this->load->library('upload', $config);

				if ( ! $this->upload->do_upload("category_picture"))
				{
					$error['category_picture'] = $this->upload->display_errors();
					$e_flag=1;
				}
			}

			if ($e_flag == 0) {
				$data = array('dc_catname' => $post['dc_catname'],
								'dc_catdetails' => $post['dc_catdetails'],
								'dc_catimg' => $post['dc_catimg'],
								'dc_status' => $post['dc_status']
							);
				$ret = $this->common_model->insertData(DEAL_CATEGORY, $data);

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
			$data['error_msg'] = $error;
		}
		$data['view'] = "add_edit";
		$this->load->view('content', $data);
	}


	public function edit($id)
	{
		if ($id == "" || $id <= 0) {
			redirect('category');
		}

		$where = 'dc_catid = '.$id;

		$post = $this->input->post();
		if ($post) {

			$error = array();
			$e_flag=0;
			if(trim($post['dc_catname']) == ''){
				$error['dc_catname'] = 'Please enter category name.';
				$e_flag=1;
			}
			if(trim($post['dc_catdetails']) == ''){
				$error['dc_catdetails'] = 'Please enter category detail.';
				$e_flag=1;
			}
			/*if ($_FILES['category_picture']['error'] > 0) {
				$error['category_picture'] = 'Error in image upload.';
				$e_flag=1;
			}*/

			if ($_FILES['category_picture']['error'] == 0) {
				$config['overwrite'] = TRUE;
				$config['upload_path'] = DOC_ROOT_CATEGORY_IMG;
				$config['allowed_types'] = 'gif|jpg|png|bmp|jpeg';

				$img_arr = explode('.',$_FILES['category_picture']['name']);
				$img_arr = array_reverse($img_arr);

				$config['file_name'] = $post['dc_catimg'] = time()."_img.".$img_arr[0];

				$this->load->library('upload', $config);

				if ( ! $this->upload->do_upload("category_picture"))
				{
					$error['category_picture'] = $this->upload->display_errors();
					$e_flag=1;
				}
				else
				{
					$category = $this->common_model->selectData(DEAL_CATEGORY, '*', $where);
					if ($category[0]->dc_catimg != "")
						unlink(DOC_ROOT_CATEGORY_IMG.$category[0]->dc_catimg);
				}
			}

			if ($e_flag == 0) {
				$data = array('dc_catname' => $post['dc_catname'],
								'dc_catdetails' => $post['dc_catdetails'],
								'dc_status' => $post['dc_status']
							);
				if (isset($post['dc_catimg']))
					$data['dc_catimg'] = $post['dc_catimg'];
				$ret = $this->common_model->updateData(DEAL_CATEGORY, $data, $where);

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
			$data['error_msg'] = $error;

		}

		$data['category'] = $category = $this->common_model->selectData(DEAL_CATEGORY, '*', $where);

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
			$ret = $this->common_model->deleteData(DEAL_CATEGORY, array('dc_catid' => $post['id'] ));
			if ($ret > 0) {
				echo "success";
				#echo success_msg_box('Category deleted successfully.');

			}else{
				echo "error";
				#echo error_msg_box('An error occurred while processing.');
			}
		}
	}

	public function categorystatusupdate()
	{
		$post = $this->input->post();

		$where = array();
		$where['dc_catid'] = $post['id'];

		$data = array();
		$data['dc_status'] = ($post['flag']=="1")?"1":"0";

		$ret = $this->common_model->updateData(DEAL_CATEGORY, $data, $where);
		echo $ret;exit;
	}
}
