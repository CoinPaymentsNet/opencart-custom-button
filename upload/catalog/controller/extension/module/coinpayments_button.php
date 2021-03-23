<?php
class ControllerExtensionModuleCoinpaymentsButton extends Controller {
    public function index() {
        $this->load->language('extension/module/coinpayments_button');
        $status = true;

        if (!$this->cart->hasProducts() || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $status = false;
        }
        $data['total_price'] = number_format($this->cart->getTotal(), 2, '', '');

        $data['items'] = array();
        foreach ($this->cart->getProducts() as $product) {
            array_push($data['items'], array("name"=>$product['name'], "quantity"=>array("value"=>$product['quantity'], "type"=>"1"), "amount"=>strval(number_format($product['total'], 2, '', ''))));
        }
        $data['taxes'] = json_encode(array("subtotal"=>strval( number_format($this->cart->getSubTotal(), 2, '', '')),"taxTotal"=>strval(number_format(array_sum($this->cart->getTaxes()), 2, '', ''))));
        $data['items'] = json_encode($data['items']);
        if ($status) {
            return $this->load->view('extension/module/coinpayments_button', $data);
        }
    }
}