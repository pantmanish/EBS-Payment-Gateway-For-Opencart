<?php 
/**
@Developed by Manish Pant (www.manishpant.com)
**/ 
class ControllerPaymentEBS extends Controller {
	private $error = array(); 
	 
	public function index() { 
		$this->load->language('payment/EBS');


	$this->document->setTitle($this->language->get('heading_title'));
	$this->load->model('setting/setting');
	
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting('EBS', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_edit'] = $this->language->get('text_edit');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
	
		$data['entry_status'] = $this->language->get('entry_status');
		$data['account_id'] = $this->language->get('account_id');
		$data['secret_key'] = $this->language->get('secret_key');
		$data['mode'] = $this->language->get('mode');

// new code added for zone
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
//end
$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		if (isset($this->request->post['EBS_account_id'])) {
			$data['EBS_account_id'] = $this->request->post['EBS_account_id'];
		} else {
			$data['EBS_account_id'] = $this->config->get('EBS_account_id');
		}
		if (isset($this->request->post['EBS_secret_key'])) {
			$data['EBS_secret_key'] = $this->request->post['EBS_secret_key'];
		} else {
			$data['EBS_secret_key'] = $this->config->get('EBS_secret_key');
		}
		if (isset($this->request->post['EBS_test'])) {
			$data['EBS_test'] = $this->request->post['EBS_test'];
		} else {
			$data['EBS_test'] = $this->config->get('EBS_test');
		}
			if (isset($this->request->post['EBS_status'])) {
			$data['EBS_status'] = $this->request->post['EBS_status'];
		} else {
			$data['EBS_status'] = $this->config->get('EBS_status');
		}

// newly added code for zone status for guest checkout
		if (isset($this->request->post['EBS_geo_zone_id'])) {
			$data['EBS_geo_zone_id'] = $this->request->post['EBS_geo_zone_id'];
		} else {
			$data['EBS_geo_zone_id'] = $this->config->get('EBS_geo_zone_id'); 
		} 

		$this->load->model('localisation/geo_zone');
										
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
//end





		
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');

	//	$data['error_warning'] = @$this->error['warning'];
if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

 	
		
  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER .'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => 'https://secure.ebs.in/pg/ma/sale/pay',
       		'text'      => $this->language->get('text_payment'),
      		'separator' => ' :: '
   		);
		
   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER .'index.php?route=payment/EBS&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payment/EBS', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('payment/EBS', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token']);
	
		
		
	
				
		//$this->id       = 'content';
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
 
			$this->response->setOutput($this->load->view('payment/EBS.tpl', $data));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/EBS')){
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['EBS_account_id']) {
			$this->error['EBS_account_id'] = $this->language->get('error_EBS_account_id');
		}

		if (!$this->request->post['EBS_secret_key']) {
			$this->error['EBS_secret_key'] = $this->language->get('error_EBS_secret_key');
		}
		
		
		 
			return !$this->error;
		 
	}
}
?>
