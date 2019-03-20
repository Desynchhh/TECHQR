<?php
    class Departments extends CI_Controller{
        public function index($offset = 0){
            //Check the user is admin
            if($this->session->userdata('permissions') != 'Admin'){
                redirect('home');
            }

            //Pagination configuration
            $config['base_url'] = base_url().'departments/index/';
            $config['total_rows'] = $this->db->count_all_results('departments');
            $config['per_page'] = 10;
            $config['uri_segment'] = 3;
            $config['attributes'] = array('class' => 'pagination-link');
            $this->pagination->initialize($config);

            //Set data variables
            $data['title'] = 'Afdelings oversigt';
            $data['departments'] = $this->department_model->get_department(NULL, $config['per_page'], $offset);

            //Load the page
            $this->load->view('templates/header');
            $this->load->view('departments/index', $data);
            $this->load->view('templates/footer');
        }

        public function create(){
            if($this->session->userdata('permissions') != 'Admin'){
                redirect('home');
            }

            $data['title'] = 'Opret afdeling';

            $this->form_validation->set_rules('name','"Afdelingsnavn"','required|callback_check_department_exists');

            if($this->form_validation->run() === FALSE){
                $this->load->view('templates/header');
                $this->load->view('departments/create', $data);
                $this->load->view('templates/footer');
            } else {
                $this->department_model->create_department();
                $this->session->set_flashdata('department_created',$this->input->post('name').' er succesfuldt oprettet!');
                redirect('departments');
            }
        }

        //Open a page with details about a department
        public function view($d_id, $offset = 0){
            //Check a user is logged in
            if($this->session->userdata('permissions') != 'Admin'){
                redirect('home');
            }

            //Pagination configuration
            $config['base_url'] = base_url() . 'departments/view/'.$d_id;
            $config['total_rows'] = $this->db->where('user_departments.department_id', $d_id)->count_all_results('user_departments');
            $config['per_page'] = 10;
            $config['uri_segment'] = 4;
            $config['attributes'] = array('class' => 'pagination-link');
            $this->pagination->initialize($config);
            
            //Set data variables
            $data['title'] = 'Afdelings detajler';
            $data['department'] = $this->department_model->get_department($d_id);
            $data['users'] = $this->user_department_model->get_department_members($d_id, $config['per_page'], $offset);
            
            //Load the page
            $this->load->view('templates/header');
            $this->load->view('departments/view', $data);
            $this->load->view('templates/footer');
        }

        //Remove a user from the department
        public function remove($u_id, $d_id){
            if($this->session->userdata('permissions') != 'Admin'){
                redirect('home');
            }

            if(count($this->user_department_model->get_user_departments($u_id)) <= 1){
                //If the user only has 1 department
                $this->session->set_flashdata('department_user_remove_fail','Brugere skal have minimum én afdeling');
            } else {
                $this->session->set_flashdata('department_user_remove_success','Brugeren er blevet fjernet fra afdelingen');
                $this->user_department_model->delete_user_from_department($u_id, $d_id);
            }
            redirect('departments/view/'.$d_id);
        }

        public function add($d_id, $u_id = NULL){
            if($this->session->userdata('permissions') != 'Admin'){
                redirect('home');
            }
            $this->form_validation->set_rules('u_id','"bruger"','required');
            if($u_id === NULL){
                $data['users'] = $this->user_department_model->get_department_not_members($d_id);
                for($i = 0; $i < count($data['users']); $i++){
                    if(!$this->user_department_model->is_already_member($data['users'][$i]['u_id'], $d_id)){
                        $temp[] = ($data['users'][$i]);
                    }
                }
                $data['users'] = $temp;
            
                $data['department'] = $this->department_model->get_department($d_id);
                $data['title'] = 'Tilføj bruger til <b>'.$data['department']['name'].'</b>';

                //Load page
                $this->load->view('templates/header');
                $this->load->view('departments/add', $data);
                $this->load->view('templates/footer');
            } else {
                $this->user_department_model->assign_user_to_department($u_id, $d_id);
                $this->session->set_flashdata('department_user_added','Brugeren er blevet tilføjet til '.$data['department']['name']);
                $this->view($d_id);
            }
        }

        //It's called edit, but it is only used to rename departments
        public function edit($id = NULL){
            if($this->session->userdata('permissions') != 'Admin'){
                redirect('home');
            }

            if($id === NULL){
                //Modify any department and its members
            } else {
                //Edit specific department
                $data['title'] = 'Rediger afdeling';
                $data['department'] = $this->department_model->get_department($id);

                $this->form_validation->set_rules('name','"afdelingsnavn"','required|callback_check_department_exists');

                if($this->form_validation->run() === FALSE){
                    $this->load->view('templates/header');
                    $this->load->view('departments/edit', $data);
                    $this->load->view('templates/footer');
                } else {
                    $this->department_model->edit_department($id);
                    $this->session->set_flashdata('department_edited','Afdeling opdateret');
                    redirect('departments');
                }
            }
        }

        public function confirm_delete($d_id){
            if(!$this->session->userdata('logged_in')){
                if($this->session->userdata('permissions') != 'Admin'){
                    redirect('users/view/'.$this->session->userdata('u_id'));
                }
                redirect('login');
            }
            
            $data['title'] = "SLET AFDELING?";
            $data['department'] = $this->department_model->get_department($d_id);

            $this->form_validation->set_rules('department','"afdelingsnavn"','required');

            if($this->form_validation->run() === FALSE || $this->input->post('department') != $data['department']['name']){
                $this->load->view('templates/header');
                $this->load->view('departments/confirm_delete', $data);
                $this->load->view('templates/footer');
            } else {
                $this->delete($d_id);
            }
        }

        public function delete($id){
            if($this->session->userdata('permissions') != 'Admin'){
                redirect('home');
            }
            
            //Check if the department has users who only have 1 department
            //DO NOT DELETE DEPARTMENT IF THIS IS TRUE
            $dep_users = $this->user_department_model->get_department_members($id);
            foreach($dep_users as $user){
                if(count($this->user_department_model->get_user_departments($user['u_id'])) <= 1){
                    //Denne afdeling har en eller flere brugere som kun har en afdeling. En bruger skal som minimum have én afdeling!
                    $this->session->set_flashdata('department_delete_fail','Afdelingen kunne ikke slettes, da <strong>'.$user['username'].'</strong> kun har 1 afdeling');
                    redirect('departments/view/'.$id);
                }
            }

            $this->assignment_model->delete_department_ass($id);
            $this->event_model->delete_department_event($id);
            $this->department_model->delete_department($id);
            $this->session->set_flashdata('department_delete_success','Afdeling slettet.');
            redirect('departments');
        }

        //CUSTOM VALIDATION RULES BELOW THIS POINT
        function check_department_exists($d_name){
            $this->form_validation->set_message('check_department_exists','Der findes allerede en afdeling med det navn');
            if($this->department_model->check_department_exists($d_name)){
                return false;
            } else {
                return true;
            }
        }
    }



