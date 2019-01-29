<?php
    class Users extends CI_Controller{
        public function index(){
            $data['title'] = 'Bruger oversigt';
            $data['users'] = $this->user_model->get_user();

            $this->load->view('templates/header');
            $this->load->view('users/index', $data);
            $this->load->view('templates/footer');
        }

        public function view($id = NULL){
            $data['title'] = 'Bruger detaljer';
            
            if($id === NULL){
                //No user given
                redirect('users');
            } else {   
                //Get user
                $data['user'] = $this->user_model->get_user($id);

                $this->load->view('templates/header');
                $this->load->view('users/view', $data);
                $this->load->view('templates/footer');
            }
        }

        public function login(){
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
            }
        }

        public function logout(){
            redirect('/');
        }

        public function register(){
            $data['title'] = 'Opret Bruger';
            $data['departments'] = $this->department_model->get_department();

            
            $this->form_validation->set_rules('username','"brugernavn"','required|callback_check_space|callback_check_user_exists');
            $this->form_validation->set_rules('email','"email"','required|valid_email|callback_check_email_exists');
            $this->form_validation->set_rules('password','"kodeord"','required|callback_check_space');
            $this->form_validation->set_rules('password2','"bekræft kodeord"','matches[password]');

            if($this->form_validation->run() === FALSE)
            {
                $this->load->view('templates/header');
                $this->load->view('users/register', $data);
                $this->load->view('templates/footer');
            } else {
                $enc_pass = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
                $this->user_model->create_user($enc_pass);
                $this->session->set_flashdata('user_created','Brugeren '.$this->input->post('username').' er succesfuldt oprettet!');
                redirect('users');
            }
        }

        public function edit($id){
            $data['title'] = 'Rediger bruger';
            $data['user'] = $this->user_model->get_user($id);
            $data['departments'] = $this->department_model->get_department();

            $this->form_validation->set_rules('username','"brugernavn"','required|callback_check_space|callback_check_user_exists');
            $this->form_validation->set_rules('email','"email"','valid_email|callback_check_space|callback_check_email_exists');

            if($this->form_validation->run()=== FALSE){
                $this->load->view('templates/header');
                $this->load->view('users/edit', $data);
                $this->load->view('templates/footer');
            } else {
                redirect('users/view/'.$id);
            }
        }
        
        public function delete($id){
            $this->user_model->delete_user($id);
            $this->session->set_flashdata('user_deleted','Bruger slettet');
            redirect('users');
        }

        public function user_change_password(){
            $this->form_validation->set_rules('old_password','"gammelt kodeord"','required|callback_check_space');
            $this->form_validation->set_rules('new_password','"nyt kodeord"','required|callback_check_space');
            $this->form_validation->set_rules('new_password2','"bekræft kodeord"','matches[new_password]');
            
            if($this->input->post('new_password') === $this->input->post('new_password2')){
                $db_pass = $this->user_model->get_password($this->input->post('id'));
                if(password_verify($this->input->post('old_password'), $db_pass)){
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
            //This part is used when a new user is being registered OR when an existing user's email is changed
            //Ensure the new email address does not already exist within the DB
             if($this->user_model->check_email_exists($email)){
                return false;
            } else {
                return true;
            }
        }
    }