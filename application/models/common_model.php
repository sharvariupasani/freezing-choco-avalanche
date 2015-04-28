<?php
class common_model extends CI_Model{
	public function  __construct(){
		parent::__construct();
		$this->load->database();
	}


	/*
	| -------------------------------------------------------------------
	| Select data
	| -------------------------------------------------------------------
	|
	| general function to get result by passing nesessary parameters
	|
	*/
	public function selectData($table, $fields='*', $where='', $order_by="", $order_type="", $group_by="", $limit="", $rows="", $type='')
	{
		$this->db->select($fields);
		$this->db->from($table);
		if ($where != "") {
			$this->db->where($where);
		}

		if ($order_by != '') {
			$this->db->order_by($order_by,$order_type);
		}

		if ($group_by != '') {
			$this->db->group_by($group_by);
		}

		if ($limit > 0 && $rows == "") {
			$this->db->limit($limit);
		}
		if ($rows > 0) {
			$this->db->limit($rows, $limit);
		}


		$query = $this->db->get();

		if ($type == "rowcount") {
			$data = $query->num_rows();
		}else{
			$data = $query->result();
		}

		#echo "<pre>"; print_r($this->db->queries); exit;
		$query->free_result();

		return $data;
	}


	/*
	| -------------------------------------------------------------------
	| Insert data
	| -------------------------------------------------------------------
	|
	| general function to insert data in table
	|
	*/
	public function insertData($table, $data)
	{
		$result = $this->db->insert($table, $data);
		if($result == 1){
			return $this->db->insert_id();
		}else{
			return false;
		}
	}


	/*
	| -------------------------------------------------------------------
	| Update data
	| -------------------------------------------------------------------
	|
	| general function to update data
	|
	*/
	public function updateData($table, $data, $where)
	{
		$this->db->where($where);
		if($this->db->update($table, $data)){
			return 1;
		}else{
			return 0;
		}
	}

	/*
	| -------------------------------------------------------------------
	| Delere data
	| -------------------------------------------------------------------
	|
	| general function to delete the records
	|
	*/
	public function deleteData($table, $data)
	{
		if($this->db->delete($table, $data)){
			return 1;
		}else{
			return 0;
		}
	}



	/*
	| -------------------------------------------------------------------
	| check unique fields
	| -------------------------------------------------------------------
	|
	*/
	public function isUnique($table, $field, $value,$where = "")
	{
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where($field,$value);
		if ($where != "")
			$this->db->where($where);
		$query = $this->db->get();
		$data = $query->num_rows();
		$query->free_result();

		return ($data > 0)?FALSE:TRUE;
	}


	public function deleteTags($de_autoid,$tag_ids)
	{
		$this->db->where_in('dm_dtid', $de_autoid);
		$this->db->where(array('dm_ddid'=>$tag_ids));
		$del = $this->db->delete(DEAL_MAP_TAGS);
		if($del){
			$delqry = "DELETE FROM deal_tags WHERE dt_autoid IN (".implode(",",$de_autoid).") AND (SELECT IF (COUNT(*)=0,1,0) FROM deal_map_tags WHERE dm_dtid = dt_autoid)";
			$this->db->query($delqry);
			return 1;
		}else{
			return 0;
		}
	}

	public function getDealTags($dd_autoid)
	{
		$this->db->select("dt_autoid,dt_tag");
		$this->db->from(DEAL_TAGS);
		$this->db->join(DEAL_MAP_TAGS, "dm_dtid = dt_autoid");
		$this->db->where(array("dm_ddid"=>$dd_autoid));

		$query = $this->db->get();
		$tags = $query->result_array();
		$query->free_result();
		return ($tags);
	}

	public function assingImagesToDeal($deal_id,$image_ids)
	{
		$this->db->where_in('dl_autoid',$image_ids);
		$data = array("dl_ddid"=>$deal_id);
		if($this->db->update(DEAL_LINKS, $data)){
			return 1;
		}else{
			return 0;
		}
	}

	public function getTagAutoSuggest($tag)
	{
		$this->db->select('dt_tag');
		$this->db->from(DEAL_TAGS);
		$this->db->like('dt_tag', $tag, 'after');
		$query = $this->db->get();
		$tags = $query->result_array();
		$resTags = array();
		foreach($tags as $tag)
			$resTags[] = array($tag['dt_tag'],$tag['dt_tag']);
		return ($resTags);
	}

