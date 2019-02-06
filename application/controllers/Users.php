<?php
    class Users extends CI_Controller{
        public function index(){
            if($this->session->userdata('permissions')!='Admin'){
                redirect('home');
            }

            $data['title'] = 'Bruger oversigt';
            $data['users'] = $this->user_model->get_user();

            $this->load->view('templates/header');
            $this->load->view('users/index', $data);
            $this->load->view('templates/footer');
        }

        public function view($id = NULL){
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }
            
            if($id === NULL){
                //No user given
                redirect('users');
            } else {
                //Get user
                if($this->session->userdata('permissions') != 'Admin'){
                    $id = $this->session->userdata('u_id');
                }
                $data['title'] = 'Bruger detaljer';
                $data['user'] = $this->user_model->get_user($id);
                $data['departments'] = $this->user_department_model->get_user_departments($id);

                $this->load->view('templates/header');
                $this->load->view('users/view', $data);
                $this->load->view('templates/footer');
            }
        }

        public function login(){
            if($this->session->userdata('logged_in')){
                redirect('users/logout');
            }

            $data['title'] = "Log ind";

            $this->form_validation->set_rules('username','"brugernavn"','required');
            $this->form_validation->set_rules('password','"kodeord"','required');

            if($this->form_validation->run() === FALSE)
            {
                $this->load->view('templates/header');
                $this->load->view('users/login', $data);
                $this->load->view('templates/footer');
            } else {
                //Attempt login
                $username = $this->input->post('username');
                if(password_verify($this->input->post('password'), $this->user_model->get_password())){
                    //Password and username matches with the DB
                    $dbinfo = $this->user_model->login($username);
                    $departments = $this->user_department_model->get_user_departments($dbinfo['id']);
                    $userdata = array(
                        'u_id' => $dbinfo['id'],
                        'username' => $username,
                        'permissions' => $dbinfo['permissions'],
                        'departments' => $departments,
                        'logged_in' => true
                    );
                    $this->session->set_userdata($userdata);
                    redirect('events');
                } else {
                    //Login details does not match with the DB
                }
            }
        }

        public function logout(){
            if(!$this->session->userdata('logged_in')){
                redirect('login');
            }

            $this->session->unset_userdata('u_id');
            $this->session->unset_userdata('username');
            $this->session->unset_userdata('permissions');
            $this->session->unset_userdata('departments');
            $this->session->unset_userdata('logged_in');
            redirect('users/login');
        }

        public function register(){
            if(!$this->session->userdata('logged_in')){
                if($this->session->userdata('permissions') != 'Admin'){
                    redirect('home');
                }
                redirect('login');
            }

            $data['title'] = 'Opret Bruger';
            $data['departments'] = $this->department_model->get_department();

            
            $this->form_validation->set_rules('username','"brugernavn"','required|callback_check_space|callback_check_user_exists');
            $this->form_validation->set_rules('email','"email"','required|valid_email|callback_check_email_exists');
            $this->form_validation->set_rules('password','"kodeord"','required|callback_check_space');
            $this->form_validation->set_rules('password2','"bekræft kodeord"','matches[password]');
            $this->form_validation->set_rules('permissions','"bruger type"','required');
            $this->form_validation->set_rules('d_id','"tildel afdeling"','required');

            if($this->form_validation->run() === FALSE)
            {
                $this->load->view('templates/header');
                $this->load->view('users/register', $data);
                $this->load->view('templates/footer');
            } else {
                $assign_department = FALSE;
                if(!empty($this->input->post('d_id'))){
                    $assign_department = TRUE;
                }
                $enc_pass = $this->hash_password($this->input->post('password'));
                $this->user_model->create_user($enc_pass, $assign_department);
                $this->session->set_flashdata('user_created','Brugeren '.$this->input->post('username').' er succesfuldt oprettet!');
                redirect('users');
            }
        }

        public function edit($id){
            if(!$this->session->userdata('logged_in')){
                if($this->session->userdata('permissions') != 'Admin'){
                    redirect('home');
                }
                redirect('login');
            }

            $data['title'] = 'Rediger bruger';
            $data['user'] = $this->user_model->get_user($id);
            $data['departments'] = $this->department_model->get_department();
            $newpass = FALSE;

            $this->form_validation->set_rules('username','"brugernavn"','required|callback_check_space|callback_check_user_exists');
            $this->form_validation->set_rules('email','"email"','valid_email|callback_check_space|callback_check_email_exists');
            if(!empty($this->input->post('password'))){
                $this->form_validation->set_rules('password','"nyt kodeord"','required');
                $this->form_validation->set_rules('password2','"bekræft kodeord"','matches[password]|callback_check_space');
                $newpass = TRUE;
            }

            if($this->form_validation->run() === FALSE){
                $this->load->view('templates/header');
                $this->load->view('users/edit', $data);
                $this->load->view('templates/footer');
            } else {
                $this->user_model->edit_user($id);
                if($newpass){
                    $enc_pass = $this->hash_password($this->input->post('password'));
                    $this->user_model->change_password($id, $enc_pass);
                }
                if(!empty($this->input->post('d_id'))){
                    
                    if(!$this->user_department_model->is_already_member($this->input->post('u_id'), $this->input->post('d_id'))){
                        $this->user_department_model->assign_user_to_department($this->input->post('u_id'), $this->input->post('d_id'));
                    }
                }
                $this->session->set_flashdata('user_edited','Brugeren blev succesfuldt opdateret');
                redirect('users/view/'.$id);
            }
        }
        
        public function confirm_delete($u_id){
            if(!$this->session->userdata('logged_in')){
                if($this->session->userdata('permissions') != 'Admin'){
                    redirect('users/view/'.$this->session->userdata('u_id'));
                }
                redirect('login');
            }
            
            $data['title'] = "SLET BRUGER?";
            $data['user'] = $this->user_model->get_user($u_id);

            $this->load->view('templates/header');
            $this->load->view('users/confirm_delete', $data);
            $this->load->view('templates/footer');
        }

        public function delete($id){
            if(!$this->session->userdata('logged_in')){
                if($this->session->userdata('permissions') != 'Admin'){
                    redirect('home');
                }
                redirect('login');
            }

            $this->user_model->delete_user($id);
            $this->session->set_flashdata('user_deleted','Bruger slettet');
            redirect('users');
        }

        public function change_password(){
            $this->form_validation->set_rules('old_password','"nuværende kodeord"','required|callback_check_space');
            $this->form_validation->set_rules('new_password','"nyt kodeord"','required|callback_check_space');
            $this->form_validation->set_rules('new_password2','"bekræft kodeord"','matches[new_password]');
            if($this->input->post('new_password') === $this->input->post('new_password2') && !empty($this->input->post('new_password'))){
                if(password_verify($this->input->post('old_password'), $this->user_model->get_password())){
                    //Old password matches
                    $enc_pass = password_hash($this->input->post('new_password'), PASSWORD_DEFAULT);
                    $this->user_model->change_password($this->input->post('id'), $enc_pass);
                    $this->session->set_flashdata('password_changed','Du har nu ændret dit kodeord');
                } else {
                    //Old password does not match
                    $this->session->set_flashdata('old_password_mismatch','Det indtastede gamle kodeord passer ikke med det i systemet');
                }
            } else {
                $this->session->set_flashdata('new_password_mismatch','De indtastede nye kodeord passer ikke med hindanden');
            }
            redirect('users/view/'.$this->input->post('id'));
        }


        //Hash password function
        private function hash_password($password){
            return password_hash($password, PASSWORD_DEFAULT);
        }

        //CUSTOM VALIDATION RULES BELOW THIS POINT
        function check_space($str){
            $this->form_validation->set_message('check_space','No field can contain a space');

            if(strpos($str, ' ') !== FALSE){
                //If the string does not contain a space
                return false;
            } else {
                //If the string contains a space
                return true;
            }
        }

        function check_user_exists($username){
            $this->form_validation->set_message('check_user_exists','Det brugernavn findes allerede i systemet.');
            //This part is used when editing an existing user
            //If the "new" username address is still the same as the old one, it'll pass
            if($this->input->post('old_username')){
                if($this->input->post('old_username') === $username){
                    return true;
                }
            }
            //This part is used when a new user is being registered OR when an existing user's username is changed
            //Ensure the new email address does not already exist within the DB
             if($this->user_model->check_user_exists($username)){
                 return false;
             } else {
                 return true;
             }
        }

        function check_email_exists($email){
            $this->form_validation->set_message('check_email_exists','Den email findes allerede i systemet.');
            //This part is used when an existing user is being edited
            //If the "new" email address is still the same as the old one, it'll pass
            if($this->input->post('old_email')){
                if($this->input->post('old_email') === $email){
                    return true;
                }
            }
            //This part is used when a new user is being registered OR when an existing user's email is æchanged
            //Ensure the new email address does not already exist within the DB
             if($this->user_model->check_email_exists($email)){
                return false;
            } else {
                return true;
            }
        }
    }