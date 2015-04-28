<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends CI_Controller {

	function __construct(){
		parent::__construct();

		is_login();

		$this->user_session = $this->session->userdata('user_session');
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<label for="inputError" class="control-label"><i class="fa fa-times-circle-o"></i>','</label><br/>');
	}


	public function edit()
	{
		$post = $this->input->post();
		if ($post) {
			$this->form_validation->set_rules('du_uname', 'Name', 'trim|required');
            $this->form_validation->set_rules('du_contact', 'Contact', 'trim|required');
            $this->form_validation->set_rules('du_email', 'Email', 'trim|required|valid_email|callback_email_check');



            if ($this->form_validation->run()) {

                $data = array('du_uname' => $post['du_uname'],
                                'du_email' => $post['du_email'],
                                'du_contact' => $post['du_contact']
                            );
                $ret = $this->common_model->updateData(DEAL_USER, $data, array('du_autoid' => $this->user_session['id']) );

                if ($ret > 0) {
                    $data['flash_msg'] = success_msg_box('Profile updated successfully.');
                }else{
                    $data['flash_msg'] = error_msg_box('An error occurred while processing.');
                }

            }
		}


		$data['profile_data'] = $this->common_model->selectData(DEAL_USER, '*', array('du_autoid' => $this->user_session['id']));


		$data['view'] = "edit";
		$this->load->view('content', $data);
	}


	public function email_check($email)
	{

		$unique_email = $this->common_model->isUnique(DEAL_USER, 'du_email', $email, "du_autoid != '".$this->user_session['id']."'");
		if ($unique_email) {
			return TRUE;
		}else{
			$this->form_validation->set_message('email_check', 'The %s field already exists.');
			return FALSE;
		}

	}

	public function change_password()
	{
		$post = $this->input->post();
		if ($post) {

			$error = array();
			$e_flag=0;
			if(trim($post['password']) == ''){
				$error['password'] = 'Please enter new password.';
				$e_flag=1;
			}
			if(trim($post['re_password']) == ''){
				$error['re_password'] = 'Please enter repeat password.';
				$e_flag=1;
			}
			if(trim($post['password']) != trim($post['re_password'])){
				$flash_arr = array('flash_type' => 'error',
									'flash_msg' => 'Both paswords should be same.'
								);
				$e_flag=1;
			}

			if ($e_flag == 0) {
				# update password
				$data = array('du_password' => sha1(trim($post['password'])) );
				$ret = $this->common_model->updateData(DEAL_USER, $data, 'du_autoid = '.$this->front_session['id']);

				if ($ret > 0) {
					$flash_arr = array('flash_type' => 'success',
										'flash_msg' => 'Password updated successfully.'
									);
					#$this->session->set_flashdata($flash_arr);
				}else{
					$flash_arr = array('flash_type' => 'error',
										'flash_msg' => 'An error occurred while processing.'
									);
					#$this->session->set_flashdata($flash_arr);
				}
			}

			$data['error_msg'] = $error;
			$data['flash_msg'] = @$flash_arr;
		}
		$data['view'] = "password";
		$this->load->view('content', $data);
	}


}

/* End of file profile.php */
/* Location: ./application/controllers/profile.php */