	public function getmyfav()
	{
		$session = $this->session->userdata('front_session');
		$user_id = $session['id'];

		$this->db->select("SQL_CALC_FOUND_ROWS dd_autoid", FALSE);
		$this->db->select('deal_detail.*,(select dl_url from deal_links where dl_autoid = dd_mainphoto and dl_type="img") as `dd_photourl`');
		$this->db->from(DEAL_DETAIL);
		$this->db->join(DEAL_FAV, 'db_dealid = dd_autoid', 'left');

		$this->db->where('dd_status',"published");
		$this->db->where("dd_startdate <= now()");
		$this->db->where("dd_expiredate >= now()");
		$this->db->where(array("df_userid"=>$user_id));

		$query = $this->db->get();
		$resDeals = $query->result_array();

		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$totalRecordsCount = $query->row()->Count;

		$deals = array();
		foreach ($resDeals as $deal)
		{
			$rec = array();
			$rec['id'] = $deal['dd_autoid'];
			$rec['name'] = $deal['dd_name'];
			$rec['description'] = $deal['dd_description'];
			$rec['dd_discount'] = $deal['dd_discount'];
			$rec['dd_originalprice'] = $deal['dd_originalprice'];
			$rec['dd_listprice'] = $deal['dd_listprice'];
			$rec['photo'] = base_url()."uploads/".$deal['dd_photourl'];
			$rec['url'] = base_url()."deals/detail/".$deal['dd_autoid']."/".replace_char($deal['dd_name']);
			$rec['is_fav'] = 1;
			$deals[] = $rec;
		}
		return (json_encode($deals));

	}

	public function searchDeals($tags,$catid="",$page = 1,$limit = 15,$or=false)
	{
		$tags = array_filter(explode(",",$tags));

		$this->db->select("SQL_CALC_FOUND_ROWS dd_autoid", FALSE);
		$this->db->select('deal_detail.*,(select dl_url from deal_links where dl_autoid = dd_mainphoto and dl_type="img") as `dd_photourl`');
		$this->db->from(DEAL_DETAIL);

		if (count ($tags) > 0)
		{
			$this->db->join(DEAL_MAP_TAGS, 'dd_autoid = dm_ddid', 'left');
			$this->db->join(DEAL_TAGS, 'dm_dtid = dt_autoid', 'left');
			$this->db->where_in('dt_tag',$tags);
			$this->db->group_by('dd_autoid');

			if(!$or)
				$this->db->having("COUNT(DISTINCT dm_dtid) = ".count($tags));
		}

		$this->db->where('dd_status',"published");
		//$this->db->where('dd_originalprice != 0');
		$this->db->where("dd_startdate <= now()");
		$this->db->where("dd_expiredate >= now()");

		if ($catid != "")
			$this->db->where('dd_catid',$catid);

		$start = ($page == 1)?0:$page*$limit;
		$this->db->limit($limit, $start);

		$query = $this->db->get();
		$resDeals = $query->result_array();

		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		$totalRecordsCount = $query->row()->Count;

		$deals = array();
		$deals['totalRecordsCount'] = $totalRecordsCount;

		$session = $this->session->userdata('front_session');
		$favdata = array();
		$favdata['df_userid'] = $session['id'];
		$favArray = array();
		if (isset($session['id']) && $session['id'] !="")
		{
			$favData = $this->selectData(DEAL_FAV,"db_dealid", $favdata);
			foreach ($favData as $fav)
				$favArray[] = $fav->db_dealid;
		}

		foreach ($resDeals as $deal)
		{
			$rec = array();
			$rec['id'] = $deal['dd_autoid'];
			$rec['name'] = $deal['dd_name'];
			$rec['description'] = $deal['dd_description'];
			$rec['dd_discount'] = $deal['dd_discount'];
			$rec['dd_originalprice'] = $deal['dd_originalprice'];
			$rec['dd_listprice'] = $deal['dd_listprice'];
			$rec['photo'] = base_url()."uploads/".$deal['dd_photourl'];
			$rec['url'] = base_url()."deals/detail/".$deal['dd_autoid']."/".replace_char($deal['dd_name']);
			$rec['is_fav'] = in_array($deal['dd_autoid'],$favArray);
			$deals[] = $rec;
		}
		return (json_encode($deals));
	}

