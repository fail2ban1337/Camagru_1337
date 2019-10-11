<?php
// session_start();
    class Users extends Controller {
        public function __construct()
        {
            $this->userModel = $this->model('user');
            $this->galleryModel = $this->model('photo');
        }

        public function register()
        {
            // check for POST
            if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                //process form

                // sanitize post data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                // init data
                $data = [
                    'name' => strtolower(trim($_POST['name'])),
                    'email' => strtolower(trim($_POST['email'])),
                    'password' => $_POST['password'],
                    'confirm_password' => $_POST['confirm_password'],
                    'name_error' => '',
                    'email_error' => '',
                    'password_error' => '',
                    'confirm_password_error' => ''
                ];
                // validate Email
                if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL))
                {
                    $data['email_error'] = 'Please entre a valid email';
                }
                if (empty($data['email']))
                {
                    $data['email_error'] = 'Please entre email';
                }else {
                    // check email
                    if ($this->userModel->findUserByEmail($data['email']))
                    {
                        $data['email_error'] = 'email already taken';   
                    }
                }
                // validate name
                if (empty($data['name']))
                {
                    $data['name_error'] = 'Please entre name';
                }else if (strlen($data['name']) <= 4 || strlen($data['name']) > 20)
                {
                    $data['name_error'] = 'Name should be between 5 and 20 characters';
                }
                // Check if username already Taken
                if ($this->userModel->findUserByName($data['name']))
                {
                    $data['name_error'] = 'Username Already Taken';
                }
                // validate password
                if (empty($data['password']))
                {
                    $data['password_error'] = 'Please entre password';
                } else if(!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,20}$/', $data['password']))
                {
                    $data['password_error'] = 'Password should be between 8-20 characters in length and should include at least one upper case letter, one number or one special character.';
                }
                // validate confirme password
                if (empty($data['confirm_password']))
                {
                    $data['confirm_password_error'] = 'Please confirm password';
                }else {
                    if ($data['password'] != $data['confirm_password'])
                    {
                        $data['confirm_password_error'] = 'Passwords do not match';
                    }
                }
                // Make sure the errors are empty
                if (empty($data['email_error']) && empty($data['name_error']) && empty($data['password_error']) && empty($data['confirm_password_error']))
                {
                    // Hash Password
                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                    
                    // Register User
                    if ($this->userModel->register($data))
                    {
                        // Succes
                        $_SESSION['confirme_email'] = 'succes';
                        // work on token  verfication
                        if ($this->userModel->userVerification($data['email']))
                        {;
                        // Redirect to login page
                        redirect('users/login');
                        }else {
                            die ('Problem in sending email verifcation');
                        }
                    }else {
                        die ('something went wrong');
                    }
                }else{
                    // Load view with errors
                    $this->view('users/register', $data);
                }

            }else {
                // load form
                $data = [
                    'name' => '',
                    'email' => '',
                    'password' => '',
                    'confirm_password' => '',
                    'name_error' => '',
                    'email_error' => '',
                    'password_error' => '',
                    'confirm_password_error' => ''
                ];
                //Load view
                $this->view('users/register',$data);
            }
        }
        public function login()
        {
            // check for POST
            if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                //process form
                // sanitize post data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                // init data
                $data = [
                    'name' => strtolower(trim($_POST['name'])),
                    'password' => $_POST['password'],
                    'name_error' => '',
                    'password_error' => '',
                    'is_verified' => ''
                ];

                // validate Email
                if (empty($data['name']))
                {
                    $data['name_error'] = 'Please entre your username';
                }

                // validate password
                if (empty($data['password']))
                {
                    $data['password_error'] = 'Please entre password';
                }

                // check for user/email
                if ($row = $this->userModel->findUserByName($data['name']))
                {
                    // user found let see if its already verified
                    if ($row->is_verfied == 0)
                    {
                        $data['is_verified'] = 'NO';
                    }

                }else {
                    $data['name_error'] = 'No User Found';
                }
                // make sure erros are empty
                if (empty($data['name_error'])&& empty($data['password_error']))
                {
                    //Validated
                    // check and set logged in user
                    $loggedInUser = $this->userModel->login($data['name'],$data['password']);
                    if ($loggedInUser)
                    {
                        // Create Session
                        if ($data['is_verified'] == '')
                        {
                            createUserSession($loggedInUser);
                            redirect('gallery/index');
                        }
                        else
                            $this->view('users/login',$data);
                    }else {
                        $data['is_verified'] = '';
                        $data['password_error'] = 'Password Incorrect';
                        $this->view('users/login',$data);
                    }
                }else{
                    // Load view with errors
                    $this->view('users/login', $data);
                }
                
            }else {
                // load form
                $data = [
                    'name' => '',
                    'password' => '',
                    'name_error' => '',
                    'password_error' => '',
                    'is_verified' => ''
                ];
                //Load view
                $this->view('users/login',$data);
            }            
        }
        public function logout($id = 1)
        {
            if ($_SESSION['logout'] == $id)
            {
            unset($_SESSION['user_id']);
            unset($_SESSION['user_email']);
            unset($_SESSION['user_name']);
            session_destroy();
            redirect('users/login');
            }else {
                redirect('users/404');
            }
        }
        
        public function verification($id = 1)
        {
            if ($row = $this->userModel->tokenverif($id))
            {
                $this->userModel->dealtoken($row);
                $_SESSION['confirmed'] = $row->email;
                redirect('users/login');
            }else {
                redirect('users/404');
            }
        }
        //email recover  responde
        public function recover()
        {
            if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                // sanitize post data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                // init data
                $data = [
                    'name' => strtolower(trim($_POST['name'])),
                    'name_error' => '',
                ];
                if (empty($data['name']))
                {
                    $data['name_error'] = 'Please entre your email or your username';
                }else {
                    if (!$this->userModel->findUserByEmail($data['name']) && !$this->userModel->findUserByName($data['name']))
                    {
                        $data['name_error'] = "Oops, that's not a match. Try again?";
                        $this->view('users/recover',$data);
                    }else
                    {
                        if ($row = $this->userModel->findUserByEmail($data['name']))
                        {
                            if ($this->userModel->userRecover($row->email))
                            {
                                $_SESSION['recover'] = 'sucess';
                                redirect('users/login');
                            }else {
                                $_SESSION['account_verif'] = 'ok';
                                redirect('users/login');
                            }
                        }else if ($row = $this->userModel->findUserByName($data['name']))
                        {
                            if ($this->userModel->userRecover($row->name))
                            {
                                $_SESSION['recover'] = 'sucess';
                                redirect('users/login');
                            }else {
                                $_SESSION['account_verif'] = 'ok';
                                redirect('users/login');
                            }
                        }
                    }
                }
                $this->view('users/recover',$data);
            }
            else {
                // load form
                $data = [
                    'name' => '',
                    'name_error' => '',
                ];
                //Load view without errors
                $this->view('users/recover',$data);
        }
        }
        // Work on setting of  user (edit profile)
        public function settings()
        {
            // this just to check if the user set the notification on=1 or off=0
            $result = $this->userModel->checkNofication();

            if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                // this is for the notification email (comments)
                if (!empty($_POST['status']))
                {
                    if ($_POST['status'] === 'checked')
                    {
                        $true = 1;
                        // here we gonna put 1 on our user table on the is_notifi row
                        $this->userModel->notifiUpdate($true);
                    }else {
                        $false = 0;
                        // here we gonna put 0 on our user table on the is_notifi row
                        $this->userModel->notifiUpdate($false);
                    }
                }else {

                // sanitize post data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                // init data
                $data = [
                    'name' => strtolower(trim($_POST['name'])),
                    'email' => strtolower(trim($_POST['email'])),
                    'password' => $_POST['password'],
                    'name_error' => '',
                    'email_error' => '',
                    'password_error' => '',
                    'is_notifi' => $result->is_notifi
                ];
                //validate email
                if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL))
                {
                    $data['email_error'] = 'Please entre a valid email';
                }
                if (empty($data['email']))
                {
                    $data['email_error'] = 'You must specify your email.';
                }else if ($this->userModel->findUserByEmail($data['email']) && $data['email'] != $_SESSION['user_email'])
                {
                    $data['email_error'] = 'Email Already Taken Please Chosse An Other One';   
                }else {
                    $pos1 = strpos($data['email'], '@');
                    $pos2 = strpos($data['email'], '.');
                    if ($pos1 !== false && $pos2 !== false && $pos2 > $pos1)
                    {
                        //valid email
                    }else{
                        // not a valid email
                        $data['email_error'] = "please enter a valid email address";
                    }
                }
                // validate name
                if (empty($data['name']))
                {
                    $data['name_error'] = 'You must specify your name.';
                }else if (strlen($data['name']) <= 4 || strlen($data['name']) > 20)
                {
                    $data['name_error'] = 'Name has to be between 5 and 20 characters';
                }
                if ($this->userModel->findUserByName($data['name']) && $data['name'] != $_SESSION['user_name'])
                {
                    $data['name_error'] = 'THIS USERNAME IS ALREADY TAKEN. PLEASE CHOOSE ANOTHER NAME';
                }
                // validate password
                if (empty($data['password']) &&  ($data['name'] != $_SESSION['user_name'] || $data['email'] != $_SESSION['user_email']))
                {
                    $data['password_error'] = 'Please entre password';
                }
                // Make sure the errors are empty
                if (!empty($data['password']) && empty($data['email_error']) && empty($data['name_error']) && empty($data['password_error']))
                {
                    // check if the password are correct or no
                    if ($result = $this->userModel->login($_SESSION['user_name'],$data['password']))
                    {
                        // lets work on changing the info that he
                        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                        if ($this->userModel->userEdit($data['name'], $data['email'], $data['password']))
                        {
                            // now lets change our session user
                            // first let get the result from database
                            $row = $this->userModel->findUserByName($data['name']);
                            // i cam back to update the (name on photos , likes)  comments username
                            // lets begin with the Comments table 
                            $this->galleryModel->updataNameOnComments($row->name);
                            // now lets update the likes table
                            $this->galleryModel->updateNameOnLikes($row->name);
                            // last thing lets update the photos table
                            $this->galleryModel->updateNameOnPhotos($row->name);
                            //edit session with the result of database
                            createUserSession($row);
                            $_SESSION['settings'] = 'OK';
                            $this->view('users/settings',$data);
                        }else {
                            die ('Something goes wrong when updating...');
                        }
                    }else {
                        // password is not correct
                        $data['password_error'] = 'Sorry, wrong password';
                        // Load view with errors
                        $this->view('users/settings', $data);
                    }
                }else{
                    // Load view with errors
                    $this->view('users/settings', $data);
                }
                }
            }else {
                // load form
                $data = [
                    'name' => '',
                    'email' => '',
                    'password' => '',
                    'name_error' => '',
                    'email_error' => '',
                    'password_error' => '',
                    'is_notifi' => $result->is_notifi
                ];
                //Load view
                $this->view('users/settings',$data);
            }
        }
        // work on change password
        public function  passedit()
        {
            if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                // sanitize post data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                // init data
                $data = [
                    'password' => trim($_POST['password']),
                    'first_conpass' => $_POST['first_conpass'],
                    'secend_conpass' => $_POST['secend_conpass'],
                    'password_error' => '',
                    'first_conpass_error' => '',
                    'secend_conpass_error' => ''
                ];
                // validate password
                if (!$this->userModel->login($_SESSION['user_name'],$data['password']))
                {
                    $data['password_error'] = 'Sorry, wrong password';
                }
                // validate confirm password
                if (empty($data['first_conpass']))
                {
                    $data['first_conpass_error'] = 'Enter a password to continue.';
                }else if(!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,20}$/', $data['first_conpass']))
                {
                    $data['first_conpass_error'] = 'Password should be between 8-20 characters in length and should include at least one upper case letter, one number, and one special character.';
                } 
                if (empty($data['secend_conpass']))
                {
                    $data['secend_conpass_error'] = 'Please verify your password';
                }
                if ($data['first_conpass'] != $data['secend_conpass'] && !empty($data['first_conpass']) && !empty($data['secend_conpass']))
                {
                    $data['secend_conpass_error'] = 'Passwords do not match';
                }
                // Make sure the errors are empty
                if (empty($data['password_error']) && empty($data['first_conpass_error']) && empty($data['secend_conpass_error']))
                {
                    // Hash Password
                    $data['first_conpass'] = password_hash($data['first_conpass'], PASSWORD_DEFAULT);
                    // Register User
                    if ($this->userModel->userEdit($_SESSION['user_name'], $_SESSION['user_email'], $data['first_conpass']))
                    {
                        // Succes
                        $_SESSION['password_change'] = 'succes';
                        redirect('users/passedit');
                    }else{
                        die ('something goes Wrong');
                    }
                }else{
                    // Load view with errors
                    $this->view('users/passedit', $data);
                }
            }else {
                // load form
                $data = [
                    'password' => '',
                    'first_conpass' => '',
                    'secend_conpass' => '',
                    'password_error' => '',
                    'first_conpass_error' => '',
                    'secend_conpass_error' => ''
                ];
                //Load view
                $this->view('users/passedit',$data);
            }
        }
        // work on rest password (forgotten account)
        public function resetpass($id = 1)
        {
            if ($_SERVER['REQUEST_METHOD'] == 'GET')
            {
             if ($row = $this->userModel->tokenvrest($id))
             {
                $_SESSION['resetpass'] = $id;
                $this->view('users/resetpass',NULL);
             }else {
                redirect('users/404');
             }
            }else if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                // load form
                $data = [
                    'first_pass' => $_POST['first_pass'],
                    'secend_pass' => $_POST['secend_pass'],
                    'first_pass_error' => '',
                    'secend_pass_error' => '',
                ];
                $row = $this->userModel->tokenvrest($_SESSION['resetpass']);
                // validate password
                if (empty($data['first_pass']))
                {
                    $data['first_pass_error'] = 'Please entre password';
                } else if(!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,20}$/', $data['first_pass']))
                {
                    $data['first_pass_error'] = 'Password should be between 8-20 characters in length and should include at least one upper case letter, one number, and one special character.';
                } 
                // validate confirme password
                if (empty($data['secend_pass']))
                {
                    $data['secend_pass_error'] = 'Please confirm password';
                }else {
                    if ($data['first_pass'] != $data['secend_pass'])
                    {
                        $data['secend_pass_error'] = 'Passwords do not match';
                    }
                }
                if (password_verify($data['first_pass'],$row->password))
                {
                    $data['first_pass_error'] = 'HI STRANGER This Is Your Password Just Login With It And Stop Wasting Our Time';
                }
                if (empty($data['first_pass_error']) && empty($data['secend_pass_error']))
                {
                    // lets change the password
                    // Hash Password
                    $data['first_pass'] = password_hash($data['first_pass'], PASSWORD_DEFAULT);
                    // Register User
                    if ($this->userModel->passwordrest($data['first_pass'], $row->name))
                    {
                        // Succes
                        $_SESSION['restpassword_change'] = 'succes';
                        unset($_SESSION['resetpass']);
                        if ($this->userModel->removeresettoken($row->name))
                        {
                            redirect('users/login');
                        }else {
                            die ('Something Goes Wrong');
                        }
                    }else{
                        die ('something goes Wrong');
                    }
                }else {
                    // Load view with errors
                    $this->view('users/resetpass', $data);
                }
            }
        }
        // set error handler later
        public function index()
        {
            $this->view('users/404');
        }

            // this is for the remove account
    public function removeaccount($id = 1)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && $id === 1 && isLoggedIn())
        {
            // lets first put the token on the token_del
            if ($token = $this->userModel->tokendeluser())
            {
                // after we set the token on the user row 
                // now we gonna send the email to the user
                if ($this->userModel->sendMailDele($token))
                {
                    // now lets redirect the user to the setting page and show  him a message to inform him that he need to confirm his reuest on the mail
                    $_SESSION['delete_account'] = 'OK';
                    redirect('users/settings');
                }
            }
        }else if ($id != 1 && $_SERVER['REQUEST_METHOD'] == 'GET'){
            // lets check our id so we can confirm if we gonna delete the user our not
            // first of all we gonna send the id to user method so can check our data to check if its valide or not
            if ($row = $this->userModel->checkfortokenuser($id))
            {
                // we did find  a user with that uniqe id 
                if ($result = $this->galleryModel->getalllikes($row[0]->name))
                {
                    foreach($result as $likes)
                    {
                        // dec the likes from the images
                        $this->galleryModel->decliketbphotos($likes->photoname);
                    }
                    // now im gonna remove all the likes of the user
                    $this->galleryModel->removeAlllikestb($row[0]->name);
                }
                // here we gonna remove all the comments from database
                $this->galleryModel->removeAllComments($row[0]->name);
                // now lets unlink the photos from database
                if ($photos = $this->galleryModel->getphotoToRemove($row[0]->name))
                {
                    foreach($photos as $rows)
                    {
                        $rows->photoname;
                        // also remove the likes of users from our database
                        $this->galleryModel->removeLikesFromdlimages($rows->photoname);
                        // also remove the comments of other user on the photos of the user that delete his account
                        $this->galleryModel->removeCommentsFromimages($rows->photoname);
                        unlink('/var/www/html/abelomar/public/uploded/'.$rows->photoname.'.png');
                    }
                }
                // finally lets remove all the photo from phototb
                $this->galleryModel->removeAllphotostb($row[0]->name);
                // now we gonna remove the user from our database
                $this->userModel->removeUserTbUsers($row[0]->name);
                DestroyUserSession();
                redirect('users/register');
            }
        }
        // redirect the user to the error page
        $this->view('users/404');
    }
}