<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Payments controller responsible for:
 * 
 * - displaying payouts
 * - handling payments/transfer related interactions
 * 
 * @author      Collins Igeh
 * 
 */

class Payments extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('custom_library');
    }

    /**
    * Display payouts
    */
    public function index()
    {
        $this->custom_library->check_login();

        $data = array(
            'payouts' => $this->get_payouts()
        );

        $this->load->view('templates/header');
        $this->load->view('pages/payouts', $data);
    }

    /**
    * Display make_payment page
    */
    public function make_payment()
    {
        $this->custom_library->check_login();

        $data = array(
            'suppliers' => $this->get_suppliers()
        );

        $this->load->view('templates/header');
        $this->load->view('pages/make_payment', $data);
    }

    /**
    * Display confirm_payment page
    */
    public function confirm_payment()
    {
        $this->custom_library->check_login();

        if(!$_POST){
            redirect(base_url().'dashboard/');
        }

        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('recipient', 'Select supplier', 'required');
        $this->form_validation->set_rules('amount', 'Amount', 'required|greater_than[100000]');

        if ($this->form_validation->run() == FALSE)
        {
                $_SESSION['errors'] = validation_errors();
                redirect(base_url().'payments/make_payment/');
        }

        $amount = $_POST['amount'];
        
        $balance = $this->account_balance();
        if($amount >= ($balance - 50000)){
            $_SESSION['errors'] = '<p>Your request is denied because your transfer amount is too large for your account balance.</p><p>For your transfer to be successful, you must have at least NGN 500.00 more than the amount you wish to transfer.</p>';
            redirect(base_url().'payments/make_payment/');
        }

        $recipient = $_POST['recipient'];
        $supplier_name = '';

        $suppliers = $this->get_suppliers();
        foreach($suppliers as $supplier){
            
            if($supplier['recipient_code'] == $recipient){
                $supplier_name = $supplier['name'];
            }
        }
        

        if(isset($_POST['reason']) && strlen($_POST['reason']) > 2){
            $reason = $_POST['reason'];
        }else{
            $reason = 'General supply';
        }

        $data = array(
            'supplier_name' => $supplier_name,
            'recipient' => $recipient,
            'amount' => $amount,
            'reason' => $reason
        );

        $this->load->view('templates/header');
        $this->load->view('pages/confirm_payment', $data);
    }

    /**
    * Handles submitting of payments to request to paystack
    */
    public function send_payment()
    {
        $this->custom_library->check_login();

        if(!$_POST){
            redirect(base_url().'dashboard/');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('confirmaton', 'Confirmation', 'required');
        $this->form_validation->set_rules('reason', 'Reason for payment', 'required');
        $this->form_validation->set_rules('amount', 'amount', 'required|greater_than[100000]');
        $this->form_validation->set_rules('recipient', 'Recipient code', 'required');

        if ($this->form_validation->run() == FALSE)
        {
                $_SESSION['errors'] = validation_errors();
                redirect(base_url().'payments/make_payment/');
        }

        $amount = $_POST['amount'];
        
        $balance = $this->account_balance();
        if($amount >= ($balance - 50000)){
            $_SESSION['errors'] = '<p>Your request is denied because your transfer amount is too large for your account balance.</p><p>For your transfer to be successful, you must have at least NGN 500.00 more than the amount you wish to transfer.</p>';
            redirect(base_url().'payments/make_payment/');
        }

        $post_data = array(
            'source' => 'balance',
            'reason' => $_POST['reason'],
            'amount' => $amount,
            'recipient' => $_POST['recipient']
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.paystack.co/transfer');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Authorization: Bearer sk_test_964385f725f9e13b9a6579dd8b4dddf460aaf036"));
 
        $output = curl_exec($ch);

        curl_close($ch);

        if($output === FALSE){
            $_SESSION['errors'] = 'An unexpected error occured.';
            redirect(base_url().'dashboard/');
        }

        $arr = json_decode($output, TRUE);
        if($arr['status'] == 'true'){
            $_SESSION['success'] = $arr['message'];
            redirect(base_url().'dashboard/success/');
        }else{
            $_SESSION['errors'] = $arr['message'];
            redirect(base_url().'dashboard/failure/');
        }
    }

    /**
    * Get and return payouts from paystack api
    */
    public function get_payouts()
    {
        $this->custom_library->check_login();

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.paystack.co/transfer');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Authorization: Bearer sk_test_964385f725f9e13b9a6579dd8b4dddf460aaf036")); 
 
        $output = curl_exec($ch);

        curl_close($ch);

        if($output === FALSE){

                $_SESSION['errors'] = 'An unexpected error occured.';
                redirect(base_url().'dashboard/');
        }

        $arr = json_decode($output, TRUE);

        return $arr['data'];
    }
    
    /**
    * Display bulk_transfer page
    */
    public function bulk_transfer()
    {
        $this->custom_library->check_login();

        $this->load->view('templates/header');
        $this->load->view('pages/bulk_transfer');
    }

    /**
    * Display confirm_bulk_transfer page
    */
    public function confirm_bulk_transfer()
    {
        $this->custom_library->check_login();
        
        $data = array(
            'amount' => '40000',
            'reason' => 'Bread Flower'
        );

        $this->load->view('templates/header');
        $this->load->view('pages/confirm_bulk_transfer', $data);
    }

    /**
         * Get and return bank details
         */
        public function get_banks()
        {
                $this->custom_library->check_login();

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, 'https://api.paystack.co/bank');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Authorization: Bearer sk_test_964385f725f9e13b9a6579dd8b4dddf460aaf036")); 
 
                $output = curl_exec($ch);

                curl_close($ch);

                if($output === FALSE){

                        $_SESSION['errors'] = 'An unexpected error occured.';
                        redirect(base_url().'dashboard/');
                }

                $arr = json_decode($output, TRUE);

                return $arr['data'];
        }

        /**
        * Get and return registered recipients from paystack
        */
        public function get_suppliers()
        {
            $this->custom_library->check_login();

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://api.paystack.co/transferrecipient');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Authorization: Bearer sk_test_964385f725f9e13b9a6579dd8b4dddf460aaf036")); 
    
            $output = curl_exec($ch);

            curl_close($ch);

            if($output === FALSE){

                    $_SESSION['errors'] = 'An unexpected error occured.';
                    redirect(base_url().'dashboard/');
            }

            $arr = json_decode($output, TRUE);

            return $arr['data'];
        }

        /**
         * Checks for and returns account balance in kobo
         */
        public function account_balance()
        {
                $this->custom_library->check_login();

                $balance = 0;

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, 'https://api.paystack.co/balance');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Authorization: Bearer sk_test_964385f725f9e13b9a6579dd8b4dddf460aaf036")); 
 
                $output = curl_exec($ch);

                curl_close($ch);

                if($output === FALSE){

                        $_SESSION['errors'] = 'An unexpected error occured.';
                        redirect(base_url().'dashboard/');
                }

                $arr = json_decode($output, TRUE);

                $result = $this->extract_array_from_json($arr);

                $result_string = implode("<br>", $result["values"]);
                $result_array = explode("<br>", $result_string);
                $balance = $result_array[3];


                return $balance;
        }

        /**
         * Recursive function to extract nested values
         */
        public function extract_array_from_json($arr) {
                global $count;
                global $values;
                
                // Check input is an array
                if(!is_array($arr)){
                die("ERROR: Input is not an array");
                }
                
                /*
                Loop through array, if value is itself an array recursively call the
                function else add the value found to the output items array,
                and increment counter by 1 for each value found
                */
                foreach($arr as $key=>$value){
                if(is_array($value)){
                        $this->extract_array_from_json($value);
                } else{
                        $values[] = $value;
                        $count++;
                }
                }
                
                // Return total count and values found in array
                return array('total' => $count, 'values' => $values);
        }

    
}