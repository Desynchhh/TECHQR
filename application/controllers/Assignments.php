<?php
	class Assignments extends CI_Controller{
		public function index($per_page = 10, $order_by = 'asc', $sort_by = 'title', $offset = 0){
			// Check if anyone is logged in
			if(!$this->session->userdata('logged_in')){
				redirect('login');
			}

			// Get search string from form or userdata
			$search_string = $this->input->post('search_string');
			// Check if new search string was submitted
			$newsearch = (isset($search_string)) ? TRUE : FALSE;
			// Give value of new search string, or search string stored in session if no new search string was submitted
			$search_string = ($newsearch) ? $this->input->post('search_string') : $this->session->userdata('search');

			// Store search string in session
			$this->session->set_userdata('search', $search_string);
			
			// Count necessary rows
			$isAdmin = ($this->session->userdata('permissions') == 'Admin') ? TRUE : FALSE;
			$user_depts = $this->session->userdata('departments');
			$total_rows = 0;
			if($isAdmin){
				// Get all assignments
				if(isset($search_string)){
					$total_rows = $this->db->like('assignments.title', $search_string)
					->or_like('assignments.notes', $search_string)
					->or_like('assignments.created_by', $search_string)
					->count_all_results('assignments');
				} else {
					$total_rows = $this->db->count_all_results('assignments');
				}
			} else {
				// Get all assignments in the users departments
				foreach($user_depts as $dep){
					$total_rows += $this->db->where('assignments.department_id', $dep['d_id'])->count_all_results('assignments');
				}
			}

			// Check $sort_by string is a field that exists in db
			$fields = array(
				'Opgavenavn' => 'title',
				'Notater' => 'notes',
				'Afdeling' => 'name',
				'Oprettet af' => 'username'
			);

			// Default to 'title'
			$sort_by = (in_array($sort_by, $fields)) ? $sort_by : 'title';

			// Pagination config
			$config['base_url'] = base_url("assignments/index/$per_page/$order_by/$sort_by");
			$config['total_rows'] = $total_rows;
			$config['per_page'] = (is_numeric($per_page)) ? $per_page : $total_rows;
			$config['uri_segment'] = 6;
			$config['first_link'] = 'Første';
			$config['last_link'] = 'Sidste';
			$config['attributes'] = array('class' => 'pagination-link');// btn btn-primary
			$this->pagination->initialize($config);

			// Data variables
			$data['title'] = 'Opgaveoversigt';
			$data['order_by'] = $order_by;
			$data['sort_by'] = $sort_by;
			$data['offset'] = $offset;
			$data['per_page'] = $per_page;
			$data['search_string'] = $search_string;
			$data['fields'] = $fields;
			
			// Only set if pagination is needed on the page
			$pagination['per_page'] = ($total_rows >= 5) ? $per_page : NULL;
			$pagination['offset'] = $offset;
			$pagination['total_rows'] = $total_rows;

			// Get assignments
			if($this->form_validation->run() === FALSE){
				// Get all assignments
				$data['asses'] = $this->assignment_model->get_asses($user_depts, $isAdmin, $config['per_page'], $offset, $sort_by, $order_by, $this->session->userdata('search'));
			}

			//Load the page
			$this->load->view('templates/header');
			$this->load->view('assignments/index', $data);
			$this->load->view('templates/footer', $pagination);
		}
		
		
		public function view($ass_id){
			// Check if anyone is logged in
			if(!$this->session->userdata('logged_in')){
				redirect('login');
			}
			
			$data['title'] = 'Opgave detaljer';
			$data['ass'] = $this->assignment_model->get_ass_view($ass_id);
			// Check the currently logged in users departments. 
			// Only users who are in the same department as the assignment can view it.
			$ismember = FALSE;
			for($i = 0; $i < count($this->session->userdata('departments')); $i++){	
				if($this->session->userdata['departments'][$i]['d_name'] == $data['ass']['department']){
					$ismember = TRUE;
					break;
				}
			}

			if($ismember || $this->session->userdata('permissions') == 'Admin'){
					// Allow the user to view the assignment details if they are a member of its department
					$data['events'] = $this->assignment_model->get_ass($ass_id);

					$this->load->view('templates/header');
					$this->load->view('assignments/view', $data);
					$this->load->view('templates/footer');
				} else {
					// Return the user to the overview page if they tried to access an assignment they are not allowed to access
					redirect('assignments/index/10/asc/title');
				}
		}


		public function delete($ass_id){
			// Check logged in
			if(!$this->session->userdata['logged_in']){
				redirect('login');
			}
			
			// Check if the logged in user is a member of the assignments department
			$ismember = FALSE;
			$data['ass'] = $this->assignment_model->get_ass_view($ass_id);
			for($i = 0; $i < count($this->session->userdata('departments')); $i++){	
				if($this->session->userdata['departments'][$i]['d_name'] == $data['ass']['department']){
					$ismember = TRUE;
					break;
				}
			}

			if($ismember || $this->session->userdata('permissions') == 'Admin'){
				$input = $this->input->post('input');
				// Check if the entered name matches the name in the DB
				if($input == $data['ass']['ass_title']){
					// Names match
					$this->event_assignment_model->remove_ass_all($ass_id);
					$this->assignment_model->delete_ass($ass_id);
					$this->session->set_flashdata('ass_delete_success','Opgave slettet');
					redirect('assignments/index/10/asc/title');
				} else {
					// Names don't match
					$this->session->set_flashdata('ass_delete_fail','Den indtastede titel matcher ikke opgavens titel!');
					redirect("assignments/view/$ass_id");
				}
			} else {
				// Redirect if the user is not a member
				$this->session->set_flashdata('ass_delete_fail', 'Du kan kun slette opgaver fra din egne afdelinger!');
				redirect('assignments/index/10/asc/title');
			}
		}

		// Create assignment and store in DB
		public function create($answerAmount = 1){
			// Check logged in
			if(!$this->session->userdata('logged_in')){
				redirect('login');
			}
			// Get inputted amount of answers
			$answerAmount = $this->input->post('answerAmount');
			
			// Set data variables
			$data['title'] = 'Opret opgave';
			$data['options'] = array(
				'optionsAmount' => (!empty($answerAmount)) ? $answerAmount : 1,
				'maxOptions' => 9
			);
			$data['departments'] = ($this->session->userdata('permissions') == 'Admin') ? $this->department_model->get_department() : $this->session->userdata('departments');
			$data['events'] = $this->event_model->get_event(NULL, $data['departments']);
			// Set form validation
			$this->form_validation->set_rules('title','"opgavenavn"','required');
			// Set validation rules for all generated 'answer' and 'points' fields
			for($i = 1; $i<= $data['options']['optionsAmount']; $i++){
				$this->form_validation->set_rules("answer$i",'"svar mulighed '.$i.'"','required');
				$this->form_validation->set_rules("points$i",'"point '.$i.'"','required|numeric');
			}
			
			if($this->form_validation->run() === FALSE){
				// If validation failed or didn't run
				$this->load->view('templates/header');
				$this->load->view('assignments/create', $data);
				$this->load->view('templates/footer');
			} else {
				// If validation was successful
				$e_id = $this->input->post('eventbox');
				if(isset($e_id)){
					$ass_id = $this->assignment_model->create_ass($data['options']['optionsAmount'], TRUE);
					$this->event_assignment_model->add_ass($e_id, $ass_id);
				} else {
					$this->assignment_model->create_ass($data['options']['optionsAmount']);
				}
				$this->session->set_flashdata('ass_created','Opgave oprettet!');
				redirect("assignments/index/10/asc/title");
			}
		}


		public function edit($ass_id, $optionsAmount = NULL){
			// Check if anyone is logged in
			if(!$this->session->userdata('logged_in')){
				redirect('login');
			}
			
			// Get inputted amount of answers
			$answerAmount = $this->input->post('answerAmount');

			// Set data variables
			$data['title'] = "Rediger opgave";
			$data['departments'] = ($this->session->userdata('permissions') == 'Admin') ? $this->department_model->get_department() : $this->session->userdata('departments');
			// Get info about assignment from DB
			$data['ass'] = $this->assignment_model->get_ass_view($ass_id);
			if($optionsAmount){
				// Set the amount of answers to the user specified amount
				$data['options'] = array(
					'optionsAmount' => (!empty($answerAmount)) ? $answerAmount : 1,
					'maxOptions' => 9
				);
			} else {
				// Get the amount of answers to the assignment from the DB
				// $data['options'] = $this->set_answer_amount(count($data['ass'][0]));
				$options['optionsAmount'] = count($data['ass'][0]);
				$data['options'] = $options;
			}

			$this->form_validation->set_rules('title','"opgave titel"','required');
			// Set validation rules for all generated 'answer' and 'points' fields
			for($i = 1; $i<= $data['options']['optionsAmount']; $i++){
				$this->form_validation->set_rules('answer'.$i,'"svar mulighed '.$i.'"','required');
				$this->form_validation->set_rules('points'.$i,'"point '.$i.'"','required|numeric');
			}

			if($this->form_validation->run() === FALSE){
				// If validation failed or didn't run
				$this->load->view('templates/header');
				$this->load->view('assignments/edit', $data);
				$this->load->view('templates/footer');
			} else {
				// If validation succeeded
				if($this->input->post('d_id') != $data['ass']['d_id']){
					// Remove assignment from all events if the department was changed
					$this->event_assignment_model->remove_ass_all($data['ass']['ass_id']);
				}
				$this->assignment_model->edit_ass($ass_id, $data['options']['optionsAmount']);
				$this->session->set_flashdata('ass_edited','Opgaven er blevet redigeret');
				redirect("assignments/view/$ass_id");
			}
		}
	}