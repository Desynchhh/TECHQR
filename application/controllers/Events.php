<?php
    class Events extends CI_Controller{
        public function index(){
            if(!$this->session->userdata('logged_in')){
            redirect('login');
            }
        
            $data['title'] = "Event oversigt";
            $events = $this->event_model->get_event();

            if($this->session->userdata('permissions') != 'Admin'){

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
            } else {
                $data['events'] = $events;
            }

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

        public function edit($e_id){
            //Check the user is logged in
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }
            
            $data['title'] = "Rediger event";
            $data['event'] = $this->event_model->get_event($e_id);
            $data['e_id'] = $e_id;
            //Check the user is part of the events department
            foreach($this->session->userdata('departments') as $department){
                if($department['d_id'] == $data['event']['d_id']){
                    $ismember = TRUE;
                    break;
                }
            }

            if($ismember || $this->session->userdata('permissions') == 'Admin'){
                //Set rules for form input fields
                $this->form_validation->set_rules('e_name','"event navn"','required');

                if($this->form_validation->run() === FALSE){
                    //Load the page if the validation failed OR didn't run
                    $this->load->view('templates/header');
                    $this->load->view('events/edit', $data);
                    $this->load->view('templates/footer');
                } else {
                    //Update/rename the event if validation is successful
                    $this->event_model->edit_event($e_id);
                    $this->session->set_flashdata('event_edited','Eventet er blevet omdøbt');
                    redirect('events');
                }
            }
        }

        public function view($id){
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }

            $data['event'] = $this->event_model->get_event($id);
            //Check if the user is in the same department as the event
            $ismember = false;
            foreach($this->session->userdata('departments') as $department){
                if($department['d_id'] == $data['event']['d_id']){
                    $ismember = TRUE;
                    break;
                }
            }

            if($ismember || $this->session->userdata('permissions') == 'Admin'){
                //The user is a part of the events department
                $data['title'] = "Event detaljer";
                //Calculate the maximum score of the event
                $data['event_asses'] = $this->event_assignment_model->get_ass($id);//get_event_ass($id);
                $data['max_points'] = $this->calc_max_points($data['event_asses']);
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

            if($ismember || $this->permissions->userdata('permissions') == 'Admin'){
                //Run if the user is a member of the events department
                $data['e_id'] = $e_id;
                $data['title'] = "Tilføj opgave - ".$event['e_name'];
                $asses = $this->assignment_model->get_ass_index();
                $event_asses = $this->event_assignment_model->get_ass($e_id);//get_event_ass($e_id);
                
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

            if($ismember || $this->permissions->userdata('permissions') == 'Admin'){
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

            if($ismember || $this->permissions->userdata('permissions') == 'Admin'){
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

            if($ismember || $this->permissions->userdata('permissions') == 'Admin'){
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
            
            if($ismember || $this->permissions->userdata('permissions') == 'Admin'){
                //Delete the event
                $this->event_model->delete_event($e_id);
                $this->team_model->delete_team($e_id);
                $this->session->set_flashdata('event_deleted','Event slettet');
            }
            redirect('events');
        }

        public function pdf($e_id){
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
            
            if($ismember || $this->permissions->userdata('permissions') == 'Admin'){
                $data['title'] = $data['event']['e_name'].' PDF';
                $this->load->view('templates/header');
                $this->load->view('events/pdf', $data);
                $this->load->view('templates/footer');            
            }
        }

        //Helper function
        private function calc_max_points($event_asses){
            $max_points = 0;
            $ass_points = array();
            $ass_max_points;
            
            foreach($event_asses as $event_ass){
                //For each assignment in the event.. get the answers to the assignment
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

        //Create team pdf - WIP
        public function create_team_pdf($e_id){
            require_once 'vendor\autoload.php';

            $event = $this->event_model->get_event($e_id);
            $eventfolder = url_title($event['e_name']);
            $teams = $this->team_model->get_teams($e_id);

            //Check if folders for the event exists. Create them if they don't.
            $path = APPPATH.'../assets/gen-files/'.$eventfolder;
            if(!is_dir($path)){
                mkdir($path, 0777, true);
                mkdir($path.'/team-qr', 0777, true);
                mkdir($path.'/ass-qr', 0777, true);
            }
            //Double check the team-qr folder exists. Maybe it was deleted, but the 'root' event folder was not.
            if(!is_dir($path.'/team-qr')){
                mkdir($path.'/team-qr', 0777, true);
            }

            //Create a QR Code for each team in the event, with a correct URL that calls the correct Controller when scanned.
            foreach($teams as $team){
                //Set the URL to the 'join()' function in the 'Events' Controller. 
                //event_id and team_id are given as parameters.
                $url = base_url('events/join/'.$e_id.'/'.$team['number']);
                //Instantiate a new Endroid\QrCode object. Takes the URL the Code takes you to as a parameter
                $qrcode = new Endroid\QrCode\QrCode($url);
                //Qr Code settings
                $qrcode->setMargin(10);
                $qrcode->setEncoding('UTF-8');
                $qrcode->setLabel('Tilføj min mobil til hold '.$team['number']);

                //Set the path to subfolders in the 'assets' folder corresponding to the team in the event
                $path = APPPATH.'../assets/gen-files/'.$eventfolder.'/team-qr/team-number-'.$team['number'].'.png';
                //Save the QR Code
                $qrcode->writeFile($path);
            }
            redirect('events/pdf/'.$event['e_id']);
        }

        //Create assignment pdf - WIP
        public function create_ass_pdf($e_id){
            require_once 'vendor\autoload.php';

            $event = $this->event_model->get_event($e_id);
            $eventfolder = url_title($event['e_name']);
            $path = APPPATH.'../assets/gen-files/'.$eventfolder;
            if(!is_dir($path)){
                mkdir($path, 0777, true);
                mkdir($path.'/team-qr', 0777, true);
                mkdir($path.'/ass-qr', 0777, true);
            }
            if(!is_dir($path.'/ass-qr')){
                mkdir($path.'/ass-qr', 0777, true);
            }

            $asses = $this->event_assignment_model->get_ass($e_id);
            
            foreach($asses as $ass){
                //Create a folder with the name of the assignment
                $assfolder = url_title($ass['title']);
                $path = APPPATH.'../assets/gen-files/'.$eventfolder.'/ass-qr/'.$assfolder;
                if(!is_dir($path)){
                    mkdir($path, 0777, true);
                }
                
                $ass_url = base_url('events/answer/'.$e_id.'/'.$ass['ass_id'].'/');
                //Get all answers for the current assignment
                $ass_answers = $this->assignment_model->get_ass_answers($ass['ass_id']);
                //Store each answer for the current assignment in another array
                $answers_array = array();
                foreach($ass_answers as $answer){
                    $answers_array[] = $answer;
                }
                //Create and save a QR code for each answer
                foreach($answers_array as $answer){
                    //URL the QR leads to
                    $url = $ass_url . url_title($answer['id']);
                    $qrcode = new Endroid\QrCode\QrCode($url);
                    $qrcode->setMargin(10);
                    $qrcode->setEncoding('UTF-8');
                    $qrcode->setLabel($answer['answer']);
                    
                    $qrpath = APPPATH.'../assets/gen-files/'.$eventfolder.'/ass-qr/'.$assfolder.'/'.url_title($answer['answer']).'.png';
                    $qrcode->writeFile($qrpath);
                }
            }
            redirect('events/pdf/'.$event['e_id']);
        }
    }