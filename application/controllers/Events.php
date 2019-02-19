<?php
    class Events extends CI_Controller{
        public function index(){
            if(!$this->session->userdata('logged_in')){
            redirect('login');
            }
        
            $data['title'] = "Event oversigt";
            $events = $this->event_model->get_event();

            //Find all events that have the same department as the logged in user
            //Create array to temporarily store events in
            $events_to_keep = array();
            //Check through all the users departments and all events, and compare them
            foreach($this->session->userdata('departments') as $user_deps){
                foreach($events as $event){
                    if($user_deps['d_id'] == $event['d_id']){
                        //Add matching event to the array
                        $events_to_keep[] = $event;
                    }
                }
            }
            //Add all matching events to the $data array
            $data['events'] = $events_to_keep;

            //Load page
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
            $data['event_asses'] = $this->event_assignment_model->get_event_ass($id);
            $data['max_points'] = $this->calc_max_points($data['event_asses']);
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

        public function assignments_add($e_id){
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
                //Run if the user is a member of the events department
                $data['e_id'] = $e_id;
                $data['title'] = "Tilføj opgave - ".$event['e_name'];
                $asses = $this->assignment_model->get_ass_index();
                $event_asses = $this->event_assignment_model->get_event_ass($e_id);
                
                //Only show the user assignments that have NOT been added to the event, as you shouldn't add the same assignment twice
                //Create array to temporarily store assignments in
                $asses_to_keep = array();
                //Run through all assignments and compare them to the events assignments
                foreach($asses as $ass){
                    $already_contains = false;
                    foreach($event_asses as $event_ass){
                        if($ass['id'] == $event_ass['ass_id']){
                            //Found a match
                            $already_contains = true;
                            break;
                        }
                    }
                    if(!$already_contains){
                        //Add assignment to the temp array if it was not found in the events assignments
                        $asses_to_keep[] = $ass;
                    }
                }
                //Add all unfound assignments to the $data array
                $data['asses'] = $asses_to_keep;

                //Load page
                $this->load->view('templates/header');
                $this->load->view('events/assignments_add', $data);
                $this->load->view('templates/footer');
            } else {
                //If the user is NOT a member of the events department, redirect them to the event index
                redirect('events');
            }
        }

        public function assignments_view($e_id){
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
                $data['title'] = 'Opgave oversigt - '.$event['e_name'];
                $data['asses'] = $this->event_assignment_model->get_ass($e_id);
                
                $this->load->view('templates/header');
                $this->load->view('events/assignments_view', $data);
                $this->load->view('templates/footer');
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
                $this->session->set_flashdata('event_removed_ass','Opgave fjernet fra eventet');
                redirect('events/assignments/view/'.$e_id);
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
                redirect('events/assignments/add/'.$e_id);
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

        //Helper function
        private function calc_max_points($event_asses){
            $max_points = 0;
            $ass_points = array();
            $ass_max_points;
            
            foreach($event_asses as $event_ass){
                //For each assignment in the event.. get the assignments answers
                $answers = $this->assignment_model->get_ass_answers($event_ass['ass_id']);
                foreach($answers as $answer){
                    //Get the points for each individual answer, and store them in an array
                    $ass_points[] = $answer['points'];
                }
                //Set the max point for this assignment as the first answer (default, only to avoid possible errors)
                $ass_max_points = $ass_points[0];
                foreach($ass_points as $ass_point){
                    //Compare each answers points to the currently highest answer
                    if($ass_max_points < $ass_point){
                        //Replace the max points with a new max
                        $ass_max_points = $ass_point;
                    }
                }
                if($ass_max_points > 0){
                    //Only add to the max number of points IF the points are positive
                    //Negative points != higher max
                    $max_points += $ass_max_points;
                }
                //Reset array
                $ass_points = array();
            }
            return $max_points;
        }
    }