<?php
    class Departments extends CI_Controller{
        public function index(){
            $data['title'] = 'Afdelings oversigt';
            $data['departments'] = $this->department_model->get_department();

            $this->load->view('templates/header');
            $this->load->view('departments/index', $data);
            $this->load->view('templates/footer');
        }

        public function create(){
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
        public function view($id = NULL){
            $data['title'] = 'Afdelings detajler';
            $data['department'] = $this->department_model->get_department($id);
            $data['users'] = $this->user_department_model->get_department_members($id);

            if($id === NULL){
                redirect('departments');
            } else {
                $this->load->view('templates/header');
                $this->load->view('departments/view', $data);
                $this->load->view('templates/footer');
            }
        }

        //Remove a user from the department
        public function remove($u_id, $d_id){
            $this->user_department_model->delete_user_from_department($u_id, $d_id);
            $this->view($d_id);
        }

        public function add($d_id){
            $data['department'] = $this->department_model->get_department($d_id);
            $data['title'] = 'Tilføj bruger til '.$data['department']['name'];
            $data['users'] = $this->user_department_model->get_department_not_members($d_id);

            $this->form_validation->set_rules('u_id','"bruger"','required');

            if($this->form_validation->run() === FALSE){
                $this->load->view('templates/header');
                $this->load->view('departments/add', $data);
                $this->load->view('templates/footer');
            } else {
                $this->user_department_model->assign_user_to_department($this->input->post('u_id'), $d_id);
                $this->session->set_flashdata('department_user_added',$this->input->post('u_id').' er blevet tilføjet til '.$data['department']['name']);
                $this->view($d_id);
            }
        }

        //It's called edit, but it is only used to rename departments
        public function edit($id = NULL){
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

        public function delete($id){
            $this->department_model->delete_department($id);
            $this->session->set_flashdata('department_deleted','Afdeling succesfuldt slettet.');
            redirect('departments');
        }

        //Custom form_validations rules below
        function check_department_exists($d_name){
            $this->form_validation->set_message('check_department_exists','Der findes allerede en afdeling med det navn');
            if($this->department_model->check_department_exists($d_name)){
                return false;
            } else {
                return true;
            }
        }
    }



