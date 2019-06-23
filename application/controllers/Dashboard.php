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

                $balance_in_kobo = $this->custom_library->account_balance();

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

}