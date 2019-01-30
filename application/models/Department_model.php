<?php
    class Department_model extends CI_Model{
        
        //Constructor to access the database with the info in /config/database.php
        public function __construct(){
            $this->load->database();
        }

        public function create_department(){
            //create data array with relevant info
            $data = array(
                'name' => $this->input->post('name')
            );
            //send POST request to database
            return $this->db->insert('departments', $data);
        }

        public function edit_department($id = NULL){
            $data = array(
                'name' => $this->input->post('name')
            );
            $this->db->where('id', $id);
            $this->db->update('departments', $data);
            return true;
        }

        public function get_department($id = NULL){
            if($id == NULL){
                //Get all departments
                $this->db->order_by('name');
                $this->db->where('id >', '1');
                $query = $this->db->get('departments');
                return $query->result_array();
            } else {
                //Get specific department
                $query = $this->db->get_where('departments', array('id' => $id));
                return $query->row_array();
            }
        }

        public function delete_department($id){
            $this->db->where('id', $id);
            $this->db->delete('departments');

            $this->db->where('department_id', $id)
            ->delete('user_departments');
            return true;
        }
    }