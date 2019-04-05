<?php
    class Department_model extends CI_Model{
        //Constructor to access the database with the info in /config/database.php
        public function __construct(){
            $this->load->database();
        }


        //Create new row in DB
        public function create_department(){
            //create data array with relevant info
            $data = array(
                'name' => $this->input->post('name')
            );
            //send POST request to database
            return $this->db->insert('departments', $data);
        }


        //Rename department
        public function edit_department($d_id = NULL){
            $data = array(
                'name' => $this->input->post('input')
            );
            $this->db->where('id', $d_id);
            $this->db->update('departments', $data);
            return true;
        }


        //Get one or all departments from DB
        public function get_department($d_id = NULL, $limit = FALSE, $offset = FALSE){
            if($d_id == NULL){
                if($limit){
                    $this->db->limit($limit, $offset);
                }
                //Get all departments
                $this->db->order_by('created_at');
                $query = $this->db->get('departments');
                return $query->result_array();
            } else {
                //Get specific department
                $query = $this->db->get_where('departments', array('id' => $d_id));
                return $query->row_array();
            }
        }


        //Delete department from DB
        public function delete_department($id){
            $this->db->where('id', $id);
            $this->db->delete('departments');

            $this->db->where('department_id', $id)
            ->delete('user_departments');
            return true;
        }

        
        //Check entered department name already exists
        public function check_department_exists($d_name){
            $result = $this->db->get_where('departments', array('name' => $d_name));
            if(empty($result->row_array())){
                //Department exist
                return true;
            } else {
                //Department doesn't exists
                return false;
            }
        }
    }