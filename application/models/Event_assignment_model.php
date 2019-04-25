<?php
    class Event_assignment_model extends CI_Model{
        function __construct(){
            $this->load->database();
        }


        //Get an assignment from the event
        public function get_ass($e_id, $limit = FALSE, $offset = FALSE, $sort_by = 'title', $order_by = 'ASC'){
            if($limit){
                $this->db->limit($limit, $offset);
            }
            
            $this->db->select('
                assignments.id as ass_id,
                assignments.title,
                assignments.notes,
                departments.name,
                event_assignments.last_answered
            ')
            ->where('event_id', $e_id)
            ->join('assignments','assignments.id = event_assignments.assignment_id')
            ->join('departments','departments.id = assignments.department_id')
            ->order_by($sort_by, $order_by)
            ->from('event_assignments');
            $query = $this->db->get();
            return $query->result_array();
        }


        //Get all assignments not in the specified event
        public function get_ass_not_event($e_id, $d_id, $limit = FALSE, $offset = FALSE, $sort_by = 'title', $order_by = 'DESC'){
            //Sub Query
            $this->db->select('assignment_id')
            ->from('event_assignments')
            ->where('event_id', $e_id);
            $subQuery = $this->db->get_compiled_select();
            
            //Main Query with limit
            if($limit){
                $this->db->limit($limit, $offset);
            }
            $this->db->select('
                assignments.id AS ass_id,
                assignments.title,
                assignments.notes,
                departments.name AS d_name
            ')
            ->from('assignments')
            ->join('departments', 'departments.id = assignments.department_id')
            ->where('assignments.department_id', $d_id)
            ->where("assignments.id NOT IN ($subQuery)", NULL, FALSE)
            ->order_by($sort_by, $order_by);
            $query = $this->db->get();
            return $query->result_array();
        }


        //Gets all the points from an assignment
        public function get_event_points($ass_id){
            $query = $this->db->select('
                answers.assignment_id as ass_id,
                answers.id as ans_id,
                answers.points
            ')
            ->where('event_assignments.assignment_id', $ass_id)
            ->join('answers', 'answers.assignment_id = event_assignments.assignment_id')
            ->from('event_assignments')
            ->get();
            return $query->result_array();
        }


        //Add an assignment to the event
        public function add_ass($e_id, $ass_id){
            $data = array(
                'event_id' => $e_id,
                'assignment_id' => $ass_id
            );
            $this->db->insert('event_assignments', $data);
        }


        //Delete an assignment from the event
        public function remove_ass($e_id, $ass_id){
            $this->db->where('event_id', $e_id)
            ->where('assignment_id', $ass_id)
            ->delete('event_assignments');
        }


        //Update last answered
        public function update_last_answered($e_id, $ass_id){
            //Prep
            $edited_at = date('Y-m-d H:i:s');
            $data = array(
                'last_answered' => $edited_at
            );
            
            //Query
            $this->db->where('event_id', $e_id)
            ->where('assignment_id', $ass_id)
            ->update('event_assignments', $data);
        }


        //Reset last_answered
        public function reset_last_answered($e_id){
            //Prep
            $data = array(
                'last_answered' => NULL
            );

            //Query
            $this->db->where('event_id', $e_id)
            ->update('event_assignments', $data);
        }
    }