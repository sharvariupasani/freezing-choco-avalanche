<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Deal extends CI_Controller {

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
		if (!@in_array("add", @array_values(config_item('user_role')[$this->user_session['role']]['deal'])) && $this->user_session['role'] != 'a') {
			redirect("dashboard");
		}
		$post = $this->input->post();
		if ($post) {
			#pr($post,1);
			$error = array();
			$e_flag=0;

			if ($post['dd_dealerid'] == "") {
				$error['dd_dealerid'] = 'Please select dealer.';
				$e_flag=1;
			}
			/*if ($post['dd_catid'] == "") {
				$error['dd_catid'] = 'Please select category.';
				$e_flag=1;
			}*/
			if ($post['dd_name'] == "") {
				$error['dd_name'] = 'Please enter deal name.';
				$e_flag=1;
			}

			$offer_data= $post['offer_data'];
			/*if (!isset($offer_data[0]))
			{
				$error['dd_offer'] = 'Please enter atleast one offer.';
				$e_flag=1;
			}
			else
			{*/
				$offerdata =  json_decode($offer_data[0],1);
			/*}

			if ($post['dd_status'] == "") {
				$error['dd_status'] = 'Please select status.';
				$e_flag=1;
			}
			if ($post['dd_includes'] == "") {
				$error['dd_includes'] = 'Please enter what deals includes.';
				$e_flag=1;
			}
			if ($post['dd_policy'] == "") {
				$error['dd_policy'] = 'Please enter deal policy.';
				$e_flag=1;
			}
			if ($post['dd_tags'] == "") {
				$error['dd_tags'] = 'Please enter deal tags.';
				$e_flag=1;
			}*/

			if ($e_flag == 0) {
				$timeperiod = explode("-",$post['dd_timeperiod']);
				$dd_startdate = date('Y-m-d H:i:s',strtotime($timeperiod[0]));
				$dd_expiredate = date('Y-m-d H:i:s',strtotime($timeperiod[1]));

				$data = array(
					'dd_dealerid'=> $post['dd_dealerid'],
					'dd_catid'=> $post['dd_catid'],
					'dd_name'=> $post['dd_name'],
					'dd_createdby'=> $this->user_session['id'], // logged in user's id
					#'dd_createdate'=> date('Y-m-d H:i:s'), // need to add in add form
					'dd_description'=> $post['dd_description'],
					'dd_features'=> $post['dd_features'],
					'dd_conditions'=> $post['dd_conditions'],
					'dd_includes'=> $post['dd_includes'],
					'dd_policy'=> $post['dd_policy'],
					//'dd_offer'=> $post['dd_offer'],
					'dd_discount'=> $offerdata['do_discount'],
					'dd_listprice'=> $offerdata['do_listprice'],
					'dd_originalprice'=> $offerdata['do_originalprice'],
					'dd_startdate'=> $dd_startdate,
					'dd_expiredate'=> $dd_expiredate,
					'dd_mainphoto'=> $post['dd_mainphoto'],
					'dd_modiftimestamp'=> date('Y-m-d H:i:s'),
					'dd_status'=> $post['dd_status'],
					'dd_validtilldate' => date('Y-m-d',strtotime($post['dd_validtilldate'])),
					'dd_address_flag' => isset($post['dd_address_flag'])?1:0
					);

				$ret_deal = $this->common_model->insertData(DEAL_DETAIL, $data);

				if ($ret_deal > 0) {
					/*ADd offer*/
					$offer_data= $post['offer_data'];
					foreach($offer_data as $offer)
					{
						$offerArr = json_decode($offer,1);
						unset($offerArr['do_autoid']);
						$offerArr['do_ddid'] = $ret_deal;
						$offerId = $this->common_model->insertData(DEAL_OFFER, $offerArr);
					}

					/*ADd Tags*/
					$post_tags = $post['dd_tags'];
					foreach ($post_tags as $tag)
					{
						$tag = trim($tag);
						$tagid = $this->common_model->selectData(DEAL_TAGS,"dt_autoid",array("dt_tag"=>$tag));
						if(!$tagid)
						{
							$tagdata =  array("dt_tag"=>$tag);
							$tagid = $this->common_model->insertData(DEAL_TAGS, $tagdata);
						}
						else
						{
							$tagid = ($tagid[0]->dt_autoid);
						}


						$tagmap = $this->common_model->selectData(DEAL_MAP_TAGS,"*",array("dm_ddid"=>$ret_deal,"dm_dtid"=>$tagid));
						if (!$tagmap)
						{
							$tagmapdata =  array("dm_ddid"=>$ret_deal,"dm_dtid"=>$tagid);
							$this->common_model->insertData(DEAL_MAP_TAGS, $tagmapdata);
						}
					}

					/*update deal id to uploaded image link*/
					$newimages = array_filter(explode(",",$post['newimages']));
					if (count($newimages) > 0)
						$this->common_model->assingImagesToDeal($ret_deal,$newimages);


					/*Deal Images sorting.*/
					if($post['sortOrder'] != "")
						$this->common_model->setImageOrder($post['sortOrder'],$ret_deal);

					$flash_arr = array('flash_type' => 'success',
										'flash_msg' => 'Deal added successfully.'
									);
				}else{
					$flash_arr = array('flash_type' => 'error',
										'flash_msg' => 'An error occurred while processing.'
									);
				}
				$this->session->set_flashdata($flash_arr);
				redirect("deal");
			}
			$data['error_msg'] = $error;
		}

		$data['dealers'] = $this->common_model->selectData(DEAL_DEALER, 'de_autoid,de_name,de_email');
		$data['categories'] = $this->common_model->selectData(DEAL_CATEGORY, 'dc_catid,dc_catname');
		$data['dd_images'] = array();
		$data['offers'] = array();

		$data['view'] = "add_edit";
		$this->load->view('content', $data);
	}

	public function edit($id)
	{
		if (!@in_array("edit", @config_item('user_role')[$this->user_session['role']]['deal']) && $this->user_session['role'] != 'a') {
			redirect("deal");
		}

		if ($id == "" || $id <= 0) {
			redirect('deal');
		}

		$where = 'dd_autoid = '.$id;

		$post = $this->input->post();
		if ($post) {
			$error = array();
			$e_flag=0;

			if ($post['dd_dealerid'] == "") {
				$error['dd_dealerid'] = 'Please select dealer.';
				$e_flag=1;
			}
			/*if ($post['dd_catid'] == "") {
				$error['dd_catid'] = 'Please select category.';
				$e_flag=1;
			}*/
			if ($post['dd_name'] == "") {
				$error['dd_name'] = 'Please enter deal name.';
				$e_flag=1;
			}
			$offer_data= $post['offer_data'];
			/*if (!isset($offer_data[0]))
			{
				$error['dd_offer'] = 'Please enter atleast one offer.';
				$e_flag=1;
			}
			else
			{*/
				$offerdata =  json_decode($offer_data[0],1);
			/*}

			if ($post['dd_status'] == "") {
				$error['dd_status'] = 'Please select status.';
				$e_flag=1;
			}
			if ($post['dd_includes'] == "") {
				$error['dd_includes'] = 'Please enter what deals includes.';
				$e_flag=1;
			}
			if ($post['dd_policy'] == "") {
				$error['dd_policy'] = 'Please enter deal policy.';
				$e_flag=1;
			}
			if ($post['dd_tags'] == "") {
				$error['dd_tags'] = 'Please enter deal tags.';
				$e_flag=1;
			}*/

			if ($e_flag == 0) {
				$timeperiod = explode("-",$post['dd_timeperiod']);
				$dd_startdate = date('Y-m-d H:i:s',strtotime($timeperiod[0]));
				$dd_expiredate = date('Y-m-d H:i:s',strtotime($timeperiod[1]));

				$data = array(
							'dd_dealerid'=> $post['dd_dealerid'],
							'dd_catid'=> $post['dd_catid'],
							'dd_name'=> $post['dd_name'],
							#'dd_createdby'=> $this->user_session['id'], // logged in user's id
							#'dd_createdate'=> $post['dd_createdate'], // need to add in add form
							'dd_description'=> $post['dd_description'],
							'dd_features'=> $post['dd_features'],
							'dd_conditions'=> $post['dd_conditions'],
							'dd_includes'=> $post['dd_includes'],
							'dd_policy'=> $post['dd_policy'],
							//'dd_offer'=> $post['dd_offer'],
							'dd_discount'=> $offerdata['do_discount'],
							'dd_listprice'=> $offerdata['do_listprice'],
							'dd_originalprice'=> $offerdata['do_originalprice'],
							'dd_startdate'=> $dd_startdate,
							'dd_expiredate'=> $dd_expiredate,
							'dd_mainphoto' => $post['dd_mainphoto'],
							'dd_modiftimestamp'=> date('Y-m-d H:i:s'),
							'dd_status'=> $post['dd_status'],
							'dd_validtilldate' => date('Y-m-d',strtotime($post['dd_validtilldate'])),
							'dd_address_flag' => isset($post['dd_address_flag'])?1:0
							);

				$ret = $this->common_model->updateData(DEAL_DETAIL, $data, $where);

				if ($ret > 0) {
					/*Add/Update offers */
					$offer_data= $post['offer_data'];
					foreach($offer_data as $offer)
					{
						$offerArr = json_decode($offer,1);
						if (isset($offerArr['do_autoid']) && $offerArr['do_autoid'] !="")
						{
							$where = 'do_autoid = '.$offerArr['do_autoid'];
							unset($offerArr['do_autoid']);
							$offerId = $this->common_model->updateData(DEAL_OFFER, $offerArr, $where);
						}
						else
						{
							$offerArr['do_ddid'] = $id;
							$offerId = $this->common_model->insertData(DEAL_OFFER, $offerArr);
						}
					}

					/*Add/Update tags */
					$post_tags = $post['dd_tags'];
					$old_tags = $this->common_model->getDealTags($id);

					foreach ($post_tags as $tag)
					{
						$tag = trim($tag);

						$found = false;
						foreach ($old_tags as $k=>$v)
						{
							if ($tag == $v['dt_tag'])
							{
								$found = true;
								unset($old_tags[$k]);
							}
						}

						if ($found) continue;

						$tagid = $this->common_model->selectData(DEAL_TAGS,"dt_autoid",array("dt_tag"=>$tag));
						if(!$tagid)
						{
							$tagdata =  array("dt_tag"=>$tag);
							$tagid = $this->common_model->insertData(DEAL_TAGS, $tagdata);
						}
						else
						{
							$tagid = ($tagid[0]->dt_autoid);
						}


						$tagmap = $this->common_model->selectData(DEAL_MAP_TAGS,"*",array("dm_ddid"=>$id,"dm_dtid"=>$tagid));
						if (!$tagmap)
						{
							$tagmapdata =  array("dm_ddid"=>$id,"dm_dtid"=>$tagid);
							$this->common_model->insertData(DEAL_MAP_TAGS, $tagmapdata);
						}
					}

					if (count($old_tags)>0)
					{
						$del_ids = array_reduce($old_tags,function($arr,$k){ $arr[] = $k['dt_autoid']; return $arr;});
						$this->common_model->deleteTags($del_ids,$id);
					}

					/*Deal Images sorting.*/
					if($post['sortOrder'] != "")
						$this->common_model->setImageOrder($post['sortOrder'],$id);

					$flash_arr = array('flash_type' => 'success',
										'flash_msg' => 'Deal updated successfully.'
									);
				}else{
					$flash_arr = array('flash_type' => 'error',
										'flash_msg' => 'An error occurred while processing.'
									);
				}
				$this->session->set_flashdata($flash_arr);
				redirect("deal");
			}
			$data['error_msg'] = $error;

		}

		$data['deal'] = $deal = $this->common_model->selectData(DEAL_DETAIL, '*', $where);

		if (empty($deal)) {
			redirect('deal');
		}

		$data['dealers'] = $this->common_model->selectData(DEAL_DEALER, 'de_autoid,de_name,de_email');
		$data['categories'] = $this->common_model->selectData(DEAL_CATEGORY, 'dc_catid,dc_catname');
		$data['dd_tags'] = $this->common_model->getDealTags($id);
		$data['dd_images'] = $this->common_model->selectData(DEAL_LINKS, 'dl_autoid,dl_url',array("dl_ddid"=>$id),"dl_order","ASC");
		$data['offers'] = $this->common_model->selectData(DEAL_OFFER, '*',array("do_ddid"=>$id));
		$data['view'] = "add_edit";
		$this->load->view('content', $data);
	}

	public function removeOffer()
	{
		$post = $this->input->post();

		if ($post) {
			$ret = $this->common_model->deleteData(DEAL_OFFER, array('do_autoid' => $post['id'] ));
			if ($ret > 0) {
				echo "success";
			}else{
				echo "error";
			}
		}
	}

	public function removeImage()
	{
		$post = $this->input->post();

		if ($post) {
			$link = $this->common_model->selectData(DEAL_LINKS,"dl_url",array('dl_autoid' => $post['id'] ));
			unlink("./uploads/".$link[0]->dl_url);
			$ret = $this->common_model->deleteData(DEAL_LINKS, array('dl_autoid' => $post['id'] ));
			if ($ret > 0) {
				echo "success";
			}else{
				echo "error";
			}
		}
	}

	public function fileupload()
	{
		$file_name = "";
		$error = "";
		$post = $this->input->post();
		if($_FILES['file']['name'] != '' && $_FILES['file']['error'] == 0){
			$config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'gif|jpg|png|bmp|jpeg';

			$file_name_arr = explode('.',$_FILES['file']['name']);
			$file_name_arr = array_reverse($file_name_arr);
			$file_extension = $file_name_arr[0];
			$file_name = $config['file_name'] = "deal_".time().".".$file_extension;

			$this->load->library('upload', $config);

			if ( ! $this->upload->do_upload('file'))
			{
				$e_flag = 1;
				$error = $this->upload->display_errors();
			}

			if ($error != "")
				echo "Error:".$error;
			else
			{
				$dd_ddid = isset($post['dd_id'])?$post['dd_id']:"";
				$linkdata =  array("dl_ddid"=>$dd_ddid,"dl_type"=>"img","dl_url"=>$file_name);
				$link_id = $this->common_model->insertData(DEAL_LINKS, $linkdata);
				echo '{"id":"'.$link_id.'","path":"'.base_url()."uploads/".$file_name.'"}';
			}
			exit;
		}else
		{
			echo "Error: File not uploaded to server.";
		}
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

	public function dealstatusupdate()
	{
		$post = $this->input->post();

		$where = array();
		$where['db_autoid'] = $post['id'];

		$data = array();
		$data['db_dealstatus'] = ($post['flag']=="1")?"active":"inactive";

		if ($this->user_session['role'] != 'a' && $data['db_dealstatus']=="active")
			exit;
		$ret = $this->common_model->updateData(DEAL_BUYOUT, $data, $where);
		echo $ret;exit;
	}

	public function offerstatusupdate()
	{
		$post = $this->input->post();

		$where = array();
		$where['do_autoid'] = $post['id'];

		$data = array();
		$data['do_status'] = ($post['flag']=="1")?"active":"inactive";

		$ret = $this->common_model->updateData(DEAL_OFFER, $data, $where);
		echo $ret;exit;
	}
}
