<?php
    class Event_assignment_model extends CI_Model{
        function __construct(){
            $this->load->database();
        }

        //Get an assignment from the event
        public function get_ass($e_id){
            $query = $this->db->select('
                assignments.id as ass_id,
                assignments.title,
                assignments.location,
                departments.name
            ')
            ->where('event_id', $e_id)
            ->join('assignments','assignments.id = event_assignments.assignment_id')
            ->join('departments','departments.id = assignments.department_id')
            ->from('event_assignments')
            ->get();
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
    }