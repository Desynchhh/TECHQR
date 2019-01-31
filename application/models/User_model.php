<?php
    class User_model extends CI_Model{
        public function __construct(){
            $this->load->database();
        }

        public function create_user($enc_pass, $assign_department){
            //Insert the user data into the users table
            $data = array(
                'username' => $this->input->post('username'),
                'password' => $enc_pass,
                'email' => $this->input->post('email'),
                'permissions' => $this->input->post('permissions')
            );
            $this->db->insert('users', $data);

            if($assign_department){
                //Establish relation between user and department in user_departments table
                $user_id = $this->db->insert_id();
                $data = array(
                    'user_id' => $user_id,
                    'department_id' => $this->input->post('d_id')
                );
                $this->db->insert('user_departments', $data);
            }
        }

        public function get_user($id = NULL){
            if($id === NULL){
                //Get all users
                $this->db->select('
                    users.id as u_id,
                    users.username,
                    users.email,
                    users.permissions
                    ')
                ->from('users')
                ->order_by('users.username', 'ASC');
                $query = $this->db->get();
                return $query->result_array();
            } else {
                //Get specific user
                $this->db->select('
                users.id as u_id,
                users.username,
                users.email,
                users.permissions,
                users.created_at
                ')
                ->where('users.id', $id)
                ->from('users');
                $query = $this->db->get();
                return $query->row_array();
            }
        }

        public function edit_user($id){
            $data = array(
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'permissions' => $this->input->post('permissions')
                );
            $this->db->where('id',$id)
            ->update('users', $data);
            return true;
        }

        public function delete_user($id){
            $this->db->where('id', $id);
            $this->db->delete('users');

            $this->db->where('user_id', $id)
            ->delete('user_departments');
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