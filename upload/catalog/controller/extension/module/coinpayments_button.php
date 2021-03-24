<?php
class ControllerExtensionModuleCoinpaymentsButton extends Controller {
    public function index() {
        $this->load->language('extension/module/coinpayments_button');
        $status = true;

        if (!$this->cart->hasProducts() || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $status = false;
        }
        $data['total_price'] = number_format($this->cart->getTotal(), 2, '', '');

        $this->coinpayments = new Coinpayments($this->registry);
        $coin_currency = $this->coinpayments->getCoinCurrency($this->config->get('config_currency'));
        $data['currency_id'] = $coin_currency['id'];

        $data['items'] = array();
        foreach ($this->cart->getProducts() as $product) {
            array_push($data['items'], array("name"=>$product['name'], "quantity"=>array("value"=>$product['quantity'], "type"=>"1"), "amount"=>strval(number_format($product['total'], $coin_currency['decimalPlaces'], '', ''))));
        }
        $data['taxes'] = json_encode(array("subtotal"=>strval( number_format($this->cart->getSubTotal(), $coin_currency['decimalPlaces'], '', '')),"taxTotal"=>strval(number_format(array_sum($this->cart->getTaxes()), $coin_currency['decimalPlaces'], '', ''))));
        $data['items'] = json_encode($data['items']);
        $data['client_id'] = json_encode($this->config->get('payment_coinpayments_client_id'));
        if ($status) {
            return $this->load->view('extension/module/coinpayments_button', $data);
        }
    }
}