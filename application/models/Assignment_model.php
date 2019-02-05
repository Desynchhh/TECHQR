<?php
	class Assignment_model extends CI_Model{
		public function __construct(){
			$this->load->database();
		}
		
		public function create_ass($answerAmount){
			$data = array(
			'title' => $this->input->post('title'),
			'location' => $this->input->post('location'),
			//Change this when a user is able to pick which of their departments they want the assignment in
			'department_id' => $this->session->userdata['departments'][0]['d_id'],
			'created_by' => $this->session->userdata('u_id'),
			'edited_by' => $this->session->userdata('u_id')
			//add the creating users "id" in the "created_by" column
			//add the creating users departments "id" in the "department_id" column
			);
			$this->db->insert('assignments',$data);

			$ass_id = $this->db->insert_id();
			$this->insert_answers($ass_id, $answerAmount);
		}

		public function edit_ass($ass_id, $answerAmount){
			$edited_at = date('Y-m-d H:i:s');
			$data = array(
				'title' => $this->input->post('title'),
				'location' => $this->input->post('location'),
				'edited_by' => $this->session->userdata('u_id'),
				'edited_at' => $edited_at
			);
			$this->db->where('id', $ass_id)
			->update('assignments', $data);

			$this->db->where('assignment_id', $ass_id)
			->delete('answers');

			$this->insert_answers($ass_id, $answerAmount);
		}

		function insert_answers($ass_id, $answerAmount){
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
			$returnarray = array();
			//Get everything related to the assignment itself
			$query = $this->db->select('
				assignments.id as ass_id,
				assignments.title as ass_title,
				assignments.edited_at,
				assignments.created_at as ass_created_at,
				assignments.location,
				departments.name as department,
				users.username as ass_created_by
			')
			->where('assignments.id', $ass_id)
			->join('departments','departments.id = assignments.department_id')
			->join('users','users.id = assignments.created_by')
			->from('assignments')
			->get();
			$returnarray = $query->row_array();

			//Get the editors username
			$query = $this->db->select('
				users.username as ass_edited_by
			')
			->where('assignments.id', $ass_id)
			->join('users', 'users.id = assignments.edited_by')
			->from('assignments')
			->get();
			$returnarray[] = $query->row_array();

			//Get all answers
			$query = $this->db->select('
				answers.id as ans_id,
				answers.answer,
				answers.points
			')
			->where('answers.assignment_id', $ass_id)
			->from('answers')
			->get();
			$returnarray[] = $query->result_array();
			return $returnarray;
		}

		public function get_ass_index($search_string = NULL){
			if($search_string === NULL){
			//Get all assignments
			$query = $this->db->select('
			assignments.id,
			assignments.title,
			assignments.location,
			assignments.department_id as d_id,
			users.username
			')
			->join('users','users.id = assignments.created_by')
			->from('assignments')
			->get();
			return $query->result_array();
			} else {
				//Search DB for the given string
			}
		}

		public function delete_ass($ass_id){
			//Delete from assignments table
			$this->db->where('id', $ass_id)
			->delete('assignments');

			//Delete from answers table
			$this->db->where('assignment_id', $ass_id)
			->delete('answers');
		}
	}