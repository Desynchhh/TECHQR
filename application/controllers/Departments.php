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

            $this->form_validation->set_rules('name','"navn"','required');

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

        public function edit($id = NULL){
            if($id === NULL){
                //Modify any department and its members
            } else {
                //Edit specific department
                $data['title'] = 'Rediger afdeling';
                $data['department'] = $this->department_model->get_department($id);

                $this->form_validation->set_rules('name','"afdelingsnavn"','required');

                if($this->form_validation->run() === FALSE){
                    $this->load->view('templates/header');
                    $this->load->view('departments/edit', $data);
                    $this->load->view('templates/footer');
                } else {
                    redirect('departments');
                }
            }
        }

        public function delete($id){
            $department = $this->department_model->get_department($id);
            $this->department_model->delete_department($id);
            $this->session->set_flashdata('department_deleted',$department['name'].' succesfuldt slettet.');
            redirect('departments');
        }
    }