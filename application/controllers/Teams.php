<?php
	class Teams extends CI_Controller{
		// Load 'no team' page. Run if a user scans a QR code without being on a team
		public function noteam(){
			$data['title'] = 'Ingen hold';

			$this->load->view('teams/noteam', $data);
			$this->load->view('templates/footer');
		}


		// Load team status screen
		public function status($e_id){
			// Check if user has the correct cookie
			if(isset($_COOKIE['TechQR'])){
				// Unserialize cookie so the data within can be read and used
				$cookie = unserialize($_COOKIE['TechQR']);
				if(!$this->team_model->check_cookie($cookie['s_id'])){
					// Student doesn't have a team
					$this->remove_cookie();
				}
				// Get team details from DB
				$team = $this->team_model->get_team($cookie['t_id']);
				// Set $data array
				$data['title'] = 'HOLD '.$cookie['t_num'].' - '.$cookie['e_name'];
				$data['message'] = $this->event_model->get_message($cookie['e_id']);
				$data['score'] = $team['t_score'];
				$data['action'] = $this->student_action_model->get_latest_assignment($e_id, $cookie['t_id']);
			} else {
				// Correct cookie not found
				redirect('teams/noteam');
			}

			// Load page
			$this->load->view('templates/header');
			$this->load->view('teams/status', $data);
			$this->load->view('templates/footer');
		}


		// Assign unique cookie
		public function join($e_id, $t_id){
			// Get team details from DB
			$team = $this->team_model->get_team($t_id);

			if(isset($team)){
				// Check if user is already on a team
				// Get which which member number the current student will be
				$team['s_id'] = $this->db->where('students.team_id', $t_id)->count_all_results('students');

				// Set cookie variables
				$name = 'TechQR';
				// Set time until cookie expiration in seconds
				//$expiretime = 300;					// 5 mins
				$expiretime = 60*60*24*7;	// 7 days
				// Set cookie expire date
				$expiredate = time()+round($expiretime);

				if(isset($_COOKIE['TechQR'])){
						// Update student entry in DB if they already are on a team
						$cookie = unserialize($_COOKIE['TechQR']);
						if($this->team_model->check_cookie($cookie['s_id'])){
								$this->team_model->update_student($cookie['t_id'], $cookie['s_id'], $team['t_id'], $expiredate);
								$team['s_id'] = $cookie['s_id'];
						}
				} else {
						// Create student in DB AND get their ID
						$team['s_id'] = $this->team_model->join_team($team['t_id'], $expiredate);
				}

				// Serialize the array so it can be cookie-fied
				$value = serialize($team);
				// Set cookie
				setcookie($name, $value, $expiredate, '/');
				// Create action
				$action = "En mobil blev tilføjet";
				$this->student_action_model->create_action($e_id, $team['t_id'], $action);

				// Load team status screen
				redirect("teams/status/$e_id");
			} else {
				// Team doesn't exist
				redirect('teams/noteam');
			}
		}


		// Remove cookie
		public function remove_cookie(){
			// Remove cookie by setting it again, but with an expiredate in the past
			$name = 'TechQR';
			$expiredate = time()-1;
			setcookie($name, '', $expiredate, '/');
			redirect('teams/noteam');
		}


		// Attempt to answer an assignment
		public function answer($e_id, $ass_id, $ans_id){
			if(isset($_COOKIE['TechQR'])){
				$cookie = unserialize($_COOKIE['TechQR']);
				if(!$this->team_model->check_cookie($cookie['s_id'])){
					// Students team was deleted
					$this->remove_cookie();
				}

				if(!$this->team_model->check_event($e_id, $cookie['t_id'])){
					// Team attempted to answer an assignment in a different event
					// Set message for team
					$this->session->set_flashdata('team_wrong_event','I kan ikke besvare opgaver fra andre events!');
					// Get team data
					$team = $this->team_model->get_team($cookie['t_id']);
					// Log action in DB
					$action = "Forsøgte at besvare en opgave fra et andet event";
					$this->student_action_model->create_action($team['e_id'], $cookie['t_id'], $action, $ass_id);
					// Go to team status/overview page
					redirect("teams/status/$team[e_id]/$cookie[t_id]");
				}

				if($this->team_model->check_already_answered($cookie['t_id'], $ass_id)){
					// Team has already answered the assignment
					// Log action in DB
					$action = "Forsøgte at besvare samme opgave mere end en gang";
					$this->student_action_model->create_action($e_id, $cookie['t_id'], $action, $ass_id);

					// Set message for team
					$this->session->set_flashdata('team_already_answered','I kan ikke besvare den samme opgave flere gange!');

					// Go to team status/overview page
					redirect("teams/status/$e_id/$cookie[t_id]");
				} else {
					// Get answer- and team points
					$answer = $this->assignment_model->get_ass_answers($ass_id, $ans_id);
					$team = $this->team_model->get_team($cookie['t_id']);
					$ans_points = $answer['points'];
					$team_points = $team['t_score'];

					// Update the teams score
					$score = $team_points + $ans_points;
					$this->team_model->update_score($cookie['t_id'], $score);

					// Update last answered
					$this->event_assignment_model->update_last_answered($e_id, $ass_id);

						// Log action
					$action = 'Svarede på opgave';
					$this->student_action_model->create_action($e_id, $cookie['t_id'], $action, $ass_id, $ans_id, $ans_points);

					// Redirect to team overview/status screen
					redirect("teams/status/$e_id/$cookie[t_id]");
				}
			} else {
				// Redirect to a 'no team' page
				redirect('teams/noteam');
			}
		}


		// Create teams for an event
		public function create($e_id){
			// Check a user is logged in
			if(!$this->session->userdata('logged_in')){
				redirect('login');
			}

			// Set form validation rules
			$this->form_validation->set_rules('teams','"antal hold"','required|numeric');

			if($this->form_validation->run() === FALSE){
				// If validation failed OR did not run
				// Reload page
				redirect("teams/view/$e_id");
			} else {
				// Validation successful
				// Check if event already has an amount of teams
				$teams = $this->team_model->get_teams($e_id);
				if(empty($teams)){
						// Event has no teams. Create the first team
						$next_team_number = 1;
				} else {
						// Event has teams. Increase the team count from the newest team
						$next_team_number = end($teams)['t_num']+1;
				}
				// Create the requested amount of teams
				for($i = 0; $i < $this->input->post('teams'); $i++){
						$this->team_model->create_team($e_id, $next_team_number);
						$next_team_number++;
				}
				// Set message to inform the user of the successful creation of teams
				$this->session->set_flashdata('team_created','Hold oprettet');
				// Reload the page
				redirect("teams/view/$e_id/10/asc/number");
			}
		}


		// List all created teams in an event
		public function view($e_id, $per_page = 10, $order_by = 'asc', $sort_by = 'number', $offset = 0){
			// Check user is logged in
			if(!$this->session->userdata('logged_in')){
				redirect('login');
			}

			// Pagination configuration
			$config['base_url'] = base_url("teams/view/$e_id/$per_page/$order_by/$sort_by");
			$config['total_rows'] = $this->db->where('teams.event_id', $e_id)->count_all_results('teams');
			$config['per_page'] = (is_numeric($per_page)) ? $per_page : $config['total_rows'];
			$config['uri_segment'] = 7;
			$config['attributes'] = array('class' => 'pagination-link');
			$config['first_link'] = 'Første';
			$config['last_link'] = 'Sidste';
			$this->pagination->initialize($config);

			// Delete students where cookie has expired
			$this->team_model->check_expire_date();

			// Set data variables
			$event = $this->event_model->get_event($e_id);
			$data['teams'] = $this->team_model->get_teams($e_id, $config['per_page'], $offset, $sort_by, $order_by);
			$data['title'] = "Hold oversigt - ".$event['e_name'];
			// Get latest action for each team
			foreach($data['teams'] as $team){
				$data['action'][] = $this->student_action_model->get_latest_action($team['t_id']);
			}
			$data['offset'] = $offset+(($offset+1) % $config['per_page']);
			$data['order_by'] = ($order_by == 'desc') ? 'asc' : 'desc';
			$data['e_id'] = $e_id;
			$data['event_asses'] = $this->db->where('event_id', $e_id)->count_all_results('event_assignments');
			// Get amount of assignments each team has answered
			foreach($data['teams'] as $team){
				$data['team_ans'][] = $this->team_model->get_team_answers($e_id, $team['t_id']);
			}
			// Used to calculate the array index difference between 'teams' and 'student_array'
			$data['pagination_offset'] = $offset;
			$data['per_page'] = $per_page;
			$pagination['per_page'] = ($config['total_rows'] >= 5) ? $per_page : NULL;
			$pagination['offset'] = $offset;
			$pagination['total_rows'] = $config['total_rows'];
			$pagination['id'] = $e_id;

			// Get the total amount of members per team
			$student_array = array();
			foreach($data['teams'] as $team){
				$data['students'][] = $this->db->where('students.team_id', $team['t_id'])->count_all_results('students');
			}

			// Load the page
			$this->load->view('templates/header');
			$this->load->view('teams/view', $data);
			$this->load->view('templates/footer', $pagination);
		}


		//Delete all teams from an event
		public function delete($e_id, $t_id = NULL){
			if(!$this->session->userdata('logged_in')){
				redirect('login');
			}

			//Delete one or all teams from the event
			$this->team_model->delete_team($e_id, $t_id);
			//Set flashdata message
			$this->session->set_flashdata('teams_deleted', 'Alle hold slettet');
			//Reload page
			redirect("teams/view/$e_id/10/asc/number");
		}
	}