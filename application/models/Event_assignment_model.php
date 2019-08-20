<?php
	class Event_assignment_model extends CI_Model{
		function __construct(){
			$this->load->database();
		}


		// Get an assignment from the event
		public function get_ass($e_id, $limit = FALSE, $offset = FALSE, $sort_by = 'title', $order_by = 'ASC', $search_string = NULL){
			if($limit){
				$this->db->limit($limit, $offset);
			}
			
			$this->db->select('
				assignments.id as ass_id,
				assignments.title,
				assignments.notes,
				departments.name,
				event_assignments.last_answered
			');
			$this->db->where('event_id', $e_id);
			if(!empty($search_string)){
				$this->db->like('title', $search_string)
				->or_like('notes', $search_string)
				->where('event_id', $e_id);	// This is the same statement as above the "if". Without this line a 2nd time, the code will get any assignments in the event_assignments table, 100% disregaring the event_id
			}
			$this->db->join('assignments','assignments.id = event_assignments.assignment_id')
			->join('departments','departments.id = assignments.department_id')
			->order_by($sort_by, $order_by);
			$this->db->from('event_assignments');
			$query = $this->db->get();
			return $query->result_array();
		}


		public function not_in_event_subquery($e_id){
			$this->db->select('assignment_id')
			->from('event_assignments')
			->where('event_id', $e_id);
			return $this->db->get_compiled_select();
		}


		// Get all assignments not in the specified event
		public function get_ass_not_event($e_id, $d_id, $limit = FALSE, $offset = FALSE, $sort_by = 'title', $order_by = 'DESC', $search_string = NULL){
			// Subquery
			$subQuery = $this->not_in_event_subquery($e_id);
			
			// Main Query with limit
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
			->where("assignments.id NOT IN ($subQuery)", NULL, FALSE);
			if(!empty($search_string)){
				$this->db->like('assignments.title', $search_string)
				->or_like('assignments.notes', $search_string)
				->where('assignments.department_id', $d_id);
			}
			$this->db->order_by($sort_by, $order_by);
			$query = $this->db->get();
			return $query->result_array();
		}


		// Returns amount of assignments that haven't been added to the event
		public function count_ass_not_event($e_id, $d_id, $search = NULL){
			if($search){
				// Subquery
				$subQuery = $this->not_in_event_subquery($e_id);

				// How many assignments in department
				$this->db->join('departments', 'departments.id = assignments.department_id')
				->where('assignments.department_id', $d_id)
				->where("assignments.id NOT IN ($subQuery)", NULL, FALSE)
				->like('assignments.title', $search)
				->or_like('assignments.notes', $search)
				->from('assignments');
				$ass_query = $this->db->get();
			} else {
				// How many assignments in department
				$this->db->where('department_id', $d_id)
				->from('assignments');
				$ass_query = $this->db->get();
			}
			// How many assignments in event
			$this->db->where('event_id', $e_id)
			->from('event_assignments');
			$event_ass_query = $this->db->get();
			
			// Calculate and return
			$ass_count = $ass_query->num_rows();
			$event_ass_count = $event_ass_query->num_rows();
			$total_rows = $ass_count - $event_ass_count;
			return $total_rows;
		}


		// Gets all the points from an assignment
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


		// Add an assignment to the event
		public function add_ass($e_id, $ass_id){
			$data = array(
				'event_id' => $e_id,
				'assignment_id' => $ass_id
			);
			$this->db->insert('event_assignments', $data);
		}


		// Delete an assignment from the event
		public function remove_ass($e_id, $ass_id){
			$this->db->where('event_id', $e_id)
			->where('assignment_id', $ass_id)
			->delete('event_assignments');
		}


		// Delete an assignment from all events
		public function remove_ass_all($ass_id){
			$this->db->where('assignment_id', $ass_id)
			->delete('event_assignments');
		}


		// Update last answered
		public function update_last_answered($e_id, $ass_id){
			// Prep
			$edited_at = date('Y-m-d H:i:s');
			$data = array(
				'last_answered' => $edited_at
			);
			
			// Query
			$this->db->where('event_id', $e_id)
			->where('assignment_id', $ass_id)
			->update('event_assignments', $data);
		}


		// Reset last_answered
		public function reset_last_answered($e_id){
			// Prep
			$data = array(
				'last_answered' => NULL
			);

			// Query
			$this->db->where('event_id', $e_id)
			->update('event_assignments', $data);
		}
	}