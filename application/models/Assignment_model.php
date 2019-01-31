<?php
	class Assignment_model extends CI_Model{
		public function __construct(){
			$this->load->database();
		}
		
		public function create_ass($answerAmount){
			$data = array(
			'title' => $this->input->post('title'),
			'location' => $this->input->post('location')
			);
			$this->db->insert('assignments',$data);

			$ass_id = $this->db->insert_id();
			foreach(range(1, $answerAmount) as $answerNumber){
				$data = array(
					'assignment_id' => $ass_id,
					'answer' => $this->input->post('answer'.$answerNumber),
					'points' => $this->input->post('points'.$answerNumber)
				);
				$this->db->insert('answers', $data);
			}
		}
		
		public function get_ass($search_string = NULL){
			if($search_string === NULL){
			//Get all assignments
			$query = $this->db->select('
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