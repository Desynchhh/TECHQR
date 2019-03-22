<?php
	class Assignments extends CI_Controller{
		public function index($offset = 0){
			//Check if anyone is logged in
			if(!$this->session->userdata('logged_in')){
                redirect('login');
			}
			
			//Prep
			$isAdmin = ($this->session->userdata('permissions') == 'Admin') ? true : false;
			$user_depts = $this->session->userdata('departments');
			$total_rows = 0;
			if($isAdmin){
				$total_rows = $this->db->count_all_results('assignments');
			} else {
				foreach($user_depts as $dep){
					$total_rows += $this->db->where('assignments.department_id', $dep['d_id'])->count_all_results('assignments');
				}
			}

			//Pagination config
			$config['base_url'] = base_url() . 'assignments/index/';
			$config['total_rows'] = $total_rows;
			$config['per_page'] = 10;
			$config['uri_segment'] = 3;
			$config['attributes'] = array('class' => 'pagination-link');// btn btn-primary
			$this->pagination->initialize($config);

			//Data variables
			$data['title'] = 'Opgave oversigt';
			
			//Get assignments
			if($this->form_validation->run() === FALSE){
				//Get all assignments
				$data['asses'] = $this->assignment_model->get_ass_index($user_depts, $isAdmin, $config['per_page'], $offset, NULL);
			} else {
				//Search the DB for assignments LIKE what the user searched for
				$data['asses'] = $this->assignment_model->get_ass_index($user_depts, $isAdmin, $config['per_page'], $offset, $this->input->post('search_string'));
			}
			
			//Load the page
			$this->load->view('templates/header');
			$this->load->view('assignments/index', $data);
			$this->load->view('templates/footer');
		}
		
		public function view($ass_id){
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }

			$data['title'] = 'Opgave detaljer';
			$data['ass'] = $this->assignment_model->get_ass_view($ass_id);
			//Check the currently logged in users departments. 
			//Only users who are in the same department as the assignment can view it.
			$ismember = false;
			for($i = 0; $i < count($this->session->userdata('departments')); $i++){	
				if($this->session->userdata['departments'][$i]['name'] == $data['ass']['department']){
					$ismember = true;
					break;
				}
			}
				if($ismember || $this->session->userdata('permissions') == 'Admin'){
					//Allow the user to view the assignment details if they are a member of its department
					$this->load->view('templates/header');
					$this->load->view('assignments/view', $data);
					$this->load->view('templates/footer');
				} else {
					//Return the user to the overview page if they tried to access an assignment they are not allowed to access
					redirect('assignments');
				}
		}

		public function delete($ass_id){
			if(!$this->session->userdata['logged_in']){
				redirect('login');
			}
			
			//Check if the logged in user is a member of the assignments department
			$ismember = false;
			$data['ass'] = $this->assignment_model->get_ass_view($ass_id);
			for($i = 0; $i < count($this->session->userdata('departments')); $i++){	
				if($this->session->userdata['departments'][$i]['name'] == $data['ass']['department']){
					$ismember = true;
					break;
				}
			}

			if($ismember || $this->session->userdata('permissions') == 'Admin'){
				$input = $this->input->post('input');
				//Check if the entered name matches the name in the DB
				if($input == $data['ass']['ass_title']){
					//Names match
					$this->assignment_model->delete_ass($ass_id);
					$this->session->set_flashdata('ass_delete_success','Opgave slettet');
					redirect('assignments');
				} else {
					$this->session->set_flashdata('ass_delete_fail','Den indtastede titel matcher ikke opgavens titel!');
					redirect('assignments/view/'.$ass_id);
				}
			} else {
				//Redirect if they are not
				$this->session->set_flashdata('ass_delete_fail', 'Du kan kun slette opgaver fra din egne afdelinger!');
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
				//Set the amount of answers to the user specified amount
				$data['options'] = $this->set_answer_amount($optionsAmount);
			} else {
				//Get the amount of answers to the assignment from the DB
				$data['options'] = $this->set_answer_amount(count($data['ass'][0]));
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