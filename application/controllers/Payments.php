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
        $this->form_validation->set_rules('amount', 'Amount', 'required|greater_than_equal_to[500]');

        if ($this->form_validation->run() == FALSE)
        {
                $_SESSION['errors'] = validation_errors();
                redirect(base_url().'payments/make_payment/');
        }

        $display_amount = $_POST['amount'];
        $amount = $display_amount * 100;//amount to kobo
        
        $balance = $this->account_balance();
        if(($amount + 50000) > $balance){
            $_SESSION['errors'] = '<p>Insufficient balance.</p><p>For your transfer to be successful, you must have at least NGN 500.00 more than the amount you wish to transfer.</p>';
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
            'display_amount' => $display_amount,
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
        $this->form_validation->set_rules('amount', 'amount', 'required|greater_than_equal_to[50000]');
        $this->form_validation->set_rules('recipient', 'Recipient code', 'required');

        if ($this->form_validation->run() == FALSE)
        {
                $_SESSION['errors'] = validation_errors();
                redirect(base_url().'payments/make_payment/');
        }

        $amount = $_POST['amount'];
        
        $balance = $this->account_balance();
        if(($amount + 50000) > $balance){
            $_SESSION['errors'] = '<p>Insufficient balance.</p><p>For your transfer to be successful, you must have at least NGN 500.00 more than the amount you wish to transfer.</p>';
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

        $data = array(
            'suppliers' => $this->get_suppliers()
        );

        $this->load->view('templates/header');
        $this->load->view('pages/bulk_transfer', $data);
    }

    /**
    * Display confirm_bulk_transfer page
    */
    public function confirm_bulk_transfer()
    {
        $this->custom_library->check_login();

        if(!$_POST){
            redirect(base_url().'dashboard/');
        }

        $transfers = [];
        $total_amount = 0;
        $transfer_sum = 0;

        $suppliers = $this->get_suppliers();
        foreach($suppliers as $supplier){
            $amount = 0;
            $ref = $supplier['recipient_code'];

            if(isset($_POST[$ref]) && ($_POST[$ref] >= 500)){

                $naira_amount = $_POST[$ref];
                $amount = $naira_amount * 100;

                $transfers[$ref] = array(
                    'amount' => $amount,
                    'naira_amount' => $naira_amount,
                    'recipient_code' => $ref,
                    'supplier_name' => $supplier['name']
                );
            }
            $total_amount = $total_amount + $amount;
        }

        $total_transfers = count($transfers);

        if($total_transfers < 1){
            $_SESSION['errors'] = 'Please enter an amount for at least ONE supplier.';
            redirect(base_url().'payments/bulk_transfer/');
        }

        $transfer_cost = $total_transfers * 5000;
        $transfer_sum = $total_amount + $transfer_cost;

        $account_balance = $this->account_balance();

        if($transfer_sum > ($account_balance + 50000)){
            $_SESSION['errors'] = '<p>Insufficient balance.</p><p>For your transfer to be successful, you must have at least NGN 500.00 more than the amount you wish to transfer plus charges.</p>';
            redirect(base_url().'payments/bulk_transfer/');
        }
        
        $data = array(
            'transfers' => $transfers,
            'total_transfers' => $total_transfers,
            'total_sum' => $transfer_sum
        );

        $this->load->view('templates/header');
        $this->load->view('pages/confirm_bulk_transfer', $data);
    }

    /**
    * Receives and handles bulk disbursement request to Paystack API
    */
    public function send_bulk_transfer()
    {
        $this->custom_library->check_login();

        if(!$_POST){
            redirect(base_url().'dashboard/');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('confirmaton', 'Confirmation', 'required');

        if ($this->form_validation->run() == FALSE)
        {
                $_SESSION['errors'] = validation_errors();
                redirect(base_url().'payments/bulk_transfer/');
        }

        $transfers = [];

        $suppliers = $this->get_suppliers();
        foreach($suppliers as $supplier){
            $amount = 0;
            $ref = $supplier['recipient_code'];
            if(isset($_POST[$ref]) && ($_POST[$ref] > 50000)){
                $amount = $_POST[$ref];
                array_push($transfers, array(
                    'amount' => $amount,
                    'recipient' => $ref
                ));
            }
        }

        $post_data = array(
            'currency' => 'NGN',
            'source' => 'balance',
            'transfers' => $transfers
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.paystack.co/transfer');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
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