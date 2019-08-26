<?php
	class Departments extends CI_Controller{
		public function index($offset = 0){
			//Check the user is admin
			if($this->session->userdata('permissions') != 'Admin'){
					redirect('home');
			}

			//Pagination configuration
			$config['base_url'] = base_url('departments/index/');
			$config['total_rows'] = $this->db->count_all_results('departments');
			$config['per_page'] = 10;
			$config['uri_segment'] = 3;
			$config['attributes'] = array('class' => 'pagination-link');
			$config['first_link'] = 'Første';
			$config['last_link'] = 'Sidste';
			$this->pagination->initialize($config);

			//Set data variables
			$data['title'] = 'Afdelingsoversigt';
			$data['departments'] = $this->department_model->get_department(NULL, $config['per_page'], $offset);

			//Load the page
			$this->load->view('templates/header');
			$this->load->view('departments/index', $data);
			$this->load->view('templates/footer');
		}

        
		public function create(){
			//Check admin
			if($this->session->userdata('permissions') != 'Admin'){
				redirect('home');
			}

			//Set validation rules
			$this->form_validation->set_rules('name','"Afdelingsnavn"','required|callback_check_department_exists');
			
			if($this->form_validation->run() === FALSE){
				//Validation failed or didn't run
				$data['title'] = 'Opret afdeling';

				//Load page
				$this->load->view('templates/header');
				$this->load->view('departments/create', $data);
				$this->load->view('templates/footer');
			} else {
				//Valdidation succeeded
				$this->department_model->create_department();
				$this->session->set_flashdata('department_created', $this->input->post('name').' er succesfuldt oprettet!');
				redirect('departments/index');
			}
		}


		//Open a page with details about a department
		public function view($d_id, $offset = 0){
			//Check user is logged in and is admin
			if($this->session->userdata('permissions') != 'Admin'){
					redirect('home');
			}

			//Pagination configuration
			$config['base_url'] = base_url('departments/view/'.$d_id.'/');
			$config['total_rows'] = $this->db->where('user_departments.department_id', $d_id)->count_all_results('user_departments');
			$config['per_page'] = 10;
			$config['uri_segment'] = 4;
			$config['attributes'] = array('class' => 'pagination-link');
			$config['first_link'] = 'Første';
			$config['last_link'] = 'Sidste';
			$this->pagination->initialize($config);
				

			$user_info = $this->user_department_model->get_department_members($d_id, $config['per_page'], $offset);
			//Set data variables
			$data['title'] = 'Afdelings detajler';
			$data['department'] = $this->department_model->get_department($d_id);
			$data['users'] = $user_info['result_array'];
			$data['member_count'] = $user_info['num_rows'];
			$data['fields'] = array(
					'Brugernavn' => 'username',
					'Type' => 'permissions',
					'Email' => 'email'
			);
				
			//Load the page
			$this->load->view('templates/header');
			$this->load->view('departments/view', $data);
			$this->load->view('templates/footer');
		}


		//Remove a user from the department
		public function remove($u_id, $d_id){
			//Check user is logged in and is admin
			if($this->session->userdata('permissions') != 'Admin'){
				redirect('home');
		}

			if(count($this->user_department_model->get_user_departments($u_id)) <= 1){
				//If the user ONLY has 1 department
				$this->session->set_flashdata('department_user_remove_fail','Brugere skal have minimum én afdeling');
			} else {
				//User has MORE than 1 department
				$this->session->set_flashdata('department_user_remove_success','Brugeren er blevet fjernet fra afdelingen');
				$this->user_department_model->remove_user_from_department($u_id, $d_id);
			}
			redirect("departments/view/$d_id");
		}


		public function add($d_id, $per_page = 10, $offset = 0, $u_id = NULL){
			//Check the user is admin
			if($this->session->userdata('permissions') != 'Admin'){
				redirect('home');
			}
				
			//Check if a user has been selected to be added
			if($u_id === NULL){
				//Dont add a user. List all users NOT in the specified department

				//Find and sort users not in the specified department
				$temp = array();
				$users = $this->user_department_model->get_department_not_members($d_id, $per_page, $offset);//, $config['per_page'], $offset
				for($i = 0; $i < count($users); $i++){
					if(!$this->user_department_model->is_already_member($users[$i]['u_id'], $d_id)){
						$temp[] = ($users[$i]);
					}
				}
				//Add found users to data variable
				$data['users'] = $temp;
						
				//Pagination config
				$config['base_url'] = base_url("departments/add/$d_id/$per_page");
				$config['total_rows'] = $this->db->where('user_departments.department_id !=', $d_id)->count_all_results('user_departments');
				$config['per_page'] = (is_numeric($per_page)) ? $per_page : $config['total_rows'];
				$config['uri_segment'] = 5;
				$config['attributes'] = array('class' => 'pagination-link');
				$config['first_link'] = 'Første';
				$config['last_link'] = 'Sidste';
				$this->pagination->initialize($config);
				
				//Set data variables
				$data['department'] = $this->department_model->get_department($d_id);
				$data['title'] = 'Tilføj bruger til <b>'.$data['department']['d_name'].'</b>';
				$data['per_page'] = $per_page;
				$pagination['per_page'] = ($config['total_rows'] >= 5) ? $per_page : NULL;
				$pagination['offset'] = $offset;
				$pagination['total_rows'] = $config['total_rows'];
				$pagination['id'] = $d_id;

				//Load page
				$this->load->view('templates/header');
				$this->load->view('departments/add', $data);
				$this->load->view('templates/footer', $pagination);
			} else {
				//User has been selected. Add them to the department
				$this->user_department_model->assign_user_to_department($u_id, $d_id);
				$this->session->set_flashdata('department_user_added','Brugeren er blevet tilføjet til '.$data['department']['d_name']);
				//Reload department details page
				redirect("departments/view/$d_id");
			}
		}


		//It's called edit, but it is only used to rename departments
		public function edit($d_id){
			//Check admin
			if($this->session->userdata('permissions') != 'Admin'){
				redirect('home');
			}
				
			//$department = $this->department_model->get_department($d_id);
			$input = $this->input->post('input');
			if($this->check_department_exists($input)){
				//Department does not exist
				$this->department_model->edit_department($d_id);
				$this->session->set_flashdata('department_edit_success', 'Afdelingen er blevet omdøbt');
			} else {
				//Department exists
				$this->session->set_flashdata('department_edit_fail', 'Der findes allerede en afdeling med det navn!');
			}
			//Reload page
			redirect('departments/index');
		}


		//Delete department from the system
		public function delete($d_id){
			//Check admin
			if($this->session->userdata('permissions') != 'Admin'){
				redirect('home');
			}
				
			//Check if the department has users who only have 1 department
			//DO NOT DELETE DEPARTMENT IF THIS IS TRUE
			$dep_users = $this->user_department_model->get_department_members($d_id);
			foreach($dep_users['result_array'] as $user){
				if(count($this->user_department_model->get_user_departments($user['u_id'])) <= 1){
					//One or more users only has 1 department
					$this->session->set_flashdata('department_delete_fail','Afdelingen kunne ikke slettes, da <strong>'.$user['username'].'</strong> kun har 1 afdeling');
					redirect("departments/view/$d_id");
				}
			}

			//Check inputted name is the same as department name
			$department = $this->department_model->get_department($d_id);
			$input = $this->input->post('input');
			if($input === $department['d_name']){
				//Input name matches
				$this->assignment_model->delete_department_ass($d_id);
				$this->event_model->delete_department_event($d_id);
				$this->department_model->delete_department($d_id);
				$this->session->set_flashdata('department_delete_success','Afdeling slettet.');
				redirect('departments/index');
			} else {
				//Input name doesn't match
				$this->session->set_flashdata('department_delete_fail','Indtastet navn matcher ikke!');
				redirect("departments/view/$d_id");
			}
		}


		//CUSTOM VALIDATION RULES BELOW THIS POINT
		function check_department_exists($d_name){
			$this->form_validation->set_message('check_department_exists','Der findes allerede en afdeling med det navn');
			return $this->department_model->check_department_exists($d_name);
		}
	}



