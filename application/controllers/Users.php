<?php
    class Users extends CI_Controller{
        public function index($offset = 0){
            if($this->session->userdata('permissions') != 'Admin'){
                redirect('home');
            }
            //Pagination config
            $config['base_url'] = base_url().'users/index/';
            $config['total_rows'] = $this->db->count_all_results('users');
            $config['per_page'] = 10;
            $config['uri_segment'] = 3;
            $config['attributes'] = array('class' => 'pagination-link');
            $config['first_link'] = 'Første';
            $config['last_link'] = 'Sidste';
            $this->pagination->initialize($config);

            $data['title'] = 'Bruger oversigt';
            $data['users'] = $this->user_model->get_user(NULL, $config['per_page'], $offset);

            //Load page
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
                //Set $data variables
                $data['title'] = 'Bruger detaljer';
                $data['user'] = $this->user_model->get_user($id);
                $data['departments'] = $this->user_department_model->get_user_departments($id);

                //Load page
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
                    $this->update_userdata($username);
                    $this->session->set_flashdata('user_login_success','Du er nu logget ind!');
                    redirect('home');
                } else {
                    //Login details does not match with the DB
                    $this->session->set_flashdata('user_login_fail','Den indtastede bruger findes ikke');
                    redirect('login');
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
                $enc_pass = $this->hash_password($this->input->post('password'));
                $this->user_model->create_user($enc_pass);
                $this->session->set_flashdata('user_created','Brugeren '.$this->input->post('username').' er blevet oprettet!');
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
                //Check if the username was changed
                if($this->input->post('username') != $this->input->post('old_username') || $this->input->post('email') != $this->input->post('old_email')){
                    //Check if the user has created any assignments, and change its "created_by" field accordingly
                    if($this->assignment_model->check_created_by($this->input->post('old_username'))){
                        $this->assignment_model->update_created_by($this->input->post('username'), $this->input->post('old_username'));
                    }
                    //Check if the user has created any assignments, and change its "edited_by" field accordingly
                    if($this->assignment_model->check_edited_by($this->input->post('old_username'))){
                        $this->assignment_model->update_edited_by($newname, $oldname);
                    }
                    //Update the sessions userdata with the new username, if an admin changed their own username
                    if($this->input->post('u_id') == $this->session->userdata('u_id')){
                        $this->session->userdata['username'] = $this->input->post('username');
                    }
                    //Update 'users' table in DB
                    $this->user_model->edit_user($id);
                }
                //Change password if validation for new password passed
                if($newpass){
                    $enc_pass = $this->hash_password($this->input->post('password'));
                    $this->user_model->change_password($id, $enc_pass);
                }
                //Assign a department to the user IF the department field is filled out (combo-box)
                if(!empty($this->input->post('d_id'))){
                    if(!$this->user_department_model->is_already_member($this->input->post('u_id'), $this->input->post('d_id'))){
                        $this->user_department_model->assign_user_to_department($this->input->post('u_id'), $this->input->post('d_id'));
                        if($this->input->post('u_id') == $this->session->userdata('u_id')){
                            $this->update_userdata($this->input->post('username'));
                        }
                    }
                }
                $this->session->set_flashdata('user_edited','Brugeren er blevet opdateret');
                redirect('users/view/'.$id);
            }
        }
        
        /*  DEPRECATED. don't forget to delete views/users/confirm_delete.php
        public function confirm_delete($u_id){
            if(!$this->session->userdata('logged_in')){
                if($this->session->userdata('permissions') != 'Admin'){
                    redirect('users/view/'.$this->session->userdata('u_id'));
                }
                redirect('login');
            }
            
            $data['title'] = "SLET BRUGER?";
            $data['user'] = $this->user_model->get_user($u_id);

            $this->form_validation->set_rules('username','"brugernavn"','required');

            if($this->form_validation->run() === FALSE || $this->input->post('username') != $data['user']['username']){
                $this->load->view('templates/header');
                $this->load->view('users/confirm_delete', $data);
                $this->load->view('templates/footer');
            } else {
                $this->delete($u_id);
            }
        }
        */

        function delete($u_id, $input_username){
            //Check user us logged in
            if(!$this->session->userdata('logged_in')){
                //Check user is admin
                if($this->session->userdata('permissions') != 'Admin'){
                    redirect('home');
                }
                redirect('login');
            }

            //Check the inputted username matches the one in the DB
            $user = $this->user_model->get_user($u_id);
            if($input_username == $user['username']){
                //Username matches
                $this->user_model->delete_user($u_id);
                $this->session->set_flashdata('user_delete_success','Bruger slettet');
                redirect('users');
            } else {
                //Username does not match
                $this->session->set_flashdata('user_delete_fail', 'Indtastet brugernavn matcher ikke!');
                redirect('users/view/'.$u_id);
            }

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

        //Update userdata function
        private function update_userdata($username){
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