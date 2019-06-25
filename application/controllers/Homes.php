<?php
    class Homes extends CI_Controller{
        public function index(){
            $data['title'] = "Hjem";
            $data['error'] = '';

            $this->load->view('templates/header');
            $this->load->view('homes/index', $data);
            $this->load->view('templates/footer');
        }


        //Function to view the user manual PDF
        public function user_manual(){
            //User manual path
            $path = APPPATH.'../assets/user-manual/';
            
            //Get PDF path
            $filename = 'TECHQR-BRUGERMANUAL.pdf';
            $path_location = $path.$filename;
            //Set the HTML to be able to read/view a PDF file
            header('Content-type: application/pdf');
            header("Content-disposition: inline; filename=$filename");
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            //Open the file at the specified path
            @readfile($path_location);
        }


        //Funtion to upload and replace the old user manual PDF
        public function upload_manual(){
            //Check user is admin
            if($this->session->logged_in !== TRUE){
                //User is not logged in
                redirect("login");
            } else {
                if($this->session->userdata('permissions') !== 'Admin'){
                    //User is not an admin
                    redirect('home');
                }

            }

            //Set upload config
            $config['upload_path'] = APPPATH.'../assets/user-manual/';
            $config['allowed_types'] = 'pdf';
            //Forcefully renames uploaded file
            $config['file_name'] = "TECHQR-BRUGERMANUAL.pdf";
            //Ensure file extension (.pdf) is lower case
            $config['file_ext_tolower'] = TRUE;
            $config['overwrite'] = TRUE;
            //Load helper library
            $this->load->library('upload', $config);

            //Attempt upload
            if(!$this->upload->do_upload('fileUpload')){
                //Upload failed
                $data['title'] = 'Hjem';
                $data['error'] = $this->upload->display_errors();

                $this->session->set_flashdata('upload_failed', 'Brugermanual ikke opdateret');

                $this->load->view('templates/header');
                $this->load->view('homes/index', $data);
                $this->load->view('templates/footer');
            } else {
                //Upload success
                $this->session->set_flashdata('upload_success', 'Brugermanual opdateret!');
                redirect('home');
            }
        }
    }