	public function getDealDetail($id,$offerid="")
	{
			$data = array();
			$this->db->select("*");
			$this->db->from(DEAL_DETAIL);
			$this->db->join(DEAL_DEALER, 'de_autoid = dd_dealerid', 'left');
			$this->db->where('dd_status',"published");
			$this->db->where("dd_startdate <= now()");
			$this->db->where("dd_expiredate >= now()");
			$this->db->where('dd_autoid',$id);
			$query = $this->db->get();
			$data['detail'] = $query->result_array();

			if (count($data['detail']) <= 0) {
				redirect("welcome");
			}

			$this->db->select("*");
			$this->db->from(DEAL_LINKS);
			$this->db->where('dl_ddid',$id);
			$this->db->order_by("dl_order","ASC");
			$query = $this->db->get();
			$data['links'] = $query->result_array();

			$this->db->select("COUNT(*) as `count`");
			$this->db->from(DEAL_BUYOUT);
			$this->db->where('db_dealid',$id);
			$query = $this->db->get();
			$res = $query->result_array();
			$data['buycount'] = $res[0]['count'];

			$this->db->select("*");
			$this->db->from(DEAL_TAGS);
			$this->db->join(DEAL_MAP_TAGS, 'dm_dtid = dt_autoid', 'left');
			$this->db->where('dm_ddid',$id);
			$query = $this->db->get();
			$data['tags'] = $query->result_array();

			$session = $this->session->userdata('front_session');
			$data['is_fav'] = 0;
			if (isset($session['id']) && $session['id'] !="")
			{
				$favdata = array();
				$favdata['df_userid'] = $session['id'];
				$favdata['db_dealid'] = $id;
				$data['is_fav'] = $this->selectData(DEAL_FAV,"*", $favdata,"","","","","","rowcount");
			}
			if ($offerid != "")
			{
				$offer = $this->common_model->selectData(DEAL_OFFER, '*',array("do_ddid"=>$id,"do_autoid"=>$offerid));
				$data['offers'] = $offer[0];
			}
			else
				$data['offers'] = $this->common_model->selectData(DEAL_OFFER, '*',array("do_ddid"=>$id));

			$catdata =array();
			$catdata["dc_catid"] = $data['detail'][0]['dd_catid'];
			$category = $this->selectData(DEAL_CATEGORY, '*',$catdata);
			$data['category'] = $category[0];
			return $data;
	}

	public function getmydeals()
	{
		$session = $this->session->userdata('front_session');
		$user_id = $session['id'];

		$this->db->select("*");
		$this->db->from(DEAL_BUYOUT);
		$this->db->join(DEAL_DETAIL, 'db_dealid = dd_autoid', 'left');
		$this->db->join(DEAL_OFFER, 'do_autoid = db_offerid', 'left');
		$this->db->join(DEAL_DEALER, 'de_autoid = dd_dealerid', 'left');
		$this->db->where("db_uid",$user_id);
		$this->db->order_by("db_date","desc");
		$query = $this->db->get();

		$myoffers = $query->result_array();
		return $myoffers;
	}

	function setImageOrder($imglist,$dealid)
	{
		$imglist = json_decode($imglist,1);
		foreach($imglist as $imgdata)
		{
			$where = array();
			$where['dl_autoid'] = $imgdata['dl_autoid'];
			$where['dl_ddid'] = $dealid;

			$data = array();
			$data['dl_order'] = $imgdata['dl_order'];
			$this->common_model->updateData(DEAL_LINKS, $data, $where);
		}
	}



	public function getDealDetailPrint($dealbuyout_id)
	{
		$this->db->select("*");
		$this->db->from(DEAL_BUYOUT);
		$this->db->join(DEAL_DETAIL, 'db_dealid = dd_autoid', 'left');
		$this->db->join(DEAL_OFFER, 'db_offerid = do_autoid', 'left');
		$this->db->join(DEAL_DEALER, 'dd_dealerid = de_autoid', 'left');
		$this->db->join(DEAL_LINKS, 'db_dealid = dl_ddid', 'left');
		$this->db->where('db_autoid',$dealbuyout_id);
		$this->db->limit(1);
		$query = $this->db->get();

		$data = $query->result();
		$query->free_result();

		return $data;
	}

}
?>
