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
        

        //Get a specific team
        public function get_team($t_id){
            $this->db->select('
                teams.id as t_id,
                teams.number as t_num,
                teams.score as t_score,
                events.id as e_id,
                events.name as e_name
            ')
            ->join('events', 'events.id = teams.event_id')
            ->from('teams')
            ->where('teams.id', $t_id);
            $query = $this->db->get();
            return $query->row_array();
        }


        //Get either all teams in an event
        public function get_teams($e_id, $limit = FALSE, $offset = FALSE, $sort_by = 'number', $order_by = 'ASC'){
                    //Sub Query
                $this->db->select('team_id')
                ->from('student_actions')
                ->order_by('created_at', 'desc')
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
        

        //Create a student for a team + insert the expiredate for the cookie
        public function join_team($t_id, $expiredate){
            $data = array(
                'team_id' => $t_id,
                'cookie_epoch_expire_date' => $expiredate
            );
            $this->db->insert('students', $data);
            //Return the ID of the newly created row
            return $this->db->insert_id();
        }


        //Delete a singular student
        public function update_student($old_t_id, $s_id, $new_t_id, $expiredate){
            $data = array(
                'team_id' => $new_t_id,
                'cookie_epoch_expire_date' => $expiredate
            );
            $this->db->where('team_id', $old_t_id)
            ->where('id', $s_id)
            ->update('students', $data);
        }


        //Check student exists in DB
        public function check_cookie($s_id){
            $query = $this->db->get_where('students', array('id' => $s_id));
            $ret = ($query->row_array(0)) ? TRUE : FALSE;
            return $ret;
        }

            //Check if team is attempting to answer an assignment from a different event
        public function check_event($e_id, $t_id){
            $this->db->select('
                teams.id
            ')
            ->where('id', $t_id)
            ->where('event_id', $e_id)
            ->from('teams');
            $query = $this->db->get();
            $ret = (empty($query->row_array())) ? FALSE : TRUE;
            return $ret;
        }

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


        public function get_team_answers($e_id, $t_id = NULL){
            $this->db->select('
                team_id as t_id,
                assignment_id as ass_id,
                answer_id as ans_id
            ')
            ->from('student_actions')
            ->where('event_id', $e_id)
            ->where('assignment_id !=', NULL)
            ->where('answer_id !=', NULL);
            if($t_id){
                $this->db->where('team_id', $t_id);
            }
            $query = $this->db->get();

            return $query->result_array();
        }

        //Remove all students / participants
        public function delete_students($t_id){
            $this->db->where('team_id', $t_id)
            ->delete('students');
        }
        

        //Delete all teams from an event
        public function delete_team($e_id){
            $this->db->where('event_id', $e_id)
            ->delete('teams');
        }
    }