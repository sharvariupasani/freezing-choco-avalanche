<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->user_session = $this->session->userdata('user_session');
	}


	public function index()
	{
		$post = $this->input->post();
		if ($post) {
			$this->form_validation->set_rules('firmname', 'Firm name', 'trim|required');
            $this->form_validation->set_rules('firmvat', 'Vat', 'trim|required');
            $this->form_validation->set_rules('firmtax', 'Tax', 'trim|required');

            if ($this->form_validation->run()) {
				$post['takein_remark'] = json_encode($post['takein_remark']);

				foreach($post as $option_name=>$option_field)
				{
					$where = array();
					$where  = array('option_name' => $option_name);

					$data = array();
					$data['option_field'] = $option_field;
					$data['option_name'] = $option_name;
						
					$opt = $this->common_model->getCount(SETTING, $where );
					if ($opt > 0)
					{
						$ret = $this->common_model->updateData(SETTING, $data, $where );
					}
					else
					{
						$ret = $this->common_model->insertData(SETTING, $data, $where );
					}
				}

                if ($ret > 0) {
                    $data['flash_msg'] = success_msg_box('Setting updated successfully.');
                }else{
                    $data['flash_msg'] = error_msg_box('An error occurred while processing.');
                }

            }
		}

		$settings = $this->common_model->selectData(SETTING, '*');
		$setting_data = array();
		foreach($settings as $setting)
		{
			$setting_data[$setting->option_name] = $setting->option_field;
		}
		$this->session->set_userdata('setting',$setting_data);
		$setting_data['takein_remark'] = json_decode($setting_data['takein_remark'],true);
		$setting_data = (Object) $setting_data;
		$data['setting_data'] = $setting_data;
		

		$data['view'] = "index";
		$this->load->view('content', $data);
	}
}

/* End of file Setting.php */
/* Location: ./application/controllers/Setting.php */
