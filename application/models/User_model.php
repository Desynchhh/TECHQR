<?php
    class User_model extends CI_Model{
        public function __construct(){
            $this->load->database();
        }

        public function create_user($enc_pass){
            //Insert the user data into the users table
            $data = array(
                'username' => $this->input->post('username'),
                'password' => $enc_pass,
                'email' => $this->input->post('email'),
                'permissions' => $this->input->post('permissions')
            );
            $this->db->insert('users', $data);
            
            //Establish relation between user and department in user_departments table
            $user_id = $this->db->insert_id();
            $data = array(
                'user_id' => $user_id,
                'department_id' => $this->input->post('d_id')
            );
            $this->db->insert('user_departments', $data);
        }

        public function get_user($u_id = NULL, $limit = FALSE, $offset = FALSE, $sort_by = 'username', $order_by = 'DESC'){
            if($u_id === NULL){
                if($limit){
                    $this->db->limit($limit, $offset);
                }
                //Get all users
                $this->db->select('
                    users.id as u_id,
                    users.username,
                    users.email,
                    users.permissions
                ')
                ->order_by($sort_by, $order_by)
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
                ->where('users.id', $u_id)
                ->from('users');
                $query = $this->db->get();
                return $query->row_array();
            }
        }

        public function edit_user($u_id){
            $data = array(
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'permissions' => $this->input->post('permissions')
                );
            $this->db->where('id',$u_id)
            ->update('users', $data);
            //return true;
        }

        public function delete_user($u_id){
            $this->db->where('id', $u_id);
            $this->db->delete('users');

            $this->db->where('user_id', $u_id)
            ->delete('user_departments');
            //return true;
        }

        public function get_password(){
            $this->db->select('users.password')
            ->from('users')
            ->where('username', $this->input->post('username'));
            $query = $this->db->get();
            return $query->row(0)->password;
        }

        public function change_password($u_id, $password){
            $data = array(
                'password' => $password
            );
            $this->db->where('id', $u_id)
            ->update('users', $data);
            //return true;
        }

        public function login($username){
            $query = $this->db->select('
                id,
                permissions
            ')
            ->from('users')
            ->where('username', $username)
            ->get();
            if($query->num_rows() == 1){
                return $query->row_array();
            } else {
                return FALSE;
            }
        }

        public function check_user_exists($username){
            $query = $this->db->get_where('users', array('username' => $username));
            if(empty($query->row_array())){
                return FALSE;
            } else {
                return TRUE;
            }
        }

        public function check_email_exists($email){
            $query = $this->db->get_where('users',array('email' => $email));
            if(empty($query->row_array())){
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }