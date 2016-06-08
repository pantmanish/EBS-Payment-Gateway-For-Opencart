<?php 
/**
@Developed by Manish Pant (www.manishpant.com)
**/
class ControllerPaymentEBS extends Controller {
	public function index() {
    	$data['button_confirm'] = $this->language->get('button_confirm');
		$data['button_back'] = $this->language->get('button_back');	
		$data['button_continue'] = $this->language->get('button_continue');		

		//$payment_address_id = $this->session->data['payment_address_id'];	
		//$payment_address = $this->model_account_address->getAddress($payment_address_id);
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
    		
		$data['action'] = 'https://secure.ebs.in/pg/ma/sale/pay/';
		$data['account_id'] = $this->config->get('EBS_account_id');
		$data['secret_key'] = $this->config->get('EBS_secret_key');
		$data['reference_no']= $this->session->data['order_id']; 
		//$data['amount']=$this->currency->format($order_info['total'], $order_info['currency'], $order_info['value'], FALSE);
		$data['amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
		$data['description']	=   $this->session->data['payment_method']['title'];
		
		$data['name']     = $order_info['payment_firstname'].' '.$order_info['payment_lastname'];
		$data['address'] 	= $order_info['payment_address_1'].",".$order_info['payment_address_2'];
		$data['city']    	= $order_info['payment_city'];
		$data['state'] 	= $order_info['payment_zone'];
		$data['postal_code']= $order_info['payment_postcode'];
		$data['country']   = $order_info['payment_country_id']; //Iso 3 char code is supported
		#$data['email']  	= $this->customer->getEmail();
		#$data['phone'] 	= $this->customer->getTelephone();

		$data['email']  	= $order_info['email'];
		$data['phone']    = $order_info['telephone'];

		if(isset($this->session->data['shipping_address_id'])){
			$shipping_address_id = $this->session->data['shipping_address_id'];	
			$shipping_address = $this->model_account_address->getAddress($shipping_address_id);
			$data['ship_name']	= $shipping_address['firstname'].' '.$shipping_address['lastname'];
			$data['ship_address']	= $shipping_address['address_1'].",".$shipping_address['address_2'];
			$data['ship_city']  	= $shipping_address['city'];
			$data['ship_state']  	= $shipping_address['zone'];
			$data['ship_postal_code'] = $shipping_address['postcode'];
			$data['ship_country']   	= $shipping_address['country_id'];//Iso 3 char code is supported
			$data['ship_phone']   	= $order_info['telephone'];
		}
		else{

			$data['ship_name']	= $order_info['payment_firstname'].' '.$order_info['payment_lastname'];
			$data['ship_address']	= $order_info['payment_address_1'].",".$order_info['payment_address_2'];
			$data['ship_city']  	= $order_info['payment_city'];
			$data['ship_state']  	= $order_info['payment_zone'];
			$data['ship_postal_code'] = $order_info['payment_postcode'];
			$data['ship_country']   	= $order_info['payment_country_id'];//Iso 3 char code is supported
			$data['ship_phone']   	= $order_info['telephone'];
		}
 
		$data['return_url']      	= HTTPS_SERVER . 'index.php?route=common/response&DR={DR}';
		//$this->url->http('common/res').'&amp;DR={DR}';
		if($this->config->get('EBS_test') == "on")
			$data['mode']    			= 'TEST';
		else
			$data['mode']    			= 'LIVE';	
		
		
		$this->id = 'payment';
	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/EBS.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/EBS.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/EBS.tpl', $data);
		}
		
		$this->render();
		
		
		
		
	}
	

}
?>

