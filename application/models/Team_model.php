<?php
    class Team_model extends CI_Model{
        public function __contruct(){
            $this->load->database();
        }

        public function create_team($e_id){
            $data = array(
                'event_id' => $e_id
            );
            $this->db->insert('teams', $data);
        }

        public function get_teams($e_id){
            $query = $this->db->select('
                teams.score,
                students.id as s_id
            ')
            ->join('students', 'students.team_id = teams.id')
            ->from('teams')
            ->where('teams.event_id', $e_id)
            ->get();
            return $query->result_array();
        }
    }