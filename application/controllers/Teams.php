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


        public function answer($e_id, $ass_id, $ans_id){
            if(isset($_COOKIE['TechQR'])){
                $cookie = unserialize($_COOKIE['TechQR']);
                if($this->team_model->check_already_answered($cookie['t_id'], $ass_id)){
                    //Team has already answered the assignment
                    $this->session->set_flashdata('team_already_answered','I kan ikke besvare den samme opgave flere gange!');
                    $this->status($e_id);
                } else {
                    //Get answer and team points
                    $answers = $this->assignment_model->get_ass_answers($ass_id, $ans_id);
                    $team = $this->team_model->get_teams($e_id, $cookie['t_num']);
                    
                    $ans_points = $answers['points'];
                    $team_points = $team['t_score'];
    
                    //Update the teams score
                    $score = $team_points + $ans_points;
                    $this->team_model->update_score($cookie['t_id'], $score);
                    
                    //Add entry to team_assignments table
                    $this->team_model->answer_assignment($e_id, $ass_id, $ans_id);
                    //Log action
                    $action = 'Svarede på opgave';
                    $this->student_action_model->create_action($e_id, $cookie['t_id'], $action, $ass_id);
                    //Redirect to the teams overview/status screen
                    redirect('teams/status/'.$e_id.'/'.$cookie['t_num']);
                }
            } else {
                //Redirect to a 'You have no team' page
                redirect('teams/noteam');
            }
        }

        public function create($e_id){
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }

            $this->form_validation->set_rules('teams','"antal hold"','numeric');

            if($this->form_validation->run() === FALSE){
                $this->load->view('templates/header');
                $this->load->view('events/view', $data);
                $this->load->view('templates/footer');
            } else {
                $teams = $this->team_model->get_teams($e_id);
                if(empty($teams)){
                    //Event has no teams. Create the first team
                    $next_team_number = 1;
                } else {
                    //Event has teams. Increase the team count from the newest team
                    $next_team_number = count($teams)+1;
                }
                //Create the requested amount of teams
                for($i = 0; $i < $this->input->post('teams'); $i++){
                    $this->team_model->create_team($e_id, $next_team_number);
                    $next_team_number++;
                }
                $this->session->set_flashdata('team_created','Hold oprettet');
                //Reload the page
                redirect('teams/view/'.$e_id);
            }
        }

        public function view($e_id){
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }
            
            $this->team_model->check_expire_date();
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

            //Delete one or all teams from the event
            $this->team_model->delete_team($e_id, $t_id);
            //Set flashdata message
            $this->session->set_flashdata('teams_deleted', 'Alle hold slettet');
            //Reload page
            redirect('teams/view/'.$e_id);
        }

        public function delete_answers($t_id){
            $this->db->where('team_assignments.team_id', $t_id)
            ->delete('team_assignments');
        }
    }