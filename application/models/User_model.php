<?php
    class User_model extends CI_Model{
        public function __construct(){
            $this->load->database();
        }

        public function create_user($enc_pass){
            $data = array(
                'department_id' => $this->input->post('department_id'),
                'username' => $this->input->post('username'),
                'password' => $enc_pass,
                'email' => $this->input->post('email'),
                'permissions' => $this->input->post('permissions')
            );
            return $this->db->insert('users', $data);
        }

        public function get_user($id = NULL){
            if($id === NULL){
                //Get all users
                /*$this->db->join('departments','departments.id = users.department_id', 'full');
                $query = $this->db->get('users');
                return $query->result_array();
                $query = $this->db->get();
                return $query->result_array();*/
                $this->db->select('
                    users.id as user_id,
                    users.username,
                    users.email,
                    users.permissions,
                    departments.name')
                ->from('users')
                ->join('departments','departments.id = users.department_id');
                $query = $this->db->get();
                return $query->result_array();      
            } else {
                //Get specific user
                $this->db->select('
                users.id as user_id,
                users.username,
                users.email,
                users.permissions,
                departments.name')
                ->where('users.id', $id)
                ->from('users')
                ->join('departments','departments.id = users.department_id');
                $query = $this->db->get();
                return $query->row_array();
            }
        }

        public function edit_user($id){

        }

        public function delete_user($id){
            $this->db->where('id', $id);
            $this->db->delete('users');
            return true;
        }

        public function get_password($id){
            $this->db->select('users.password')
            ->from('users')
            ->where('id', $id);
            $query = $this->db->get();
            return $query->row(0)->password;
        }

        public function change_password($id, $password){
            $data = array(
                'password' => $password
            );
            $this->db->where('id', $id)
            ->update('users', $data);
            return true;
        }

        public function check_user_exists($username){
            $query = $this->db->get_where('users', array('username' => $username));
            if(empty($query->row_array())){
                return false;
            } else {
                return true;
            }
        }

        public function check_email_exists($email){
            $query = $this->db->get_where('users',array('email' => $email));
            if(empty($query->row_array())){
                return false;
            } else {
                return true;
            }
        }
    }