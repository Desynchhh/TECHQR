<?php
    class Event_model extends CI_Model{
        public function __contsruct(){
            $this->load->database();
        }

        public function create_event(){
            $data = array(
                'department_id' => $this->input->post('d_id'),
                'name' => $this->input->post('event_name')
            );
            $this->db->insert('events', $data);
        }

        public function get_event($id = NULL){
            if($id){
                //Get specific event
                $query = $this->db->select('
                    events.id as e_id,
                    events.name as e_name,
                    departments.name as d_name,
                    departments.id as d_id
                ')
                ->join('departments', 'departments.id = events.department_id')
                ->from('events')
                ->where('events.id', $id)
                ->get();
                return $query->row_array();
            } else {
                //Get all events
                $query = $this->db->select('
                    events.id as e_id,
                    events.name as e_name,
                    departments.name as d_name
                ')
                ->join('departments', "departments.id = events.department_id")
                ->from('events')
                ->order_by('events.created_at', 'DESC')
                ->get();
                return $query->result_array();
            }
        }

        public function delete_event($e_id){
            $this->db->where('id', $e_id)
            ->delete('events');
        }
    }