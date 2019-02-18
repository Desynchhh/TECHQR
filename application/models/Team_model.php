<?php
    class Team_model extends CI_Model{
        public function __contruct(){
            $this->load->database();
        }

        public function create_team($e_id, $t_number){
            $data = array(
                'event_id' => $e_id,
                'number' => $t_number
            );
            $this->db->insert('teams', $data);
        }

        public function get_teams($e_id){
            $query = $this->db->select('
                teams.id as t_id,
                teams.number,
                teams.score,
                teams.created_at as t_created_at,
                events.name as e_name
            ')
            ->join('events', 'events.id = teams.event_id')
            ->from('teams')
            ->where('teams.event_id', $e_id)
            ->order_by('teams.number', "ASC")
            ->get();
            return $query->result_array();
        }

        public function get_team_members($t_id){
            $query = $this->db->select('
                students.id as s_id
            ')
            ->where('students.team_id', $t_id)
            ->from('students')
            ->get();
            return $query->result_array();
        }

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