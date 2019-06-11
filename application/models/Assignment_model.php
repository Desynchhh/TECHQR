<?php
	class Assignment_model extends CI_Model{
		public function __construct(){
			$this->load->database();
		}
		

		//Create an assignment in the DB
		public function create_ass($answerAmount, $return = FALSE){
			$data = array(
				'title' => $this->input->post('title'),
				'notes' => $this->input->post('notes'),
				'department_id' => $this->input->post('d_id'),
				'created_by' => $this->session->userdata('username'),
				'edited_by' => $this->session->userdata('username')
			);
			$this->db->insert('assignments', $data);

			$ass_id = $this->db->insert_id();
			$this->insert_answers($ass_id, $answerAmount);
			if($return){
				return $ass_id;
			}
		}


		//Update an assignment, delete all answers from the DB, and insert the new  answers
		public function edit_ass($ass_id, $answerAmount){
			$edited_at = date('Y-m-d H:i:s');
			$data = array(
				'title' => $this->input->post('title'),
				'notes' => $this->input->post('notes'),
				'department_id' => $this->input->post('d_id'),
				'edited_by' => $this->session->userdata('username'),
				'edited_at' => $edited_at
			);
			$this->db->where('id', $ass_id)
			->update('assignments', $data);

			$this->db->where('assignment_id', $ass_id)
			->delete('answers');

			$this->insert_answers($ass_id, $answerAmount);
		}


		//Add all answers to the DB
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
		

		//Get 1 assignment
		public function get_ass_view($ass_id){
			$returnarray = array();
			//Get everything related to the assignment itself
			$this->db->select('
				assignments.id as ass_id,
				assignments.title as ass_title,
				assignments.edited_at,
				assignments.edited_by as ass_edited_by,
				assignments.created_at as ass_created_at,
				assignments.created_by as ass_created_by,
				assignments.notes,
				departments.id as d_id,
				departments.name as department
			')
			->where('assignments.id', $ass_id)
			->join('departments','departments.id = assignments.department_id')
			->from('assignments');
			$query = $this->db->get();
			$returnarray = $query->row_array();
			
			//Get all answers to the assignment
			$this->db->select('
				answers.id as ans_id,
				answers.answer,
				answers.points
			')
			->where('answers.assignment_id', $ass_id)
			->from('answers');
			$query = $this->db->get();
			$returnarray[] = $query->result_array();
			return $returnarray;
		}


		//Get all assignments without their answers
		public function get_asses($department_array, $isAdmin, $limit = FALSE, $offset = FALSE, $sort_by, $order_by, $search_string = NULL){
			if($limit){
				$this->db->limit($limit, $offset);
			}

			//Get all assignments
			$this->db->select('
				assignments.id,
				assignments.title,
				assignments.notes,
				assignments.created_by as username,
				departments.name,
				departments.id as d_id
			');
			if(isset($search_string)){
				$this->db->like('assignments.title', $search_string)
				->or_like('assignments.notes', $search_string)
				->or_like('departments.name', $search_string)
				->or_like('assignments.created_by', $search_string);
			}
			$this->db->join('departments', 'departments.id = assignments.department_id');
			if($isAdmin === FALSE){	
				$this->db->where('assignments.department_id', $department_array[0]['d_id']);
				foreach($department_array as $department){
					$this->db->or_where('assignments.department_id', $department['d_id']);
				}
			}
			$this->db->from('assignments');
			$this->db->order_by($sort_by, $order_by);
			$query = $this->db->get();
			return $query->result_array();
		}


		//Get one or all answers to an assignment
		public function get_ass_answers($ass_id, $ans_id = NULL){
			$this->db->select('
				answers.id,
				answers.answer,
				answers.points
			')
			->where('answers.assignment_id', $ass_id);
			if($ans_id){
				$this->db->where('answers.id', $ans_id);
			}
			$this->db->from('answers');
			$query = $this->db->get();
			if($ans_id){
				return $query->row_array();
			} else {
				return $query->result_array();
			}
		}


		//Get all events an assignment is in
		public function get_ass($ass_id){
			$this->db->select('
				events.id as e_id,
				events.name as e_name
			')
			->join('events', 'events.id = event_assignments.event_id')
			->where('event_assignments.assignment_id', $ass_id)
			->from('event_assignments');
			$query = $this->db->get();
			return $query->result_array();
		}


		//Delete an assignment and all related answers
		public function delete_ass($ass_id){
			//Delete from assignments table
			$this->db->where('id', $ass_id)
			->delete('assignments');

			//Delete from answers table
			$this->db->where('assignment_id', $ass_id)
			->delete('answers');
		}


		//Get all assignments in a department
		public function get_department_ass($d_id){
			$query = $this->db->select('
				assignments.id,
				assignments.title,
				assignments.notes,
				departments.name,
			')
			->where('assignments.department_id', $d_id)
			->join('departments', 'departments.id = assignments.department_id')
			->from('assignments')
			->get();

			return $query->result_array();
		}


		//Delete all assignments in a department
		public function delete_department_ass($d_id){
			$query = $this->db->get_where('assignments', array('department_id' => $d_id));
			if(!empty($query->row_array())){
				$this->db->delete('assignments');
			}
		}

		
		//Check if a user has created any assignments, so the proper fields can be changed (used when changing username)
		public function check_created_by($oldname){
			$query = $this->db->get_where('assignments', array('created_by' => $oldname));
			if(empty($query->row_array())){
				//Username has not created any assignments
                return FALSE;
            } else {
				//Username has created assignments
                return TRUE;
            }
		}
		

		//Updates the 'created_by' field in the assignments table in the DB
		public function update_created_by($newname, $oldname){
			$this->db->where('created_by', $oldname)
			->update('assignments', array('created_by' => $newname));
		}

		
		//Check if a user has edited any assignments, so the proper fields can be changed (used when changing username)
		public function check_edited_by($oldname){
			$query = $this->db->get_where('assignments', array('edited_by' => $oldname));
			if(empty($query->row_array())){
				//Username has not edited any assignments
                return FALSE;
            } else {
				//Username has edited assignments
                return TRUE;
            }
		}


		//Updates the 'edited_by' field in the assignments table in the DB
		public function update_edited_by($newname, $oldname){
			$this->db->where('edited_by', $oldname)
			->update('assignments', array('edited_by' => $newname));
		}
	}