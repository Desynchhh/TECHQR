<?php
    class Events extends CI_Controller{
        public function index(){
            if(!$this->session->userdata('logged_in')){
            redirect('login');
        }
        
            $data['title'] = "Event oversigt";
            $data['events'] = $this->event_model->get_event();

            $this->load->view('templates/header');
            $this->load->view('events/index', $data);
            $this->load->view('templates/footer');
        }

        public function create(){
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }

            $data['title'] = "Opret event";

            $this->form_validation->set_rules('event_name','"eventnavn"','required');
            if($this->form_validation->run() === FALSE){
                $this->load->view('templates/header');
                $this->load->view('events/create', $data);
                $this->load->view('templates/footer');
            } else {
                //Create event in the DB
                $this->event_model->create_event();
                $this->session->set_flashdata('event_created','Event oprettet');
                redirect('events');
            }
        }

        public function view($id){
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }

            $data['title'] = "Event detaljer";
            $data['event'] = $this->event_model->get_event($id);
            //Check if the user is in the same department as the event
            foreach($this->session->userdata('departments') as $department){
                if($department['d_id'] == $data['event']['d_id']){
                    $ismember = TRUE;
                    break;
                }
            }
            if($ismember){
                //The user is a part of the events department
                //Get the events teams
                $data['teams'] = $this->team_model->get_teams($id);
                //Get the id's for each member in each team
                foreach($data['teams'] as $team){
                    $s_id_array[] = $this->team_model->get_team_members($team['t_id']);
                }
                //Store the id's in the $data array
                $data['s_id'] = $s_id_array;

                //Load the page
                $this->load->view('templates/header');
                $this->load->view('events/view', $data);
                $this->load->view('templates/footer');
            } else {
                //The user is NOT a part of the events department
                redirect('events');
            }
        }

        public function delete($e_id){
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }
            $data['event'] = $this->event_model->get_event($id);
            //Check if the user is in the same department as the event
            foreach($this->session->userdata('departments') as $department){
                if($department['d_id'] == $data['event']['d_id']){
                    $ismember = TRUE;
                    break;
                }
            }
            
            if($ismember){
                //Delete the event
                $this->event_model->delete_event($e_id);
                $this->session->set_flashdata('event_deleted','Event slettet');
            }
            redirect('events');
        }
    }