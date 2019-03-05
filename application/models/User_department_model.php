<?php
    class User_department_model extends CI_model{
        function __construct(){
            $this->load->database();
        }
        
        public function assign_user_to_department($u_id, $d_id){
            $data = array(
                'user_id' => $u_id,
                'department_id' => $d_id
                );
            return $this->db->insert('user_departments', $data);
        }

        //Get all departments of which the user is a member of
        public function get_user_departments($u_id){
            $this->db->select('
                departments.id as d_id,
                departments.name
            ')
            ->where('user_id', $u_id)
            ->join('departments','departments.id = user_departments.department_id')
            ->from('user_departments');
            $query = $this->db->get();
            return $query->result_array();
        }

        //Get all users in a department
        public function get_department_members($d_id){
            $query = $this->db->select('
                users.id as u_id,
                users.username,
                users.permissions,
                users.email,
            ')
            ->where('department_id', $d_id)
            ->join('users', 'users.id = user_departments.user_id')
            ->from('user_departments')
            ->order_by('users.username', 'ASC')
            ->get();
            return $query->result_array();
        }

        public function get_department_not_members($d_id){
            $query = $this->db->select('
                users.id as u_id,
                users.username,
                users.permissions,
                users.email,
                departments.name
            ')
            ->where('department_id !=', $d_id)
            ->join('users', 'users.id = user_departments.user_id')
            ->join('departments', 'departments.id = user_departments.department_id')
            ->from('user_departments')
            ->order_by('users.username', 'ASC')
            ->get();
            return $query->result_array();
        }

        public function delete_user_from_department($u_id, $d_id){
            $this->db->where('user_id', $u_id)
            ->where('department_id', $d_id)
            ->delete('user_departments');
            return true;
        }

        //Check if the user is already a member of the department they are being assigned to
        public function is_already_member($u_id, $d_id){
            $data = array(
                'user_id' => $u_id,
                'department_id' => $d_id
            );
            $result = $this->db->get_where('user_departments', $data);
            if(empty($result->row_array())){
                return false;
            } else {
                return true;
            }
        }
    }