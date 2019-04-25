<?php
    class Events extends CI_Controller{
        public function index($offset = 0, $order_by = 'ASC', $sort_by = 'e_name'){
            //Check user is logged in
            if(!$this->session->userdata('logged_in')){
            redirect('login');
            }

            //Prep
            $isAdmin = ($this->session->userdata('permissions') == 'Admin') ? TRUE : FALSE;
			$user_depts = $this->session->userdata('departments');
            //Count all rows in all the users department
            $total_rows = 0;
            foreach($user_depts as $department){
                $total_rows += $this->db->where('events.department_id', $department['d_id'])->count_all_results('events');
            }

            //Pagination config
            $config['base_url'] = base_url('events/index/');
            $config['total_rows'] = $total_rows;
            $config['per_page'] = 10;
            $config['uri_segment'] = 3;
            $config['attributes'] = array('class' => 'pagination-link');
            $config['first_link'] = 'Første';
            $config['last_link'] = 'Sidste';
            $this->pagination->initialize($config);

            //Data variables
            $data['title'] = "Event oversigt";
            $data['events'] = $this->event_model->get_event(NULL, $user_depts, $isAdmin, $config['per_page'], $offset, $sort_by, $order_by);
            $data['offset'] = $offset;
            $data['order_by'] = ($order_by == 'DESC') ? 'ASC' : 'DESC';
            
            //Load page
            $this->load->view('templates/header');
            $this->load->view('events/index', $data);
            $this->load->view('templates/footer');
        }


        public function create(){
            //Check logged in
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }

            $data['title'] = "Opret event";

            //Set validation rules
            $this->form_validation->set_rules('event_name','"eventnavn"','required');
            
            if($this->form_validation->run() === FALSE){
                //Validation fail
                $this->load->view('templates/header');
                $this->load->view('events/create', $data);
                $this->load->view('templates/footer');
            } else {
                //Validation success
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
            
            //Check the user is part of the events department
            $data['event'] = $this->event_model->get_event($e_id);
            $ismember = FALSE;
            foreach($this->session->userdata('departments') as $department){
                if($department['d_id'] == $data['event']['d_id']){
                    $ismember = TRUE;
                    break;
                }
            }
            
            if($ismember || $this->session->userdata('permissions') == 'Admin'){
                $input = $this->input->post('input');
                if (strlen(trim($input)) == 0){
                    //Inputted name is empty or only contains whitespace
                    $this->session->set_flashdata('event_edited_fail','Eventets navn kan ikke være tomt!');
                } else {
                    //Inputted name is valid
                    $this->event_model->edit_event($e_id, $input);
                    $this->session->set_flashdata('event_edited_success','Eventet er blevet omdøbt');
                }
                redirect('events/view/'.$e_id);
                /*
                //Set rules for form input fields
                $this->form_validation->set_rules('e_name','"event navn"','required');

                if($this->form_validation->run() === FALSE){
                    //Load the page if the validation failed OR didn't run
                    $this->load->view('templates/header');
                    $this->load->view('events/edit', $data);
                    $this->load->view('templates/footer');
                } else {
                    //Update/rename the event if validation is successful
                    redirect('events');
                }
                */
            }
        }


        //Loads page with data on the specified event
        public function view($e_id){
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }

            $data['event'] = $this->event_model->get_event($e_id);
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
                $data['event_asses'] = $this->event_assignment_model->get_ass($e_id);//get_event_ass($e_id);
                $data['max_points'] = $this->calc_max_points($data['event_asses']);
                //Get the events teams
                $data['teams'] = $this->team_model->get_teams($e_id);
                
                //Load the page
                $this->load->view('templates/header');
                $this->load->view('events/view', $data);
                $this->load->view('templates/footer');
            } else {
                //The user is NOT a part of the events department
                redirect('events');
            }
        }


        public function stats($e_id, $offset = 0){
            //Pagination config
            $config['base_url'] = base_url().'events/stats/'.$e_id;
            $config['total_rows'] = $this->db->where('event_id', $e_id)->count_all_results('event_assignments');
            $config['per_page'] = 3;
            $config['uri_segment'] = 4;
            $config['attributes'] = array('class' => 'pagination-link');
            $config['first_link'] = 'Første';
            $config['last_link'] = 'Sidste';
            $this->pagination->initialize($config);

            //Get all assignments in the event
            $event_ass = $this->event_assignment_model->get_ass($e_id, $config['per_page'], $offset);
            //Get all answers made by the students
            $team_ans = $this->team_model->get_team_answers($e_id);
            //Get all answers to the events assignments
            $event_ans = array();
            foreach($event_ass as $ass){
                $event_ans[] = $this->assignment_model->get_ass_answers($ass['ass_id']);
            }
            
            //Set data variables
            $event = $this->event_model->get_event($e_id);
            $data['title'] = 'Stats - '.$event['e_name'];
            $data['e_id'] = $e_id;
            $data['team_ans'] = $team_ans;
            $data['event_ass'] = $event_ass;
            $data['event_ans'] = $event_ans;

            //Load the page
            $this->load->view('templates/header');
            $this->load->view('events/stats', $data);
            $this->load->view('templates/footer');
        }


        //Load manage options and data
        public function manage($e_id, $empty_teams = NULL){
            //Check if any students cookie has expired
            $this->team_model->check_expire_date();

            //Set data variables
            $event = $this->event_model->get_event($e_id);
            $data['title'] = 'Manage - '.$event['e_name'];
            $data['e_id'] = $e_id;
            $data['teams'] = $this->team_model->get_teams($e_id);
            $data['empty_teams'] = $this->check_teams($e_id, $data['teams']);
            $data['message'] = $this->event_model->get_message($e_id);

            //Load page
            $this->load->view('templates/header');
            $this->load->view('events/manage', $data);
            $this->load->view('templates/footer');
        }


        //Subtract or add points to a team from the backend
        public function manage_points($e_id){
            //Set validation rules
            $this->form_validation->set_rules('points', '"point"', 'required|numeric');
            $this->form_validation->set_rules('t_num', '"hold nummer"', 'required|numeric');

            if($this->form_validation->run() === FALSE){
                //Validation didn't run or failed
                $this->session->set_flashdata('manage_points_fail', 'Point feltet må kun indeholde tal!');
            } else {
                //Get relevant team
                $t_num = $this->input->post('t_num');
                $team = $this->team_model->get_teams($e_id, $t_num);
                //Calculate the teams new score
                $t_score = $team['t_score'];
                $points = $this->input->post('points');
                $newscore = $t_score + $points;
                //Update score in DB
                $this->team_model->update_score($team['t_id'], $newscore);
                $this->session->set_flashdata('manage_points_success', 'Holdets point er blevet opdateret');
            }
            //Reload page
            redirect('events/manage/'.$e_id);
        }


        //Check if there are any teams without members. Return the empty teams.
        public function check_teams($e_id, $teams){
            //Update DB; Remove all students past the expiredate
            $this->team_model->check_expire_date();
            //Find all empty/unmanned teams
            $empty_teams = array();
            foreach($teams as $team){
                if($this->db->where('students.team_id', $team['t_id'])->count_all_results('students') <= 0){
                    $empty_teams[] = $team['t_num'];
                }
            }
            return $empty_teams;
        }


        //Updates the message field in the DB
        public function message($e_id){
            $msg = $this->input->post('message');
            $this->event_model->update_message($e_id, $msg);
            redirect('events/manage/'.$e_id);
        }


        //Loads the page where assignments can be added to the event, so teams can answer it.
        public function assignments_add($e_id, $offset = 0, $order_by = 'DESC', $sort_by = 'title'){
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }
            $event = $this->event_model->get_event($e_id);
            //Check if the user is in the same department as the event
            $ismember = FALSE;
            foreach($this->session->userdata('departments') as $department){
                if($department['d_id'] == $event['d_id']){
                    $ismember = TRUE;
                    $d_id = $department['d_id'];
                    break;
                }
            }

            //Run if the user is a member of the events department or is admin
            if($ismember || $this->session->userdata('permissions') == 'Admin'){
                //Pagination config
                $config['base_url'] = base_url('events/assignments/add/'.$e_id.'/');
                $config['total_rows'] = $this->db->where('assignments.department_id', $event['d_id'])->count_all_results('assignments');
                $config['per_page'] = 10;
                $config['uri_segment'] = 5;
                $config['attributes'] = array('class' => 'pagination-link');
                $config['first_link'] = 'Første';
                $config['last_link'] = 'Sidste';
                $this->pagination->initialize($config);
                
                //Set data variables
                $data['e_id'] = $e_id;
                $data['title'] = "Tilføj opgave - ".$event['e_name'];
                $data['asses'] = $this->event_assignment_model->get_ass_not_event($e_id, $event['d_id'], $config['per_page'], $offset, $sort_by, $order_by);
                $data['offset'] = $offset;
                $data['order_by'] = ($order_by == 'ASC') ? 'DESC' : 'ASC';

                //Load page
                $this->load->view('templates/header');
                $this->load->view('events/assignments_add', $data);
                $this->load->view('templates/footer');
            } else {
                //If the user is NOT a member of the events department, redirect them to the event index
                redirect('events');
            }
        }


        //Views all the assignments added to the event
        public function assignments_view($e_id, $offset = 0, $order_by = 'DESC', $sort_by = 'title'){
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }
            $event = $this->event_model->get_event($e_id);
            //Check if the user is in the same department as the event
            $ismember = FALSE; //Throws an error if this variable is undefined
            foreach($this->session->userdata('departments') as $department){
                if($department['d_id'] == $event['d_id']){
                    $ismember = TRUE;
                    break;
                }
            }

            if($ismember || $this->session->userdata('permissions') == 'Admin'){
                //Pagination config
                $config['base_url'] = base_url('events/assignments/view/'.$e_id.'/');
                $config['total_rows'] = $this->db->where('event_assignments.event_id', $e_id)->count_all_results('event_assignments');
                $config['per_page'] = 10;
                $config['uri_segment'] = 5;
                $config['attributes'] = array('class' => 'pagination-link');
                $config['first_link'] = 'Første';
                $config['last_link'] = 'Sidste';
                $this->pagination->initialize($config);

                //Data variables
                $data['e_id'] = $e_id;
                $data['title'] = 'Opgave oversigt - '.$event['e_name'];
                $data['asses'] = $this->event_assignment_model->get_ass($e_id, $config['per_page'], $offset, $sort_by, $order_by);
                $data['offset'] = $offset;
                $data['order_by'] = ($order_by == 'ASC') ? 'DESC' : 'ASC';
                //Load page
                $this->load->view('templates/header');
                $this->load->view('events/assignments_view', $data);
                $this->load->view('templates/footer');
            } else {
                redirect('events');
            }
        }


        //Removes an assignment from the event, so it can no longer be answered by teams.
        public function remove_ass($e_id, $ass_id){
            //Check logged in
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }
            $event = $this->event_model->get_event($e_id);
            //Check if the user is in the same department as the event
            $ismember = FALSE;
            foreach($this->session->userdata('departments') as $department){
                if($department['d_id'] == $event['d_id']){
                    $ismember = TRUE;
                    break;
                }
            }

            if($ismember || $this->session->userdata('permissions') == 'Admin'){
                $this->event_assignment_model->remove_ass($e_id, $ass_id);
                $this->session->set_flashdata('event_removed_ass','Opgave fjernet fra eventet');
                redirect('events/assignments/view/'.$e_id);
            } else {
                redirect('events');
            }
        }


        //Adds an assignment to the event, so it can be answered by teams.
        public function add_ass($e_id, $ass_id){
            //Check logged in
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }
            $event = $this->event_model->get_event($e_id);
            $ismember = FALSE;
            //Check if the user is in the same department as the event
            foreach($this->session->userdata('departments') as $department){
                if($department['d_id'] == $event['d_id']){
                    $ismember = TRUE;
                    break;
                }
            }

            if($ismember || $this->session->userdata('permissions') == 'Admin'){
                $this->event_assignment_model->add_ass($e_id, $ass_id);
                $this->session->set_flashdata('event_added_ass','Opgave tilføjet til eventet');
                redirect('events/assignments/add/'.$e_id);
            } else {
                redirect('events');
            }
        }


        //Delete the event from the system
        public function delete($e_id){
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }
            $event = $this->event_model->get_event($e_id);
            //Check if the user is in the same department as the event
            $ismember = FALSE;
            foreach($this->session->userdata('departments') as $department){
                if($department['d_id'] == $event['d_id']){
                    $ismember = TRUE;
                    break;
                }
            }
            
            if($ismember || $this->session->userdata('permissions') == 'Admin'){
                //Check if entered name matches name in DB
                $input = $this->input->post('input');
                if($input === $event['e_name']){
                    //Remove all assignments from the event
                    $asses = $this->event_assignment_model->get_ass($e_id);
                    foreach($asses as $ass){
                        $this->event_assignment_model->remove_ass($e_id, $ass['ass_id']);
                    }
                    
                    //Delete all the events teams
                    $this->team_model->delete_team($e_id);
                    //Delete actions
                    $this->student_action_model->delete_actions($e_id);
                    //Delete the event folder containing PDF files
                    $eventfolder = url_title($event['e_name'].'-'.$e_id);
                    $dirpath = APPPATH.'../assets/gen-files/'.$eventfolder;
                    $this->delete_dir($dirpath);
                    //Delete the event
                    $this->event_model->delete_event($e_id);
                    $this->session->set_flashdata('event_delete_success','Event slettet');
                    //Load index page
                    redirect('events');
                } else {
                    $this->session->set_flashdata('event_delete_fail', 'Det indtastede navn matcher ikke med eventets navn!');
                    redirect('events/view/'.$e_id);
                }
            }
        }


        //Resets the event. Empties all teams, resets scores, deletes message and action, and removes teams logged answers, so they can answer the same assignments again.
        function reset($e_id){
            //Get all teams in the event
            $teams = $this->team_model->get_teams($e_id);

            $this->event_assignment_model->reset_last_answered($e_id);
            $this->student_action_model->delete_actions($e_id);
            $this->event_model->update_message($e_id, '');
            //Reset teams & answers
            foreach($teams as $team){
                //$this->team_model->delete_answers($team['t_id']);
                $this->team_model->delete_students($team['t_id']);
                $this->team_model->update_score($team['t_id'], 0);
            }
            $this->session->set_flashdata('event_reset', 'Eventet er blevet resat');
            redirect('events/manage/'.$e_id);
        }


        //Views all actions taken by teams in the event
        public function actions($e_id, $offset = 0, $order_by = 'DESC', $sort_by = 'created_at'){
            //Check logged in
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }
            $data['event'] = $this->event_model->get_event($e_id);
            //Check if the user is in the same department as the event
            $ismember = FALSE;
            foreach($this->session->userdata('departments') as $department){
                if($department['d_id'] == $data['event']['d_id']){
                    $ismember = TRUE;
                    break;
                }
            }
            
            if($ismember || $this->session->userdata('permissions') == 'Admin'){
                //Pagination configuration
                $config['base_url'] = base_url('events/actions/'.$e_id.'/');
                $config['total_rows'] = $this->db->where('student_actions.event_id', $e_id)->count_all_results('student_actions');
                $config['per_page'] = 10;
                $config['uri_segment'] = 4;
                $config['attributes'] = array('class' => 'pagination-link');
                $config['first_link'] = 'Første';
                $config['last_link'] = 'Sidste';
                $this->pagination->initialize($config);
                
                //Set data variables
                $data['title'] = 'Handlinger - '.$data['event']['e_name'];
                $data['actions'] = $this->student_action_model->get_actions($e_id, $config['per_page'], $offset, $sort_by, $order_by);
                $data['e_id'] = $data['event']['e_id'];
                $data['offset'] = $offset;
                $data['order_by'] = ($order_by == 'ASC') ? 'DESC' : 'ASC';

                //Load page
                $this->load->view('templates/header');
                $this->load->view('events/actions', $data);
                $this->load->view('templates/footer');
            } else {
                //Not a member or admin
                redirect('events');
            }
        }


        //Loads page where PDFs can be created and viewed
        public function pdf($e_id){
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }
            $data['event'] = $this->event_model->get_event($e_id);
            //Check if the user is in the same department as the event
            $ismember = false;
            foreach($this->session->userdata('departments') as $department){
                if($department['d_id'] == $data['event']['d_id']){
                    $ismember = TRUE;
                    break;
                }
            }
            
            if($ismember || $this->session->userdata('permissions') == 'Admin'){
                //Get data
                $data['title'] = 'PDF - ' . $data['event']['e_name'];
                $data['team_pdf'] = $this->get_pdf($e_id, $data['event']['e_name'], 'team-pdf');
                $data['ass_pdf'] = $this->get_pdf($e_id, $data['event']['e_name'], 'assignment-pdf');
                $data['e_id'] = $e_id;
                
                //Load page
                $this->load->view('templates/header');
                $this->load->view('events/pdf', $data);
                $this->load->view('templates/footer');
            }
        }


        //Views the selected PDF (between 'team' and 'assignment' PDF)
        public function open_pdf($eventfolder, $path_location){
            //Check logged in
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }
            
            //Get PDF path
            $filename = $this->input->post('filename');
            $path_location = APPPATH.'../assets/gen-files/'.$eventfolder.'/'.$path_location.'/'.$filename;
            //Set the HTML to be able to read/view a PDF file
            header('Content-type: application/pdf');
            header("Content-disposition: inline; filename=".$filename);
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            //Open the file at the specified path
            @readfile($path_location);
        }
        

        //Create team pdf
        public function create_team_pdf($e_id){
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }
            
            $data['event'] = $this->event_model->get_event($e_id);
            //Check if the user is in the same department as the event
            $ismember = FALSE;
            foreach($this->session->userdata('departments') as $department){
                if($department['d_id'] == $data['event']['d_id']){
                    $ismember = TRUE;
                    break;
                }
            }
            
            if($ismember || $this->session->userdata('permissions') == 'Admin'){
                set_time_limit ( 300 );
                //Enable QR and PDF libraries
                require_once 'vendor/autoload.php';
                //Store event data in a more easily accessible variable
                $event = $data['event'];
                
                //Set folder names & path
                $eventfolder = url_title($event['e_name'].'-'.$e_id);
                $teamfolder = '/team-pdf/';
                $path = APPPATH.'../assets/gen-files/'.$eventfolder;
                
                //Ensure folders exist and are empty
                $this->check_dir_exists($path, $teamfolder);
                $this->delete_dir_contents($path.$teamfolder);
                
                //Get teams
                $teams = $this->team_model->get_teams($e_id);
                if(empty($teams)){
                    //Don't run the function if there are no teams, otherwise it creates an empty PDF
                    redirect('events/pdf/'.$e_id);
                }
                
                //Set the path for where the PDF is to be saved (../assets/gen-files/..)
                $qr_path = $path.$teamfolder.'qr/';
                $pdf_path = $path.$teamfolder;
                $url_template = base_url('teams/join/'.$e_id.'/');
                
                //Create a QR Code for each team in the event, with a URL that calls the correct Controller when scanned.
                mkdir($qr_path, 0777, TRUE);
                $content_array = array();
                foreach($teams as $team){
                    //Set the URL to the 'join()' function in the 'Teams' Controller.
                    //event_id and team_id are given as parameters.
                    $url = $url_template.$team['t_num'];
                    
                    //Instantiate a new Endroid\QrCode Object. Takes the URL the Code takes you to as a parameter
                    $qrcode = new Endroid\QrCode\QrCode($url);
                    //Qr Code settings
                    $qrcode->setSize(150);
                    $qrcode->setMargin(10);
                    $qrcode->setEncoding('UTF-8');

                    //Create QR Code image file
                    $qr_filename = 'Hold-'.$team['t_num'].'.png';
                    $qrcode->writeFile($qr_path.$qr_filename);
                    
                    //Store text, image, and layout for each page in the PDF
                    $content = '	
                        <div align="center">
                            <h1>Tilføj min mobil til hold '.$team["t_num"].'</h1>
                            <div>
                                <img src="'.$qr_path.$qr_filename.'" />
                            </div>
                        </div>
                    ';
                    $content_array[] = $content;
                }
                
                //PDF settings
                $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetTitle('Team PDF');
                $title_font = TCPDF_FONTS::addTTFfont(APPPATH.'../assets/fonts/Frutiger_Black.ttf','TrueTypeUnicode','',96);
                $pdf->SetFont($title_font,'', 12);
                $pdf->SetAutoPageBreak(true, 10);
                $pdf->SetPrintHeader(false);
                $pdf->SetPrintFooter(false);

                //Create a PDF containing all QR Codes (1 per page)
                foreach($teams as $team){
                    $qr_filename = 'Hold-'.$team['t_num'].'.png';
                    //Add a new page for each team
                    $pdf->AddPage();
                    //Go the the last page
                    $pdf->LastPage();
                    //Insert QR Code in PDF file
                    $pdf->writeHTML($content_array[$team['t_num']-1]);
                }
                //Save PDF file to path.
                $pdf->Output($pdf_path.'Hold.pdf', 'F');
                //Delete temp QR Code folder
                $this->delete_dir($qr_path);
                
                $this->session->set_flashdata('pdf_team_created',"Hold PDF oprettet!");
                set_time_limit (ini_get('max_executuion_time'));
                redirect('events/pdf/'.$e_id);
            }
        }


        //Create assignment PDF
        public function create_ass_pdf($e_id){
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }
            $data['event'] = $this->event_model->get_event($e_id);
            //Check if the user is in the same department as the event
            $ismember = FALSE;
            foreach($this->session->userdata('departments') as $department){
                if($department['d_id'] == $data['event']['d_id']){
                    $ismember = TRUE;
                    break;
                }
            }
            
            if($ismember || $this->session->userdata('permissions') == 'Admin'){
                set_time_limit(300);
                //Enable PDF and QR libraries
                require_once 'vendor/autoload.php';
                $event = $data['event'];

                $assfolder = '/assignment-pdf';
                $eventfolder = url_title($event['e_name'].'-'.$e_id);
                $asses = $this->event_assignment_model->get_ass($e_id);
                
                $path = APPPATH.'../assets/gen-files/'.$eventfolder;
                $this->check_dir_exists($path, $assfolder);            
                $this->delete_dir_contents($path.$assfolder);
                if(empty($asses)){
                    //Stop running the function if there are no assignments, otherwise it will create an empty PDF
                    redirect('events/pdf/'.$e_id);
                }

                //URL
                $url_template = base_url('teams/answer/'.$e_id.'/');
                //Get fonts
                $title_font = TCPDF_FONTS::addTTFfont(APPPATH.'../assets/fonts/Frutiger_Black.ttf','TrueTypeUnicode','',96);
                $main_font = TCPDF_FONTS::addTTFfont(APPPATH.'../assets/fonts/FrutigerNext_LT_Regular.ttf','TrueTypeUnicode','',96);
                //Create a PDF for each assignment containing a QR code for each answer
                foreach($asses as $ass){
                    //PDF settings
                    $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);
                    $pdf->SetCreator(PDF_CREATOR);
                    $pdf->SetTitle('Assignment PDF');
                    $pdf->SetAutoPageBreak(TRUE, 10);
                    //$pdf->setCellPaddings(1, 1, 1, 1);
                    //$pdf->setCellMargins(1, 1, 1, 1);
                    $pdf->SetPrintHeader(FALSE);
                    $pdf->SetPrintFooter(FALSE);
                    //MUST add a new page, otherwise it will throw an error due to not containing any pages
                    $pdf->AddPage();
                    
                    //URL template for each assignment (used later)
                    $ass_url = $url_template . $ass['ass_id'].'/';
                    //Get all answers for the current assignment
                    $ass_answers = $this->assignment_model->get_ass_answers($ass['ass_id']);
                    
                    //Create and save a QR Code for each answer
                    //Set a title with the assignments title in the PDF
                    $pdf->SetFont($title_font,'', 22);
                    $ass_title = '<h1>'.$ass['title'].'</h1>';
                    $pdf->MultiCell(180, 20, $ass_title, 0, 'L', 0, 1, '15', '15', true, 0, true);
                    $pdf->SetFont($main_font, '', 12);
                    
                    //Create PDF
                    //Check amount of answers for the assignment
                    $answers_left = count($ass_answers);
                    $qr_answer_index = 0;
                    //Calculate amount of rows for each assignment
                    $rows = ceil($answers_left/3);

                    //Create 1-3 rows
                    for($i = 0; $i <= $rows; $i++){
                        //Figure out how many times to iterate the loops
                        if($answers_left >= 3){
                            $loops = 3;
                        } else {
                            $loops = $answers_left;
                        }

                        //Write out up to 3 answers per line
                        for($o = 0; $o < $loops; $o++){
                            if($o == $loops-1){
                                $pdf->MultiCell(60, 20, $ass_answers[$qr_answer_index+$o]['answer'], 0, 'C', 0, 1, '', '', true);
                            } else {
                                $pdf->MultiCell(60, 20, $ass_answers[$qr_answer_index+$o]['answer'], 0, 'C', 0, 0, '', '', true);
                            }
                        }
                        //Create and input QR codes for each answer
                        for($o = 0; $o < $loops; $o++){
                            $content = '';
                            //URL the QR leads to
                            $url = $ass_url . $ass_answers[$qr_answer_index+$o]['id'];
                            //Instantiate new object with the URL as parameter
                            $qrcode = new Endroid\QrCode\QrCode('Valgt svar: '.$ass_answers[$qr_answer_index+$o]['answer']."\nTryk 'åben link' for at svare\n\n".$url);
                            //QR Settings
                            $qrcode->setSize(125);
                            //$qrcode->setMargin(10);
                            $qrcode->setEncoding('UTF-8');

                            //Set path+filename for the QR
                            $qrpath = APPPATH.'../assets/gen-files/'.$eventfolder.'/assignment-pdf/'.$ass_answers[$qr_answer_index+$o]['id'].'-'.$o.'.png';
                            //Save QR to machine
                            $qrcode->writeFile($qrpath);

                            $content .= '
                            <div>
                                <img src="'.$qrpath.'" />
                            </div>
                            ';

                            //Ensure the QR Code is placed beneath the relevant Answer
                            if($o == $loops-1){
                                $pdf->MultiCell(60, 25, $content, 0, 'C', 0, 1, '', '', true, 0, true);
                            } else {
                                $pdf->MultiCell(60, 25, $content, 0, 'C', 0, 0, '', '', true, 0, true);
                            }
                            //Delete the .png file from the machine
                            unlink($qrpath);
                        }
                        $qr_answer_index += 3;
                        $answers_left -= 3;
                    }

                    // Multicell params
                    //$pdf->MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
                    $pdf->Output(APPPATH.'../assets/gen-files/'.$eventfolder.'/assignment-pdf/'.url_title($ass['title']).'.pdf','F');
                }
                $this->session->set_flashdata('pdf_ass_created',"Opgave PDF'er oprettet!");
                set_time_limit(ini_get('max_execution_time'));
                redirect('events/pdf/'.$e_id);
            }
        }


        // ONLY PRIVATE FUNCTIONS BELOW THIS POINT
        //Checks if the given folder exists at the specified path. Creates it if it doesn't
        private function check_dir_exists($path, $folder){
            if(!is_dir($path)){
                mkdir($path, 0777, TRUE);
                mkdir($path.$folder, 0777, TRUE);
            }
            if(!is_dir($path.$folder)){
                mkdir($path.$folder, 0777, TRUE);
            }
        }


        //Deletes all files in a folder
        private function delete_dir_contents($dirpath){
            if(substr($dirpath, strlen($dirpath) - 1, 1) != '/') {
                $dirpath .= '/';
            }
            $files = glob($dirpath . '*', GLOB_NOSORT);
            foreach($files as $file){
                unlink($file);
            }
        }


        //Recursive function to delete all files in a folder and the folder itself
        private function delete_dir($dirpath){
            if (substr($dirpath, strlen($dirpath) - 1, 1) != '/') {
                $dirpath .= '/';
            }
            $files = glob($dirpath . '*', GLOB_MARK);
            foreach ($files as $file) {
                if (is_dir($file)) {
                    self::delete_dir($file);
                } else {
                    unlink($file);
                }
            }
            rmdir($dirpath);
        }


        //Returns the max possible points in an event
        private function calc_max_points($event_asses){
            $max_points = 0;
            
            //Get all answers for each assignment
            foreach($event_asses as $ass){
                $ass_points = array();
                $answers = $this->assignment_model->get_ass_answers($ass['ass_id']);
                //Get all points from each answer
                foreach($answers as $answer){
                    $ass_points[] = $answer['points'];
                }
                //Add the highest amount of points to the $max_points
                $max_points += max($ass_points);
            }
            //Return highest possible attainable score for the event
            return $max_points;
        }


        //Returns all PDF files in given folder
        private function get_pdf($e_id, $e_name, $subfolder){
            $eventfolder = url_title($e_name.'-'.$e_id);
            $path = APPPATH.'../assets/gen-files/'.$eventfolder.'/'.$subfolder;
            if(is_dir($path)){
                $files = array_diff(scandir($path), array('.', '..'));
                //$pdf = array();
            } else {
                $files = NULL;
            }
            /* The above code makes the array index start at [2]. This commented code resets it to [0]
            foreach($files as $file){
                $pdf[] = $file;
            }
            */
            return $files;
        }
    }
