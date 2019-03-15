<?php
    class Student_action_model extends CI_Model{
        public function __construct(){
            $this->load->database();
        }

        public function create_action($e_id, $t_id, $action, $ass_id = NULL){
            $data = array(
                'event_id' => $e_id,
                'team_id' => $t_id,
                'assignment_id' => $ass_id,
                'action' => $action
            );
            $this->db->insert('student_actions', $data);
        }

        //Get all IDs
        public function get_action_id($e_id){
            $query = $this->db->select('
                student_actions.id as act_id
            ')
            ->where('student_actions.event_id', $e_id)
            ->from('student_actions')
            ->get();
            return $query->result_array();
        }

        //Check if the action has an assignment ID
        public function check_has_ass_id($act_id){
            $query = $this->db->select('
                student_actions.assignment_id
            ')
            ->where('student_actions.id', $act_id)
            ->from('student_actions')
            ->get();

            if($query->row_array()['assignment_id'] === NULL){
                //Doesn't have assignment id
                return FALSE;
            } else {
                //Has assignment id
                return TRUE;
            }
        }

        public function get_action($e_id, $act_id, $has_ass_id = FALSE){
            if($has_ass_id){
                $query = $this->db->select('
                    student_actions.action,
                    assignments.title as ass_title,
                    teams.number as t_num,
                    answers.answer,
                    student_actions.created_at
                ')
                ->join('assignments', 'assignments.id = student_actions.assignment_id')
                ->join('answers', 'answers.assignment_id = student_actions.assignment_id');
            } else {
                $query = $this->db->select('
                    student_actions.action,
                    teams.number as t_num,
                    student_actions.created_at
                ');
            }
            $this->db->join('teams', 'teams.id = student_actions.team_id')
            ->where('teams.event_id', $e_id)
            ->where('student_actions.id', $act_id)
            ->from('student_actions');
            $query = $this->db->get();
            return $query->row_array();
        }

        public function delete_actions($e_id){
            $this->db->where('student_actions.event_id', $e_id)
            ->delete('student_actions');
        }
        
        /*   DEPRECATED
        public function get_action_ass($e_id, $act_id){
            //if($has_ass_id){
                $query = $this->db->select('
                    student_actions.action,
                    assignments.title as ass_title,
                    teams.number as t_num,
                    answers.answer,
                    student_actions.created_at
                ')
                ->join('teams', 'teams.id = student_actions.team_id')
                ->join('assignments', 'assignments.id = student_actions.assignment_id')
                ->join('answers', 'answers.assignment_id = student_actions.assignment_id')
                ->where('teams.event_id', $e_id)
                ->where('student_actions.id', $act_id)
                ->from('student_actions')
                ->order_by('student_actions.created_at', 'DESC')
                ->get();
            } else {
                $query = $this->db->select('
                    student_actions.action,
                    teams.number as t_num,
                    student_actions.created_at
                ')
                ->join('teams', 'teams.id = student_actions.team_id')
                ->where('teams.event_id', $e_id)
                ->from('student_actions')
                ->order_by('student_actions.created_at', 'DESC')
                ->get();
            }
            return $query->row_array();
        }
        */

        /*   DEPRECATED
        public function get_action_standard($e_id, $act_id){
            $query = $this->db->select('
                student_actions.action,
                teams.number as t_num,
                student_actions.created_at
            ')
            ->join('teams', 'teams.id = student_actions.team_id')
            ->where('teams.event_id', $e_id)
            ->where('student_actions.id', $act_id)
            ->from('student_actions')
            ->order_by('student_actions.created_at', 'DESC')
            ->get();
            return $query->row_array();
        }
        */
    }