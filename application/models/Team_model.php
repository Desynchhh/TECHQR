<?php
    class Team_model extends CI_Model{
        public function __contruct(){
            $this->load->database();
        }


        //Create team
        public function create_team($e_id, $t_num){
            $data = array(
                'event_id' => $e_id,
                'number' => $t_num
            );
            $this->db->insert('teams', $data);
        }
        

        //Get either one or all teams
        public function get_teams($e_id, $t_num = NULL, $limit = FALSE, $offset = FALSE, $sort_by = 'number', $order_by = 'ASC'){
            if($t_num){
                //Get specific team
                $this->db->select('
                teams.id as t_id,
                    teams.number as t_num,
                    teams.score as t_score,
                    events.id as e_id,
                    events.name as e_name
                ')
                ->join('events', 'events.id = teams.event_id')
                ->from('teams')
                ->where('teams.event_id', $e_id)
                ->where('teams.number', $t_num);
                $query = $this->db->get();
                return $query->row_array();
            } else {
                //Get all teams
                //Sub Query
                $this->db->select('team_id')
                ->from('student_actions')
                ->order_by('created_at', 'DESC')
                ->limit(1);
                $subquery = $this->db->get_compiled_select();

                //Main Query
                if($limit){
                    $this->db->limit($limit, $offset);
                }

                $this->db->select('
                    teams.id as t_id,
                    teams.number as t_num,
                    teams.score as t_score,
                    teams.created_at as t_created_at,
                    events.name as e_name,
                    student_actions.action
                ')
                ->join('events', 'events.id = teams.event_id')
                ->join('student_actions', "$subquery = teams.id", 'left')
                ->from('teams')
                ->where('teams.event_id', $e_id)
                ->order_by($sort_by, $order_by);
                $query = $this->db->get();
                return $query->result_array();
            }
        }
        

        //Create a student for a team + insert the expiredate for the cookie
        public function join_team($t_id, $expiredate){
            $data = array(
                'team_id' => $t_id,
                'cookie_epoch_expire_date' => $expiredate
            );
            $this->db->insert('students', $data);
        }


        /* DEPRECATED.
        //Save the fact that a team has answered an assignment
        public function answer_assignment($t_id, $ass_id, $ans_id, $e_id){
            $data = array(
                'team_id' => $t_id,
                'assignment_id' => $ass_id,
                'answer_id' => $ans_id,
                'event_id' => $e_id
            );
            $this->db->insert('team_assignments', $data);
        }
        */


        //Check if the team has already answered the assignment they are attempting to answer
        public function check_already_answered($t_id, $ass_id){
            $data =  array(
                'team_id' => $t_id,
                'assignment_id' => $ass_id
            );
            $query = $this->db->get_where('student_actions', $data);
            if(empty($query->row_array())){
                return FALSE;
            } else {
                return TRUE;
            }
        }


        //Update a teams score
        public function update_score($t_id, $score){
            $data = array(
                'score' => $score
            );
            $this->db->where('teams.id', $t_id)
            ->update('teams', $data);
            return TRUE;
        }


        //Checks the expiredate for all students and deletes the ones that are necessary
        public function check_expire_date(){
            $this->db->where('students.cookie_epoch_expire_date <', time())
            ->delete('students');
        }


        public function get_team_answers($e_id){
            $this->db->select('
                team_id as t_id,
                assignment_id as ass_id,
                answer_id as ans_id
            ')
            ->from('student_actions')//team_assignments
            ->where('event_id', $e_id)
            ->where('assignment_id !=', NULL);
            $query = $this->db->get();

            return $query->result_array();
        }


        //Remove all students / participants
        public function delete_students($t_id){
            $this->db->where('team_id', $t_id)
            ->delete('students');
        }


        /*
        //Reset all the teams answers
        public function delete_answers($t_id){
            $this->db->where('team_id', $t_id)
            ->delete('team_assignments');
        }
        */
        

        //Delete all teams from an event
        public function delete_team($e_id){
            //Delete all teams in the event
            $this->db->where('event_id', $e_id)
            ->delete('teams');
        }
    }