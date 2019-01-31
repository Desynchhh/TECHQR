<?php
    class Events extends CI_Controller{
        public function index(){
            $data['title'] = "Event oversigt";

            $this->load->view('templates/header');
            $this->load->view('events/index', $data);
            $this->load->view('templates/footer');
        }
    }