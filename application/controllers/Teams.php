<?php
    class Teams extends CI_Controller{
        public function create($e_id){
            $data['title'] = 'Opret hold';
            $data['e_id'] = $e_id;
            $data['teams'] = $this->team_model->get_teams($e_id);

            $this->form_validation->set_rules('teams','"antal hold"','required|numeric');

            if($this->form_validation->run() === FALSE){
                $this->load->view('templates/header');
                $this->load->view('events/teams', $data);
                $this->load->view('templates/footer');
            } else {
                for($i = 0; $i < $this->input->post('teams'); $i++){
                    $this->team_model->create_team($e_id);
                }
                $this->session->set_flashdata('team_created','Hold oprettet');
                redirect('events');
            }
        }
    }