<?php 
/**
@Developed by Manish Pant (www.manishpant.com)
**/
class ControllerCommonResponse extends Controller { 
public function index() { 

$this->load->language('common/EBS');
$data['button_confirm'] = $this->language->get('button_confirm');
$data['button_continue'] = $this->language->get('button_continue');
$data['heading_title'] = $this->language->get('heading_title');
$data['continue'] = HTTP_SERVER . 'index.php?route=common/home';

$secret_key = $this->config->get('EBS_secret_key'); // Your Secret Key
if(isset($_GET['DR'])) {
	 require('Rc43.php');
	 $DR = preg_replace("/\s/","+",$_GET['DR']);

	 $rc4 = new Crypt_RC4($secret_key);
 	 $QueryString = base64_decode($DR);
	 $rc4->decrypt($QueryString);
	 $QueryString = explode('&',$QueryString);

	 $response = array();
	 foreach($QueryString as $param){
	 	$param = explode('=',$param);
		$response[$param[0]] = urldecode($param[1]);
	 }
	 $data['response']=$response;

   
$this->load->model('checkout/order');
	if($response['ResponseCode']=='0')
{

//$this->model_checkout_order->confirm($response['MerchantRefNo'], $this->config->get('cod_order_status_id'));
	
		if (isset($this->session->data['order_id'])) {
			$this->cart->clear();
			
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['guest']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);	
			unset($this->session->data['coupon']);
		}


//$order_info = $this->model_checkout_order->getOrder($response['MerchantRefNo']);

//print_r($order_info);
		/*
		echo "<pre>";
		print_r($response);die;  */
		// $this->model_checkout_order->confirm($response['MerchantRefNo'],'5');
		// $this->model_checkout_order->addOrder($response['MerchantRefNo'],'5');
		$this->model_checkout_order->addOrderHistory($response['MerchantRefNo'],'5');

	 $data['responseMsg']='Your order has been successfully processed!<br/>Payment Successful<br/> Your Order id - '.$response['MerchantRefNo'];
}
else
{

		
	$this->model_checkout_order->addOrderHistory($response['MerchantRefNo'],'10');
	 $data['responseMsg']='Sorry, Try Again !! <br/>Payment Failed';
}
		$this->document->setTitle($this->language->get('success_heading_title'));
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['footer'] = $this->load->controller('common/footer');
		
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['success_heading_title'] = $this->language->get('success_heading_title');
		$data['continue'] = $this->url->link('common/home');
		$data['button_continue'] = $this->language->get('button_continue');
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_basket'),
			'href' => $this->url->link('checkout/cart')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_checkout'),
			'href' => $this->url->link('checkout/checkout', '', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_success'),
			'href' => $this->url->link('checkout/success')
		);
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/response.tpl')) {
			// $this->template = $this->config->get('config_template') . '/template/common/response.tpl';
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/response.tpl', $data));
		} else {
			// $this->template = 'default/template/common/response.tpl';
			$this->response->setOutput($this->load->view('default/template/common/response.tpl', $data));
		}
		
	
			/* $this->children = array(
			'common/header',
			'common/footer',
			'common/column_left',
			'common/column_right'
		); */
		// $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	 }
	 
	 }
}
?>
