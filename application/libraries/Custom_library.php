<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Custom_liberay is designed to do the following:
 * 
 * 01 - check user login session
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
    
}