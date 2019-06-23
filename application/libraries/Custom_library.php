<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Custom_liberay is designed to do the following:
 * 
 * 01 - check user login session
 * 02 - check and returns account balance
 * 03 - gets and returns suppliers
 * 
 * @author      Collins Igeh
 * 
 */

class Custom_library {

    protected $CI;
    
    // We'll use a constructor, as you can't directly call a function
    // from a property definition.
    public function __construct()
    {
            // Assign the CodeIgniter super-object
            $this->CI =& get_instance();
    }
    
    public function check_login()
    {
            $this->CI->load->helper('url');    
            $this->CI->load->library('session');
            $this->CI->config->item('base_url');
            if(!isset($_SESSION['user_logged_in']) OR $_SESSION['user_logged_in'] != 'Yes'){
                redirect(base_url().'dashboard/login/');
            }
    }

    /**
         * Checks for and returns account balance in kobo
         */
        public function account_balance()
        {
                $this->check_login();

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

        /**
        * Get and return registered recipients from paystack
        */
        public function get_suppliers()
        {
            $this->check_login();

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
    * Get and return payouts from paystack api
    */
    public function get_payouts()
    {
        $this->check_login();

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
    * Get and return bank details
    */
    public function get_banks()
    {
        $this->check_login();

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
}