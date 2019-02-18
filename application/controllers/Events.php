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
                //Load the page
                $this->load->view('templates/header');
                $this->load->view('events/view', $data);
                $this->load->view('templates/footer');
            } else {
                //The user is NOT a part of the events department
                redirect('events');
            }
        }

        public function assignments($viewadd, $e_id){
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }
            $event = $this->event_model->get_event($e_id);
            //Check if the user is in the same department as the event
            foreach($this->session->userdata('departments') as $department){
                if($department['d_id'] == $event['d_id']){
                    $ismember = TRUE;
                    break;
                }
            }

            if($ismember){
                $data['e_id'] = $e_id;
                //Check whether to load the view page or the add page
                if($viewadd == "view") {
                    //Load view page
                    $data['title'] = 'Opgave oversigt - '.$event['e_name'];
                    $data['asses'] = $this->event_assignment_model->get_ass($e_id);
                    
                    $this->load->view('templates/header');
                    $this->load->view('events/assignments_view', $data);
                    $this->load->view('templates/footer');
                } elseif($viewadd == "add"){
                    //Load add page
                    $data['title'] = "Tilføj opgave - ".$event['e_name'];
                    $data['asses'] = $this->assignment_model->get_ass_index();
                    
                    $this->load->view('templates/header');
                    $this->load->view('events/assignments_add', $data);
                    $this->load->view('templates/footer');
                
                } else {
                    //The value of $addview is hardcoded, therefore you will only end up with a 404 if you misspelled something
                    $this->load->view('templates/header');
                    show_404();
                    $this->load->view('templates/footer');
                }
            } else {
                redirect('events');
            }
        }

        public function remove_ass($e_id, $ass_id){
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }
            $event = $this->event_model->get_event($e_id);
            //Check if the user is in the same department as the event
            foreach($this->session->userdata('departments') as $department){
                if($department['d_id'] == $event['d_id']){
                    $ismember = TRUE;
                    break;
                }
            }

            if($ismember){
                $this->event_assignment_model->remove_ass($e_id, $ass_id);
                $this->session->set_flashdata('event_added_ass','Opgave tilføjet til eventet');
                redirect('events/view/'.$e_id);
            } else {
                redirect('events');
            }
        }

        public function add_ass($e_id, $ass_id){
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }
            $event = $this->event_model->get_event($e_id);
            //Check if the user is in the same department as the event
            foreach($this->session->userdata('departments') as $department){
                if($department['d_id'] == $event['d_id']){
                    $ismember = TRUE;
                    break;
                }
            }

            if($ismember){
                $this->event_assignment_model->add_ass($e_id, $ass_id);
                $this->session->set_flashdata('event_added_ass','Opgave tilføjet til eventet');
                redirect('events/view/'.$e_id);
            } else {
                redirect('events');
            }
        }

        public function delete($e_id){
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }
            $data['event'] = $this->event_model->get_event($e_id);
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
                $this->team_model->delete_team($e_id);
                $this->session->set_flashdata('event_deleted','Event slettet');
            }
            redirect('events');
        }
    }