<?php
    class Teams extends CI_Controller{
        public function create($e_id){
            $this->team_model->create_team($e_id);
            $this->session->set_flashdata('team_created','Hold oprettet');
            redirect('events');
        }
    }