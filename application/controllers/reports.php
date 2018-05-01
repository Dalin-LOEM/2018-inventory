<?php
/**
 * This controller serves the user management pages and tools.
 * @copyright  Copyright (c) 2014-2017 Benjamin BALET
 * @license    http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
 * @link       https://github.com/bbalet/skeleton
 * @since      0.1.0
 */

if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

/**
 * This controller serves the user management pages and tools.
 * The difference with HR Controller is that operations are technical (CRUD, etc.).
 */
class reports extends CI_Controller {
    public function __construct() {
        parent::__construct();
        log_message('debug', 'URI=' . $this->uri->uri_string());
        $this->session->set_userdata('last_page', $this->uri->uri_string());
        if($this->session->loggedIn === TRUE) {
           // Allowed methods
         if ($this->session->isAdmin || $this->session->isSuperAdmin) {
             //User management is reserved to admins and super admins
         } else {
           redirect('errors/privileges');
       }
   } else {
     redirect('connection/login');
 }
 $this->load->model('users_model');
}

    /**
     * Display the list of all users
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function index() {
        $this->load->helper('form');
        $data['users'] = $this->users_model->getUsersAndRoles();
        $data['title'] = 'Reports chart';
        $data['activeLink'] = 'others';
        $data['flashPartialView'] = $this->load->view('templates/flash', $data, TRUE);
        $this->load->view('templates/header', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('reports/charts', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * Display the form / action Create a new user
     * @author Benjamin BALET <benjamin.balet@gmail.com>
     */
    public function create() {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $data['title'] = 'Create a new user';
        $data['activeLink'] = 'users';
        $data['roles'] = $this->users_model->getRoles();

        $this->form_validation->set_rules('firstname', 'Firstname', 'required|strip_tags');
        $this->form_validation->set_rules('lastname', 'Lastname', 'required|strip_tags');
        $this->form_validation->set_rules('login', 'Login', 'required|callback_checkLogin|strip_tags');
        $this->form_validation->set_rules('email', 'Email', 'required|strip_tags');
        $this->form_validation->set_rules('role[]', 'Role', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('users/create', $data);
            $this->load->view('templates/footer');
        } else {
            $password = $this->users_model->setUsers();
            //Send an e-mail to the user so as to inform that its account has been created
            $this->load->library('email');
            $this->load->library('parser');
            $data = array(
                'Title' => 'User account to the Skeleton application',
                'BaseURL' => base_url(),
                'Firstname' => $this->input->post('firstname'),
                'Lastname' => $this->input->post('lastname'),
                'Login' => $this->input->post('login'),
                'Password' => $password
            );
            $message = $this->parser->parse('emails/new_user', $data, TRUE);

            if ($this->config->item('from_mail') != FALSE && $this->config->item('from_name') != FALSE ) {
                $this->email->from($this->config->item('from_mail'), $this->config->item('from_name'));
            } else {
             $this->email->from('do.not@reply.me', 'Skeleton app');
         }
         $this->email->to($this->input->post('email'));
         if ($this->config->item('subject_prefix') != FALSE) {
            $subject = $this->config->item('subject_prefix');
        } else {
         $subject = '[Skeleton] ';
     }
     $this->email->subject($subject . 'Your account is created');
     $this->email->message($message);
     log_message('debug', 'Sending the user creation email');
     if ($this->config->item('log_threshold') > 1) {
      $this->email->send(FALSE);
      $debug = $this->email->print_debugger(array('headers'));
      log_message('debug', 'print_debugger = ' . $debug);
  } else {
      $this->email->send();
  }

  $this->session->set_flashdata('msg', 'The user was successfully created');
  redirect('users');
}
}
}
