<?php
    /*
    * App Core Class
    * Creates URL & loads core controller
    * URL Format - /controller/methods/params
    */
    class Core {
        protected $currentController = 'gallery';
        protected $currentMethod = 'index';
        protected $params = [];

        public function __construct()
        {
            //print_r ($this->geturl());

            $url = $this->getUrl();

            // Look in controllers for first value
            if (file_exists('../app/controllers/'. ucwords($url[0]) . '.php'))
            {
                //if its exists , set as controller
                $this->currentController = ucwords($url[0]);
                // unset 0 index
                unset($url[0]);
            }
            // Require the controller
            require_once '../app/controllers/'. $this->currentController . '.php';
            // instanite controller class
            $this->currentController = new $this->currentController;

            // check for second part of url
            if (isset($url[1]))
            {
                // check to see if method existe in controller
                if (method_exists($this->currentController, $url[1]))
                {
                    $this->currentMethod = $url[1];
                }
                // unset url [1]
                unset($url[1]);
            }
            // Get params
            $this->params = $url ? array_values($url) : [];
            if ($this->currentMethod === 'view' || $this->currentMethod === 'construct' || $this->currentMethod === 'model')
                redirect('users/404');
            //Call a callback with array of params
            call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
        }

        public function getUrl()
        {
            if(isset($_GET['url']))
            {
                $url = rtrim($_GET['url'], '/');
                $url = filter_var($url,FILTER_SANITIZE_URL);
                $url = explode('/', $url);
                return $url;
            }
        }
    }