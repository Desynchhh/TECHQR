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
                //$unsorted_asses = $this->assignment_model->get_ass_index();
                $asses = $this->assignment_model->get_department_ass($event['d_id']);
                $event_asses = $this->event_assignment_model->get_ass($e_id);//get_event_ass($e_id);

                //Sort assignments, so only the assignments in the events department are shown
                //Create array to temporarily store assignments in
                /*
                $asses_to_keep = array();                
                foreach($unsorted_asses as $ass){
                    if($ass['d_id'] == $event['d_id']){
                        $asses_to_keep[] = $ass;
                    }
                }
                //Store the sorted assignments in an array
                $sorted_asses = $asses_to_keep;
                */

                //Only show the user assignments that have NOT been added to the event, as you shouldn't add the same assignment twice
                //Reset the temp array
                $asses_to_keep = array();
                //Run through all assignments and compare them to the events assignments
                foreach($asses as $ass){
                    $already_contains = false;
                    //Compare all assignments in the event to every other assignment.
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
            $event = $this->event_model->get_event($e_id);
            //Check if the user is in the same department as the event
            foreach($this->session->userdata('departments') as $department){
                if($department['d_id'] == $event['d_id']){
                    $ismember = TRUE;
                    break;
                }
            }
            
            if($ismember || $this->permissions->userdata('permissions') == 'Admin'){
                //Delete all the events assignments
                $asses = $this->event_assignment_model->get_ass($e_id);
                foreach($asses as $ass){
                    $this->event_assignment_model->remove_ass($e_id, $ass['ass_id']);
                }

                //Delete all the events teams
                $this->team_model->delete_team($e_id);
                //Delete the event folder containing PDF files
                $eventfolder = url_title($event['e_name']);
                $dirpath = APPPATH.'../assets/gen-files/'.$eventfolder;
                $this->deleteDir($dirpath);
                //Delete the event
                $this->event_model->delete_event($e_id);
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
            $ismember = false;
            foreach($this->session->userdata('departments') as $department){
                if($department['d_id'] == $data['event']['d_id']){
                    $ismember = TRUE;
                    break;
                }
            }
            
            if($ismember || $this->session->userdata('permissions') == 'Admin'){
                //Get data and load the page
                $data['title'] = 'PDF - ' . $data['event']['e_name'];
                $data['team_pdf'] = $this->get_pdf($e_id, 'team-pdf');
                $data['ass_pdf'] = $this->get_pdf($e_id, 'assignment-pdf');
                $this->load->view('templates/header');
                $this->load->view('events/pdf', $data);
                $this->load->view('templates/footer');
            }
        }

        public function open_pdf($eventfolder, $path_location){
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
        
        //Create team pdf - WIP
        public function create_team_pdf($e_id){
            require_once 'vendor/autoload.php';
            $teamfolder = '/team-pdf';
            
            $event = $this->event_model->get_event($e_id);
            $eventfolder = url_title($event['e_name']);
            $teams = $this->team_model->get_teams($e_id);
            
            $path = APPPATH.'../assets/gen-files/'.$eventfolder;
            $this->check_dir_exists($path, $teamfolder);
            $this->delete_folder_contents($path.$teamfolder);
            
            //PDF settings
            $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetTitle('Team PDF');
            $font_FrutigerBlack = TCPDF_FONTS::addTTFfont(APPPATH.'../assets/fonts/Frutiger_Black.ttf','TrueTypeUnicode','',96);
            $pdf->SetFont($font_FrutigerBlack,'', 12);
            $pdf->SetAutoPageBreak(true, 10);
            //Create a QR Code for each team in the event, with a correct URL that calls the correct Controller when scanned.
            foreach($teams as $team){
                $pdf->AddPage();
                $pdf->LastPage();
                //Set the URL to the 'join()' function in the 'Events' Controller. 
                //event_id and team_id are given as parameters.
                $url = base_url('events/join/'.$e_id.'/'.$team['number']);
                //Instantiate a new Endroid\QrCode object. Takes the URL the Code takes you to as a parameter
                $qrcode = new Endroid\QrCode\QrCode($url);
                //Qr Code settings
                $qrcode->setSize(150);
                $qrcode->setMargin(10);
                $qrcode->setEncoding('UTF-8');

                //Set the path to subfolders in the 'assets' folder corresponding to the team in the event
                $path = APPPATH.'../assets/gen-files/'.$eventfolder.'/team-pdf/Hold-'.$team['number'].'.png';
                //Save the QR Code
                $qrcode->writeFile($path);
                $content = '	
                    <div align="center">
                        <h1>Tilføj min mobil til hold '.$team["number"].'</h1>
                        <div>
                            <img src="'.$path.'" />
                        </div>
                    </div>
                ';
                $pdf->writeHTML($content);
                unlink($path);
            }
            $pdf->Output(APPPATH.'../assets/gen-files/'.$eventfolder.'/team-pdf/Hold.pdf', 'F');
            $this->session->set_flashdata('pdf_team_created',"Hold PDF'er oprettet!");
            redirect('events/pdf/'.$event['e_id']);
        }

        //Create assignment pdf - WIP
        public function create_ass_pdf($e_id){
            //Enable PDF and QR libraries
            require_once 'vendor\autoload.php';
            $assfolder = '/assignment-pdf';
            $event = $this->event_model->get_event($e_id);
            $eventfolder = url_title($event['e_name']);
            
            $path = APPPATH.'../assets/gen-files/'.$eventfolder;
            $this->check_dir_exists($path, $assfolder);            
            $this->delete_folder_contents($path.$assfolder);

            //Create a PDF for each assignment containing a QR code for each answer
            $asses = $this->event_assignment_model->get_ass($e_id);
            foreach($asses as $ass){
                //PDF settings
                $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, TRUE, 'UTF-8', FALSE);
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetTitle('Assignment PDF');
                $pdf->SetAutoPageBreak(true, 10);
                $pdf->setCellPaddings(1, 1, 1, 1);
                $pdf->setCellMargins(1, 1, 1, 1);
                $pdf->SetPrintHeader(false);
                $pdf->SetPrintFooter(false);
                //MUST add a new page, otherwise it will throw an error due to not containing any pages
                $pdf->AddPage();

                //URL template for each assignment (used later)
                $ass_url = base_url('events/answer/'.$e_id.'/'.$ass['ass_id'].'/');
                //Get all answers for the current assignment
                $ass_answers = $this->assignment_model->get_ass_answers($ass['ass_id']);

                //Create and save a QR Code for each answer
                //Set a title with the assignments title in the PDF
                $font_FrutigerBlack = TCPDF_FONTS::addTTFfont(APPPATH.'../assets/fonts/Frutiger_Black.ttf','TrueTypeUnicode','',96);
                $pdf->SetFont($font_FrutigerBlack,'', 12);
                $ass_title = '<h1>'.$ass['title'].'</h1>';
                $pdf->MultiCell(180, 20, $ass_title, 0, 'L', 0, 1, '15', '15', true, 0, true);
                $font_FrutigerLight = TCPDF_FONTS::addTTFfont(APPPATH.'../assets/fonts/Frutiger_Light.ttf','TrueTypeUnicode','',96);
                $pdf->SetFont($font_FrutigerLight, '', 20);
                
                //Create PDF
                //Check amount of answers for the assignment
                $answer_amount = count($ass_answers);
                $answers_per_line = $answer_amount;
                $qr_answer_index = 0;
                //Calculate amount of rows for each assignment
                $rows = ceil($answer_amount/3);

                //Create 1-3 rows
                for($i = 0; $i <= $rows; $i++){
                    //Figure out how many times to iterate the loops
                    if($answers_per_line >= 3){
                        $loops = 3;
                    } else {
                        $loops = $answers_per_line;
                    }

                    //Write out up to 3 answer choices per line
                    for($o = 0; $o < $loops; $o++){
                        if($o == $loops-1){
                            $pdf->MultiCell(60, 10, $ass_answers[$qr_answer_index+$o]['answer'], 0, 'C', 0, 1, '', '', true);
                        } else {
                            $pdf->MultiCell(60, 10, $ass_answers[$qr_answer_index+$o]['answer'], 0, 'C', 0, 0, '', '', true);
                        }
                    }
                    //Create and input QR codes for each answer
                    for($o = 0; $o < $loops; $o++){
                        $content = '';
                        //URL the QR leads to
                        $url = $ass_url . $ass_answers[$qr_answer_index+$o]['id'];
                        //Instantiate new object with the URL as parameter
                        $qrcode = new Endroid\QrCode\QrCode('Valgt svar: '.$ass_answers[$qr_answer_index+$o]['answer']."\nTryk 'åben link' for at svare\n".$url);
                        //QR Settings
                        $qrcode->setSize(125);
                        $qrcode->setMargin(10);
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
                    $answers_per_line -= 3;
                }

                // Multicell params
                //$pdf->MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
                $pdf->Output(APPPATH.'../assets/gen-files/'.$eventfolder.'/assignment-pdf/'.url_title($ass['title']).'.pdf','F');
            }
            $this->session->set_flashdata('pdf_ass_created',"Opgaver PDF'er oprettet!");
            redirect('events/pdf/'.$event['e_id']);
        }


        // ONLY PRIVATE FUNCTIONS BELOW THIS POINT
        //Checks if the given folder exists at the specified path. Creates it if it doesn't
        private function check_dir_exists($path, $folder){
            if(!is_dir($path)){
                mkdir($path, 0777, true);
                mkdir($path.$folder, 0777, true);
            }
            if(!is_dir($path.$folder)){
                mkdir($path.$folder, 0777, true);
            }
        }

        //Deletes only the files within a folder
        private function delete_folder_contents($dirpath){
            if (substr($dirpath, strlen($dirpath) - 1, 1) != '/') {
                $dirpath .= '/';
            }
            $files = glob($dirpath . '*', GLOB_NOSORT);
            foreach($files as $file){
                unlink($file);
            }
        }

        //Recursive function to delete all files within a folder and the folder itself
        private function deleteDir($dirpath){
            if (substr($dirpath, strlen($dirpath) - 1, 1) != '/') {
                $dirpath .= '/';
            }
            $files = glob($dirpath . '*', GLOB_MARK);
            foreach ($files as $file) {
                if (is_dir($file)) {
                    self::deleteDir($file);
                } else {
                    unlink($file);
                }
            }
            rmdir($dirpath);
        }

        //Returns the max possible points in an event
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

        //Returns all PDF files in given folder
        private function get_pdf($e_id, $subfolder){
            $event = $this->event_model->get_event($e_id);
            $eventfolder = url_title($event['e_name']);
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
