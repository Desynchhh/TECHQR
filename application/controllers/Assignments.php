<?php
	class Assignments extends CI_Controller{
		public function index(){
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }

			$data['title'] = 'Opgave oversigt';
			if($this->form_validation->run() === FALSE){
				//Get all assignments
				$data['asses'] = $this->assignment_model->get_ass_index();
			} else {
				//Search the DB for assignments LIKE what the user searched for
				$data['asses'] = $this->assignment_model->get_ass_index($this->input->post('search_string'));
			}
			//Sort through all assignments
			//Only show assignments with the relevant department
			$tokeep = array();
			for($i = 0; $i < count($data['asses']); $i++){
				for($o = 0; $o < count($this->session->userdata('departments')); $o++){
					if($data['asses'][$i]['d_id'] == $this->session->userdata['departments'][$o]['d_id']){
						//Keep the assignment if any of the users departments match the assignments department
						$tokeep[] = $data['asses'][$i];
					}
				}
			}
			//Store the kept assignments in the $data array in order to transfer them to the view	
			$data['asses'] = $tokeep;

			$this->load->view('templates/header');
			$this->load->view('assignments/index', $data);
			$this->load->view('templates/footer');
		}
		
		public function view($ass_id = NULL){
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }

			$data['title'] = 'Opgave detaljer';
			$data['ass'] = $this->assignment_model->get_ass_view($ass_id);
			//Check the currently logged in users departments. 
			//Only users who are in the same department as the assignment can view it.
			for($i = 0; $i < count($this->session->userdata('departments')); $i++){	
				if($this->session->userdata['departments'][$i]['name'] == $data['ass']['department']){
					$ismember = true;
					break;
				}
			}
				if($ismember){
					//Allow the user to view the assignment details if they are a member of its department
					$this->load->view('templates/header');
					$this->load->view('assignments/view', $data);
					$this->load->view('templates/footer');
				} else {
					//Return the user to the overview page if they tried to access an assignment they are not allowed to access
					redirect('assignments');
				}
		}

		public function confirm_delete($ass_id){
			if(!$this->session->userdata['logged_in']){
				redirect('login');
			}
			
			$data['title'] = "SLET OPGAVE?";
			$data['ass'] = $this->assignment_model->get_ass_view($ass_id);
			for($i = 0; $i < count($this->session->userdata('departments')); $i++){	
				if($this->session->userdata['departments'][$i]['name'] == $data['ass']['department']){
					$ismember = true;
					break;
				}
			} 
			if($ismember){
				$this->load->view('templates/header');
				$this->load->view('assignments/confirm_delete', $data);
				$this->load->view('templates/footer');
			} else {
				redirect('assignments');
			}
		

		}

		public function delete($ass_id){
			if(!$this->session->userdata['logged_in']){
				redirect('login');
			}
			
			//Check if the logged in user is a member of the assignments department
			$data['ass'] = $this->assignment_model->get_ass_view($ass_id);
			for($i = 0; $i < count($this->session->userdata('departments')); $i++){	
				if($this->session->userdata['departments'][$i]['name'] == $data['ass']['department']){
					$ismember = true;
					break;
				}
			}
			if($ismember){
				//Delete if they are a member
				$this->assignment_model->delete_ass($ass_id);
				$this->session->set_flashdata('ass_delete_success','Opgave slettet');
				redirect('assignments');
			} else {
				//Redirect if they are not
				$this->session->set_flashdata('ass_delete_fail', 'is member: '.$ismember);//'Du kan kun slette opgaver fra din egne afdelinger!'
				redirect('assignments');
			}
		}

		public function create($answerAmount = 1){
            if(!$this->session->userdata('logged_in')){
                redirect('login');
			}
			
			$data['title'] = 'Opret opgave';
			$data['options'] = $this->set_answer_amount($answerAmount);

			$this->form_validation->set_rules('title','"opgave titel"','required');
			//Set validation rules for all generated 'answer' and 'points' fields
			for($i = 1; $i<= $data['options']['optionsAmount']; $i++){
				$this->form_validation->set_rules('answer'.$i,'"svar mulighed '.$i.'"','required');
				$this->form_validation->set_rules('points'.$i,'"point '.$i.'"','required|numeric');
			}
			
			if($this->form_validation->run() === FALSE){
				//If validation failed or didn't run
				$this->load->view('templates/header');
				$this->load->view('assignments/create', $data);
				$this->load->view('templates/footer');
			} else {
				//If validation was successful
				$this->assignment_model->create_ass($data['options']['optionsAmount']);
				$this->session->set_flashdata('ass_created','Opgave oprettet!');
				redirect('assignments');
			}
		}

		public function edit($ass_id, $optionsAmount = NULL){
			if(!$this->session->userdata('logged_in')){
                redirect('login');
            }

			$data['title'] = "Rediger opgave";
			$data['ass'] = $this->assignment_model->get_ass_view($ass_id);
			if($optionsAmount){
				$data['options'] = $this->set_answer_amount($optionsAmount);
			} else {
				$data['options'] = $this->set_answer_amount(count($data['ass'][1]));
			}
			$this->form_validation->set_rules('title','"opgave titel"','required');
			//Set validation rules for all generated 'answer' and 'points' fields
			for($i = 1; $i<= $data['options']['optionsAmount']; $i++){
				$this->form_validation->set_rules('answer'.$i,'"svar mulighed '.$i.'"','required');
				$this->form_validation->set_rules('points'.$i,'"point '.$i.'"','required|numeric');
			}
			if($this->form_validation->run() === FALSE){
				//If validation failed or didn't run
				$this->load->view('templates/header');
				$this->load->view('assignments/edit', $data);
				$this->load->view('templates/footer');
			} else {
				//If validation succeeded
				$this->assignment_model->edit_ass($ass_id, $data['options']['optionsAmount']);
				$this->session->set_flashdata('ass_edited','Opgaven er blevet redigeret');
				redirect('assignments/view/'.$ass_id);
			}
		}

		public function set_answer_amount($answerAmount = 1){
			$minOptions = 1;
			//increase $maxOptions to increase the max amount of answers an assignment can have
			$maxOptions = 9;
			//Ensure the user didn't somehow request a too large or too small amount of fields
			if($answerAmount < $minOptions){
				$answerAmount = $minOptions;
			}
			else if($answerAmount > $maxOptions){
				$answerAmount = $maxOptions;
			}
			//Put values into an array to return them
			$options = array(
				'optionsAmount' => $answerAmount,
				'maxOptions' => $maxOptions
			);
			//Return values to page (preferably into a $data array)
			return $options;
		}
	}