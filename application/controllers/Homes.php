<?php
    class Homes extends CI_Controller{
        public function index(){
            $data['title'] = "Hjem";

            $this->load->view('templates/header');
            $this->load->view('homes/index', $data);
            $this->load->view('templates/footer');
        }
    }