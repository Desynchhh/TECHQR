<?php
	class Assignments extends CI_Controller{
		public function index(){
			$data['title'] = 'Opgave oversigt';
			if($this->form_validation->run() === FALSE){
				$data['ass'] = $this->assignment_model->get_ass();
			} else {
				$data[] = $this->assignment_model->get_ass($this->input->post('search_string'));
			}
				$this->load->view('templates/header');
				$this->load->view('assignments/index', $data);
				$this->load->view('templates/footer');
		}
		
		public function create($answerAmount = 1, $assTitle = NULL, $assLocation = NULL){
			$minOptions = 1;
			$maxOptions = 10;
			if($answerAmount <= $minOptions){
				$answerAmount = $minOptions;
			}
			else if($answerAmount >= $maxOptions){
				$answerAmount = $maxOptions;
			}
			
			$data['title'] = 'Opret opgave';
			$data['optionsAmount'] = $answerAmount;
			$data['maxOptions'] = 10;
			$data['assTitle'] = $this->input->post('title');
			$data['assLocation'] = $this->input->post('location');;

			$this->form_validation->set_rules('title','"opgave titel"','required');
			$this->form_validation->set_rules('answer','"svar mulighed"','required');
			$this->form_validation->set_rules('points','"point"','required');
			
			if($this->form_validation->run() === FALSE){
				$this->load->view('templates/header');
				$this->load->view('assignments/create', $data);
				$this->load->view('templates/footer');
			} else {
				$this->assignment_model->create_ass();
				$this->session->set_flashdata('ass_created','Opgave '.$this->input->post('title').' oprettet succesfuldt!');
				redirect('assignments');
			}
		}
	}