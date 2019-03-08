<?php
    class Team_model extends CI_Model{
        public function __contruct(){
            $this->load->database();
        }

        //REMOVE THIS FUNCTION ONCE IT IS NO LONGER NEEDED
        public function force_delete(){
            $this->db->where('team_id >', 0)
            ->delete('team_assignments');
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
        public function get_teams($e_id, $t_num = NULL){
            if($t_num){
                $query = $this->db->select('
                teams.id as t_id,
                teams.number as t_num,
                teams.score as t_score,
                events.id as e_id,
                events.name as e_name
                ')
                ->join('events', 'events.id = teams.event_id')
                ->from('teams')
                ->where('teams.event_id', $e_id)
                ->where('teams.number', $t_num)
                ->get();
                return $query->row_array();
            } else {
                $query = $this->db->select('
                teams.id as t_id,
                teams.number as t_num,
                teams.score as t_score,
                events.name as e_name,
                teams.created_at as t_created_at
                ')
                ->join('events', 'events.id = teams.event_id')
                ->from('teams')
                ->where('teams.event_id', $e_id)
                ->order_by('teams.number', "ASC")
                ->get();
                return $query->result_array();
            }
        }
        
        //Get all students from a team
        public function get_team_members($t_id){
            $query = $this->db->select('
            students.id as s_id
            ')
            ->where('students.team_id', $t_id)
            ->from('students')
            ->get();
            return $query->result_array();
        }
        
        //Create a student for a team + insert the expiredate for the cookie
        public function join_team($t_id, $expiredate){
            $data = array(
                'team_id' => $t_id,
                'cookie_epoch_expire_date' => $expiredate
            );
            $this->db->insert('students', $data);
        }

        public function answer_assignment($t_id, $ass_id, $ans_id){
            $data = array(
                'team_id' => $t_id,
                'assignment_id' => $ass_id,
                'answer_id' => $ans_id
            );
            $this->db->insert('team_assignments', $data);
        }

        public function update_score($t_id, $score){
            $data = array(
                'score' => $score
            );
            $this->db->where('teams.id', $t_id)
            ->update('teams', $data);
            return true;
        }

        //Checks the expiredate for all students and deletes the ones that are necessary
        public function check_expire_date(){
            $this->db->where('students.cookie_epoch_expire_date <', time())
            ->delete('students');
        }
        
        //Delete all teams from an event
        public function delete_team($e_id, $t_id = NULL){
            if($t_id){
                //Delete a specific team
                $this->db->where('event_id', $e_id)
                ->where('id', $t_id)
                ->delete('teams');
            } else {
                //Delete all teams in the event
                $this->db->where('event_id', $e_id)
                ->delete('teams');
            }
        }
    }