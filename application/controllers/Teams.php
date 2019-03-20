<?php
    class Teams extends CI_Controller{

        public function noteam(){
            $data['title'] = 'Ingen hold';

            $this->load->view('teams/noteam', $data);
            $this->load->view('templates/footer');
        }

        public function status($e_id){
            if(isset($_COOKIE['TechQR'])){
                //Unserialize cookie so the data within can be read and used
                $cookie = unserialize($_COOKIE['TechQR']);
                $data['title'] = 'HOLD '.$cookie['t_num'].' - '.$cookie['e_name'];
                $data['message'] = $this->event_model->get_message($cookie['e_id']);
                $team = $this->team_model->get_teams($cookie['e_id'], $cookie['t_num']);
                $data['score'] = $team['t_score'];
            } else {
                //Correct cookie not found
                redirect('teams/noteam');
            }
            
            $this->load->view('templates/header');
            $this->load->view('teams/status', $data);
            $this->load->view('templates/footer');
        }

        public function join($e_id, $t_num){
            //$event = $this->event_model->get_event($e_id);
            $team = $this->team_model->get_teams($e_id, $t_num);

            if(isset($team)){
                //Set cookie variables
                $name = 'TechQR';
                $expiredate = time()+round(5);
                
                //Serialize the value so it can be cookie-fied
                $value = serialize($team);

                //Set cookie
                setcookie($name, $value, $expiredate, '/');
                
                $this->team_model->join_team($team['t_id'], $expiredate);
                $action = "En mobil blev tilføjet";
                $this->student_action_model->create_action($e_id, $team['t_id'], $action);
                redirect('teams/status/'.$e_id.'/'.$t_num);
            } else {
                //Team doesn't exist
                show_404();
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
                    //Reload go to team status/overview page
                    $this->status($e_id);
                } else {
                    //Get answer- and team points
                    $answers = $this->assignment_model->get_ass_answers($ass_id, $ans_id);
                    $team = $this->team_model->get_teams($e_id, $cookie['t_num']);
                    $ans_points = $answers['points'];
                    $team_points = $team['t_score'];
    
                    //Update the teams score
                    $score = $team_points + $ans_points;
                    $this->team_model->update_score($cookie['t_id'], $score);
                    
                    //Add entry to team_assignments table
                    $this->team_model->answer_assignment($cookie['t_id'], $ass_id, $ans_id);
                    //Log action
                    $action = 'Svarede på opgave';
                    $this->student_action_model->create_action($e_id, $cookie['t_id'], $action, $ass_id, $ans_id);
                    //Redirect to the teams overview/status screen
                    redirect('teams/status/'.$e_id.'/'.$cookie['t_num']);
                }
            } else {
                //Redirect to a 'You have no team' page
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
            $this->form_validation->set_rules('teams','"antal hold"','numeric');

            if($this->form_validation->run() === FALSE){
                //If validation failed OR did not run
                $this->load->view('templates/header');
                $this->load->view('events/view', $data);
                $this->load->view('templates/footer');
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

        public function view($e_id, $offset = 0){
            //Check user is logged in
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }

            //Pagination configuration
            $config['base_url'] = base_url().'teams/view/'.$e_id.'/';
            $config['total_rows'] = $this->db->where('teams.event_id', $e_id)->count_all_results('teams');
            $config['per_page'] = 10;
            $config['uri_segment'] = 4;
            $config['attributes'] = array('class' => 'pagination-link');
            $this->pagination->initialize($config);
            
            //Set data variables
            $this->team_model->check_expire_date();
            $event = $this->event_model->get_event($e_id);
            $data['teams'] = $this->team_model->get_teams($e_id, NULL, $config['per_page'], $offset);
            $data['title'] = "Hold oversigt - ".$event['e_name'];
            //Used to calculate the array index difference between 'teams' and 'student_array'
            $data['offset'] = $offset+(($offset+1) % $config['per_page']);
            $data['e_id'] = $e_id;
            
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