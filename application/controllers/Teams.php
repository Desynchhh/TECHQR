<?php
    class Teams extends CI_Controller{
        public function create($e_id){
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }

            //$data['title'] = 'Opret hold';
            //$data['e_id'] = $e_id;
            //$data['event'] = $this->event_model->get_event($e_id);
            //$data['teams'] = $this->team_model->get_teams($e_id);

            $this->form_validation->set_rules('teams','"antal hold"','numeric');

            if($this->form_validation->run() === FALSE){
                $this->load->view('templates/header');
                $this->load->view('events/view', $data);
                $this->load->view('templates/footer');
            } else {
                $teams = $this->team_model->get_teams($e_id);
                if(empty($teams)){
                    //Event has no teams. Create the first team
                    $t_number = 1;
                } else {
                    //Event has teams. Increase the team count from the newest team
                    $t_number = count($teams)+1;
                }
                for($i = 0; $i < $this->input->post('teams'); $i++){
                    $this->team_model->create_team($e_id, $t_number);
                    $t_number++;
                }
                $this->session->set_flashdata('team_created','Hold oprettet');
                redirect('teams/view/'.$e_id);
            }
        }

        public function view($e_id){
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            
            }
            
            $data['teams'] = $this->team_model->get_teams($e_id);
            $data['e_id'] = $e_id;
            $event = $this->event_model->get_event($e_id);
            $data['title'] = "Hold oversigt - ".$event['e_name'];
            
            //Get the id's for each member in each team
            $s_id_array = array();
            foreach($data['teams'] as $team){
                $s_id_array[] = $this->team_model->get_team_members($team['t_id']);
            }
            //Store the id's in the $data array
            $data['students'] = $s_id_array;

            $this->load->view('templates/header');
            $this->load->view('teams/view', $data);
            $this->load->view('templates/footer');
        }

        public function delete($e_id, $t_id = NULL){
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }

            $this->team_model->delete_team($e_id, $t_id);
            if($t_id){
                $this->session->set_flashdata('teams_deleted', 'Hold slettet');
            } else {
                $this->session->set_flashdata('teams_deleted', 'Alle hold slettet');
            }
            redirect('teams/view/'.$e_id);
        }
    }