<?php
class Install extends Controller {
    public function __construct()
    {
    }
    public function setup()
    {
        require_once (''.dirname(dirname(__FILE__)).'/config/setup.php');
    }
    public function index()
    {
        $this->view('users/404', $data);
    }
}