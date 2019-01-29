<?php
	class Assignment_model extends CI_Model{
		public function __construct(){
			$this->load->database();
		}
		
		public function create_ass(){
			$data = array(
			'title' => $this->input->post('title'),
			'location' => $this->input->post('location'),
			'answer' => $this->input->post('answer'),
			'points' => $this->input->post('points')
			);
			return $this->db->insert('asses',$data);
		}
		
		public function get_ass($search_string = NULL){
			if($search_string === NULL){
			//Get all assignments
			$query = $this->db->get('asses');
			return $query->result_array();
			} else {
				//Search DB for the given string
			}
		}
	}