<?php
    class Teams extends CI_Controller{
        //Load 'no team' page. Run if a user scans a QR code without being on a team
        public function noteam(){
            $data['title'] = 'Ingen hold';

            $this->load->view('teams/noteam', $data);
            $this->load->view('templates/footer');
        }


        //Load team status screen
        public function status($e_id){
            if(isset($_COOKIE['TechQR'])){
                //Unserialize cookie so the data within can be read and used
                $cookie = unserialize($_COOKIE['TechQR']);
                //Get team details from DB
                $team = $this->team_model->get_teams($cookie['e_id'], $cookie['t_num']);
                //Set $data array
                $data['title'] = 'HOLD '.$cookie['t_num'].' - '.$cookie['e_name'];
                $data['message'] = $this->event_model->get_message($cookie['e_id']);
                $data['score'] = $team['t_score'];
            } else {
                //Correct cookie not found
                redirect('teams/noteam');
            }
            
            //Load page
            $this->load->view('templates/header');
            $this->load->view('teams/status', $data);
            $this->load->view('templates/footer');
        }


        //Join a team
        public function join($e_id, $t_num){
            //$event = $this->event_model->get_event($e_id);
            //Get team details from DB
            $team = $this->team_model->get_teams($e_id, $t_num);

            if(isset($team)){
                //Set cookie variables
                $name = 'TechQR';
                //Set time until cookie expiration in seconds
                $expiretime = 60;//*60*12;
                //Set cookie expire date
                $expiredate = time()+round($expiretime);
                //Serialize the value so it can be cookie-fied
                $value = serialize($team);
                //Set cookie
                setcookie($name, $value, $expiredate, '/');
                
                //Update team in DB
                $this->team_model->join_team($team['t_id'], $expiredate);
                
                //Create action
                $action = "En mobil blev tilføjet";
                $this->student_action_model->create_action($e_id, $team['t_id'], $action);
                
                //Load team status screen
                redirect('teams/status/'.$e_id.'/'.$t_num);
            } else {
                //Team doesn't exist
                redirect('teams/noteam');
            }
        }


        //Attempt to answer an assignment
        public function answer($e_id, $ass_id, $ans_id){
            if(isset($_COOKIE['TechQR'])){
                $cookie = unserialize($_COOKIE['TechQR']);
                if($this->team_model->check_already_answered($cookie['t_id'], $ass_id)){
                    //Team has already answered the assignment
                    //Log action in DB
                    $action = "Forsøgte at besvare samme opgave mere end en gang";
                    $this->student_action_model->create_action($e_id, $cookie['t_id'], $action, $ass_id);
                    
                    //Set message for team
                    $this->session->set_flashdata('team_already_answered','I kan ikke besvare den samme opgave flere gange!');
                    
                    //Go to team status/overview page
                    $this->status($e_id);
                } else {
                    //Get answer- and team points
                    $answer = $this->assignment_model->get_ass_answers($ass_id, $ans_id);
                    $team = $this->team_model->get_teams($e_id, $cookie['t_num']);
                    $ans_points = $answer['points'];
                    $team_points = $team['t_score'];
    
                    //Update the teams score
                    $score = $team_points + $ans_points;
                    $this->team_model->update_score($cookie['t_id'], $score);

                    //Update last answered
                    $this->event_assignment_model->update_last_answered($e_id, $ass_id);
                    
                    //Log action
                    $action = 'Svarede på opgave';
                    $this->student_action_model->create_action($e_id, $cookie['t_id'], $action, $ass_id, $ans_id);
                    
                    //Redirect to team overview/status screen
                    redirect('teams/status/'.$e_id.'/'.$cookie['t_num']);
                }
            } else {
                //Redirect to a 'no team' page
                redirect('teams/noteam');
            }
        }


        //Create teams for an event
        public function create($e_id){
            //Check a user is logged in
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }

            //Set form validation rules
            $this->form_validation->set_rules('teams','"antal hold"','required|numeric');

            if($this->form_validation->run() === FALSE){
                //If validation failed OR did not run
                //Reload page
                redirect('teams/view/'.$e_id);
            } else {
                //Validation successful
                //Check if event already has an amount of teams
                $teams = $this->team_model->get_teams($e_id);
                if(empty($teams)){
                    //Event has no teams. Create the first team
                    $next_team_number = 1;
                } else {
                    //Event has teams. Increase the team count from the newest team
                    $next_team_number = end($teams)['t_num']+1;
                }
                //Create the requested amount of teams
                for($i = 0; $i < $this->input->post('teams'); $i++){
                    $this->team_model->create_team($e_id, $next_team_number);
                    $next_team_number++;
                }
                //Set message to inform the user of the successful creation of teams
                $this->session->set_flashdata('team_created','Hold oprettet');
                //Reload the page
                redirect('teams/view/'.$e_id);
            }
        }


        //List all created teams in an event
        public function view($e_id, $offset = 0, $order_by = 'ASC', $sort_by = 'number'){
            //Check user is logged in
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }

            //Pagination configuration
            $config['base_url'] = base_url('teams/view/'.$e_id.'/');
            $config['total_rows'] = $this->db->where('teams.event_id', $e_id)->count_all_results('teams');
            $config['per_page'] = 10;
            $config['uri_segment'] = 4;
            $config['attributes'] = array('class' => 'pagination-link');
            $config['first_link'] = 'Første';
            $config['last_link'] = 'Sidste';
            $this->pagination->initialize($config);
            
            //Set data variables
            $this->team_model->check_expire_date();
            $event = $this->event_model->get_event($e_id);
            $data['teams'] = $this->team_model->get_teams($e_id, NULL, $config['per_page'], $offset, $sort_by, $order_by);
            $data['title'] = "Hold oversigt - ".$event['e_name'];
            $data['offset'] = $offset+(($offset+1) % $config['per_page']);
            $data['order_by'] = ($order_by == 'DESC') ? 'ASC' : 'DESC';
            $data['e_id'] = $e_id;
            $data['event_asses'] = $this->db->where('event_id', $e_id)->count_all_results('event_assignments');
            foreach($data['teams'] as $team){
                $data['team_ans'][] = $this->team_model->get_team_answers($e_id, $team['t_id']);
            }
            //Used to calculate the array index difference between 'teams' and 'student_array'
            $data['pagination_offset'] = $offset;

            /*  ECHO DATA IN team_ans
            foreach($data['team_ans'] as $test){
                var_export($test);
                echo'<br>';
            }
            */

            //Get the total amount of members per team and store them in a separate array
            $student_array = array();
            foreach($data['teams'] as $team){
                $student_array[] = $this->db->where('students.team_id', $team['t_id'])->count_all_results('students');
            }
            //Store the array in the $data array
            $data['students'] = $student_array;

            //Load the page
            $this->load->view('templates/header');
            $this->load->view('teams/view', $data);
            $this->load->view('templates/footer');
        }


        //Delete all teams from an event
        public function delete($e_id, $t_id = NULL){
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }

            //Delete one or all teams from the event
            $this->team_model->delete_team($e_id, $t_id);
            //Set flashdata message
            $this->session->set_flashdata('teams_deleted', 'Alle hold slettet');
            //Reload page
            redirect('teams/view/'.$e_id);
        }
    }