<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dashboard controller responsible for:
 * 
 * - displaying the dashboard and login-page 
 * - handling basic tasks like sign-in, getting accoutn balance and logout
 * 
 * @author      Collins Igeh
 * 
 */

class Dashboard extends CI_Controller {
        
        public function __construct()
        {
                parent::__construct();
                $this->load->library('custom_library');
        }

        /**
         * Display dashboard
         */
        public function index()
        {
                $this->custom_library->check_login();

                $balance_in_kobo = $this->account_balance();

                $naira_balance = ($balance_in_kobo / 100);

                $data = array(
                        'balance' => $naira_balance
                );

                $this->load->view('templates/header');
                $this->load->view('pages/home', $data);
        }

        /**
         * Display login page
         */
        public function login()
        {

                $this->load->view('templates/header');
                $this->load->view('pages/login');
                $this->load->view('templates/footer');
        }

        /**
         * Handle user sign-in
         */
        public function sign_in()
        {
                if(!$_POST){
                        redirect(base_url().'dashboard/login/');
                }

                $this->load->library('form_validation');

                $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
                $this->form_validation->set_rules('password', 'Password', 'required');

                if ($this->form_validation->run() == FALSE)
                {
                        $_SESSION['errors'] = validation_errors();
                        redirect(base_url().'dashboard/login/');
                }

                if(isset($_POST['email']) && strtolower($_POST['email']) == 'collinsigeh@gmail.com' && isset($_POST['password']) && $_POST['password'] == 'pay!'){
                        
                        $_SESSION['user_logged_in'] = 'Yes';
                        redirect(base_url().'dashboard/');
                }else{
                        
                        $_SESSION['errors'] = 'Invalid login details';
                        redirect(base_url().'dashboard/login/');
                }
        }

        /**
         * Handle user logout
         */
        public function logout()
        {
                session_destroy();
                redirect(base_url().'dashboard/login/');
        }

        /**
         * Display success page
         */
        public function success()
        {

                $this->load->view('templates/header');
                $this->load->view('pages/success');
        }

        /**
         * Display failure page
         */
        public function failure()
        {

                $this->load->view('templates/header');
                $this->load->view('pages/failure');
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