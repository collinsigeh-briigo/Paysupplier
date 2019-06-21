<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Suppliers controller responsible for:
 * 
 * - displaying suppliers
 * - handling supplier related actions
 * 
 * @author      Collins Igeh
 * 
 */

class Suppliers extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('custom_library');
    }

    /**
    * Display suppliers
    */
    public function index()
    {
        $this->custom_library->check_login();

        $data = array(
            'suppliers' => $this->get_suppliers()
        );

        $this->load->view('templates/header');
        $this->load->view('pages/suppliers', $data);
    }

    /**
    * Display create_supplier page
    */
    public function create()
    {
        $this->custom_library->check_login();

        $data = array(
            'banks' => $this->get_banks()
        );

        $this->load->view('templates/header');
        $this->load->view('pages/create_supplier', $data);
    }

    /**
    * handles submitted supplier details
    */
    public function save()
    {
        $this->custom_library->check_login();

        if(!$_POST){
            redirect(base_url().'dashboard/');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Supplier name', 'required');
        $this->form_validation->set_rules('bank_code', 'Supplier bank', 'required');
        $this->form_validation->set_rules('account_number', 'Supplier account number', 'required|numeric|exact_length[10]');

        if ($this->form_validation->run() == FALSE)
        {
                $_SESSION['errors'] = validation_errors();
                redirect(base_url().'suppliers/create/');
        }

        $post_data = array(
            'type' => 'nuban',
            'name' => $_POST['name'],
            'description' => 'Supplier',
            'account_number' => $_POST['account_number'],
            'bank_code' => $_POST['bank_code'],
            'currency' => 'NGN'
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.paystack.co/transferrecipient');
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


}