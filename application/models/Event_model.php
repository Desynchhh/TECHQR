<?php
    class Event_model extends CI_Model{
        public function __construct(){
            $this->load->database();
        }

        //Creates an event and stores it in the DB
        public function create_event(){
            $data = array(
                'department_id' => $this->input->post('d_id'),
                'name' => $this->input->post('event_name')
            );
            $this->db->insert('events', $data);
        }

        //Gets either a single event or all events
        public function get_event($e_id = NULL, $department_array = NULL, $isAdmin = FALSE, $limit = FALSE, $offset = FALSE, $sort_by = 'e_name', $order_by = 'desc'){
            //Get all events
            if($limit){
				$this->db->limit($limit, $offset);
            }
            
            $this->db->select('
                events.id as e_id,
                events.name as e_name,
                departments.id as d_id,
                departments.name as d_name
            ')
            ->join('departments', "departments.id = events.department_id");
            if($department_array){
                //Get all the users events
                if(!$isAdmin){
                    $this->db->where('events.department_id', $department_array[0]['d_id']);
                    foreach($department_array as $department){
                        $this->db->or_where('events.department_id', $department['d_id']);
                    }
                }
            } else {
                //Get specific event
                $this->db->where('events.id', $e_id);
            }
            $this->db->from('events');
            $this->db->order_by($sort_by, $order_by);
            $query = $this->db->get();
            if($department_array){
                return $query->result_array();
            } else {
                return $query->row_array();
            }
        }

        //Renames an event
        public function edit_event($e_id, $input){
            $data = array(
                'name' => $input
            );
            $this->db->where('id', $e_id)
            ->update('events', $data);
        }

        //Deletes a single event
        public function delete_event($e_id){
            $this->db->where('id', $e_id)
            ->delete('events');
        }

        //Deletes all events created by the specified department
        public function delete_department_event($d_id){
            $query = $this->db->get_where('events', array('department_id' => $d_id));
			if(!empty($query->row_array())){
				$this->db->delete('events');
			}
        }

        //Updates the message to be sent to all teams
        public function update_message($e_id, $msg){
            $data = array(
                'message' => $msg
            );
            $this->db->where('id', $e_id)
            ->update('events', $data);
        }

        //Gets the events message
        public function get_message($e_id){
            $query = $this->db->select('
                events.message
            ')
            ->where('id', $e_id)
            ->from('events')
            ->get();
            return $query->row_array();
        }
    }