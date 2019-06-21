<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pages controller responsible for display generic pages
 * 
 * @author      Collins Igeh
 * 
 */

class Pages extends CI_Controller {

        /**
         * Display login
         */
        public function index()
        {

                $this->load->view('templates/header');
                $this->load->view('pages/login');
                $this->load->view('templates/footer');
        }

        /**
         * Display  author contact-page
         */
        public function contact()
        {

                $this->load->view('templates/header');
                $this->load->view('pages/contact');
                $this->load->view('templates/footer');
        }

}