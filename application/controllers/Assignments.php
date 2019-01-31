<?php
	class Assignments extends CI_Controller{
		public function index(){
			$data['title'] = 'Opgave oversigt';
			if($this->form_validation->run() === FALSE){
				$data['asses'] = $this->assignment_model->get_ass_index();
			} else {
				$data[] = $this->assignment_model->get_ass_index($this->input->post('search_string'));
			}
			$this->load->view('templates/header');
			$this->load->view('assignments/index', $data);
			$this->load->view('templates/footer');
		}
		
		public function view($ass_id = NULL){
			$data['title'] = 'Opgave detaljer';
			$data['ass'] = $this->assignment_model->get_ass_view($ass_id);

			$this->load->view('templates/header');
			$this->load->view('assignments/view', $data);
			$this->load->view('templates/footer');
		}

		public function create($answerAmount = 1){
			$minOptions = 1;
			$maxOptions = 10;
			if($answerAmount < $minOptions){
				$answerAmount = $minOptions;
			}
			else if($answerAmount > $maxOptions){
				$answerAmount = $maxOptions;
			}
			$data['title'] = 'Opret opgave';
			$data['optionsAmount'] = $answerAmount;
			$data['maxOptions'] = 10;
			$data['assTitle'] = $this->input->post('title');
			$data['assLocation'] = $this->input->post('location');;

			$this->form_validation->set_rules('title','"opgave titel"','required');
			for($i = 1; $i<= $answerAmount; $i++){
				$this->form_validation->set_rules('answer'.$i,'"svar mulighed"','required');
				$this->form_validation->set_rules('points'.$i,'"point"','required|numeric');
			}
			
			if($this->form_validation->run() === FALSE){
				$this->load->view('templates/header');
				$this->load->view('assignments/create', $data);
				$this->load->view('templates/footer');
			} else {
				$this->assignment_model->create_ass($answerAmount);
				$this->session->set_flashdata('ass_created','Opgave '.$this->input->post('title').' oprettet succesfuldt!');
				redirect('assignments');
			}
		}
	}