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
			'department_id' => $this->input->post('d_id'),
			'created_by' => $this->session->userdata('username'),
			'edited_by' => $this->session->userdata('username')
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
				'edited_by' => $this->session->userdata('username'),
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
				assignments.edited_by as ass_edited_by,
				assignments.created_at as ass_created_at,
				assignments.created_by as ass_created_by,
				assignments.location,
				departments.id as d_id,
				departments.name as department
			')
			->where('assignments.id', $ass_id)
			->join('departments','departments.id = assignments.department_id')
			->from('assignments')
			->get();
			$returnarray = $query->row_array();
			
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
			assignments.created_by as username,
			departments.name,
			departments.id as d_id
			')
			->join('departments', 'departments.id = assignments.department_id')
			->from('assignments')
			->get();
			return $query->result_array();
			} else {
				//Search DB for the given string
			}
		}

		public function get_ass_answers($ass_id){
			$query = $this->db->select('
				answers.id,
				answers.answer,
				answers.points
			')
			->where('answers.assignment_id', $ass_id)
			->from('answers')
			->get();
			return $query->result_array();
		}

		public function delete_ass($ass_id){
			//Delete from assignments table
			$this->db->where('id', $ass_id)
			->delete('assignments');

			//Delete from answers table
			$this->db->where('assignment_id', $ass_id)
			->delete('answers');
		}

		public function get_department_ass($d_id){
			$query = $this->db->select('
				assignments.id,
				assignments.title,
				assignments.location,
				departments.name,
			')
			->where('assignments.department_id', $d_id)
			->join('departments', 'departments.id = assignments.department_id')
			->from('assignments')
			->get();

			return $query->result_array();
		}

		public function delete_department_ass($d_id){
			$query = $this->db->get_where('assignments',array('department_id' => $d_id));
			if(!empty($query->row_array())){
				$this->db->delete('assignments');
			}
		}

		public function check_created_by($oldname){
			$query = $this->db->get_where('assignments',array('created_by' => $oldname));
			if(empty($query->row_array())){
				//Username has not created any assignments
                return false;
            } else {
				//Username has created assignments
                return true;
            }
		}
		
		public function update_created_by($newname, $oldname){
			$this->db->where('created_by', $oldname)
			->update('assignments', array('created_by' => $newname));
		}

		public function check_edited_by($oldname){
			$query = $this->db->get_where('assignments', array('edited_by' => $oldname));
			if(empty($query->row_array())){
				//Username has not edited any assignments
                return false;
            } else {
				//Username has edited assignments
                return true;
            }
		}

		public function update_edited_by($newname, $oldname){
			$this->db->where('edited_by', $oldname)
			->update('assignments', array('edited_by' => $newname));
		}
	}