<?php
	class Assignment_model extends CI_Model{
		public function __construct(){
			$this->load->database();
		}
		
		public function create_ass($answerAmount){
			$data = array(
			'title' => $this->input->post('title'),
			'location' => $this->input->post('location')
			//add the creating users "id" in the "created_by" column
			//add the creating users departments "id" in the "department_id" column
			);
			$this->db->insert('assignments',$data);

			$ass_id = $this->db->insert_id();
			for($i = 1; $i<= $answerAmount; $i++){
				$data = array(
					'assignment_id' => $ass_id,
					'answer' => $this->input->post('answer'.$i),
					'points' => $this->input->post('points'.$i)
				);
				$this->db->insert('answers', $data);
			}
		}
		
		public function get_ass_view($ass_id){
			$query = $this->db->select('
				assignments.title as ass_title,
				assignments.created_at as ass_created_at,
				assignments.location,
				departments.name as department,
				answers.id as ans_id,
				answers.answer,
				answers.points,
				users.username as ass_created_by
			')
			->where('assignments.id', $ass_id)
			->join('departments','departments.id = assignments.department_id')
			->join('answers','answers.assignment_id = assignments.id')
			->join('users','users.id = assignments.created_by')
			->from('assignments')
			->get();
			return $query->row_array();
		}

		public function get_ass_index($search_string = NULL){
			if($search_string === NULL){
			//Get all assignments
			$query = $this->db->select('
			id,
			created_by as username,
			title,
			location
			')
			->from('assignments')
			->get();
			return $query->result_array();
			} else {
				//Search DB for the given string
			}
		}
	}