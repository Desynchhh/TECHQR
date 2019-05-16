<?php
    class Student_action_model extends CI_Model{
        public function __construct(){
            $this->load->database();
        }

        //Insert data in the 'student_actions' table
        public function create_action($e_id, $t_id, $action, $ass_id = NULL, $ans_id = NULL, $points = NULL){
            $data = array(
                'event_id' => $e_id,
                'team_id' => $t_id,
                'assignment_id' => $ass_id,
                'answer_id' => $ans_id,
                'action' => $action,
                'points' => $points
            );
            $this->db->insert('student_actions', $data);
        }


        //Get the newest answered assignment
        public function get_latest_assignment($e_id, $t_id){
            $this->db->select('
                student_actions.created_at,
                assignments.title as ass_title
            ')
            ->where('student_actions.answer_id !=', NULL)
            ->join('assignments', 'assignments.id = student_actions.assignment_id', 'left')
            ->order_by('student_actions.created_at', 'desc')
            ->where('student_actions.event_id', $e_id)
            ->where('student_actions.team_id', $t_id)
            ->from('student_actions');
            $query = $this->db->get();
            return $query->row_array(0);
        }


        //Returns all actions in a single event 
        public function get_actions($e_id, $limit = FALSE, $offset = FALSE, $sort_by = 'created_at', $order_by = 'DESC'){
            if($limit){
                $this->db->limit($limit, $offset);
            }
            
            $this->db->select('
                student_actions.action,
                assignments.title as ass_title,
                teams.number as t_num,
                answers.answer,
                student_actions.points,
                student_actions.created_at
            ')
            ->join('assignments', 'assignments.id = student_actions.assignment_id', 'left')
            ->join('answers', 'answers.id = student_actions.answer_id', 'left')
            ->join('teams', 'teams.id = student_actions.team_id')
            ->where('teams.event_id', $e_id)
            ->where('student_actions.event_id', $e_id)
            ->order_by($sort_by, $order_by)
            ->from('student_actions');
            $query = $this->db->get();
            return $query->result_array();
        }

        //Get the newest action
        public function get_latest_action($t_id){
            $this->db->select('
                student_actions.action
            ')
            ->where('student_actions.team_id', $t_id)
            ->order_by('student_actions.created_at', 'desc')
            ->from('student_actions');
            $query = $this->db->get();
            return $query->row_array(0);
        }

        //Delete all actions from the table
        public function delete_actions($e_id){
            $this->db->where('student_actions.event_id', $e_id)
            ->delete('student_actions');
        }
    }