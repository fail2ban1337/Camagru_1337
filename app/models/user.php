<?php
    class User extends Database {
        private $db;

        public function __construct()
        {
            $this->db = new Database;
        }

        // Register User 
        public function register($data)
        {
            $this->db->query('INSERT INTO users (name,email,password,verification_key) VALUES(:name,:email,:password,:vr_key)');
            // creat vr_key
            $vr_key = md5(bin2hex(random_bytes(10)));
            // Bind values
            $this->db->bind(':name',$data['name']);
            $this->db->bind(':email',$data['email']);
            $this->db->bind(':password',$data['password']);
            $this->db->bind(':vr_key',$vr_key);
            //Execute
            if ($this->db->execute())
            {
                return true;
            }else
            {
                return false;
            }
        }
        // set token vr_key
        public function settoken($data)
        {
            $token = md5(bin2hex(random_bytes(10)));
            $this->db->query('UPDATE users SET verification_key = :vr_key WHERE email = :email');
            $this->db->bind('vr_key', $token);
            $this->db->bind('email', $data->email);
            if ($this->db->execute())
            {
                return $token;
            }else {
                return false;
            }
        }

        // work on the token_del
        public function tokendeluser()
        {
            $token = md5(bin2hex(random_bytes(10)));
            $this->db->query('UPDATE users SET token_del = :token WHERE name = :name');
            $this->db->bind('token', $token);
            $this->db->bind('name',  $_SESSION['user_name']);
            //Execute
            if ($this->db->execute())
            {
                return $token;
            }else
            {
                return false;
            }
        }

        // we gonna work on getting the user who have that unique id so we can start on our delete steps
        public function checkfortokenuser($token)
        {
            // query our  data
            $this->db->query('SELECT * FROM users WHERE token_del = :token');
            // bind our values
            $this->db->bind('token', $token);
            // get the single result
            if ($row = $this->db->resultSet())
            {
                return $row;
            }else {
                return false;
            }
        }

        // remove the user from our users table
        public function removeUserTbUsers($name)
        {
            // query our data
            $this->db->query('DELETE FROM users WHERE name = :name');
            // bind the values
            $this->db->bind('name', $name);
            $this->db->execute();
            if ($this->db->rowCount() > 0)
            {
                return true;
            }else {
                return false;
            }
        }

        // we gonna work on sending  an email to the  user 
        public function sendMailDele($token)
        {
            $to = $_SESSION['user_email'];
            $subject = "Notification About Request To Delete Your Account";
            $message='Hello '.$_SESSION['user_name'].' we recive your  request to delte your account  and your entire data please confirm your request <a href="http://localhost/abelomar/users/removeaccount/'.$token.'">Link</a> to remove your account.';
            $headers = 'From: no-reply@camagru.com' . "\r\n" .
            'Reply-To: no-reply@camagru.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
            if (mail($to,$subject,$message,$headers))
            {
                return true;
            }else {
                return false;
            }

        }

        // Work on email verification
        public function userVerification($email)
        {
            $this->db->query('SELECT * FROM users WHERE email = :email');
            $this->db->bind(':email', $email);
            $row = $this->db->single();
            $token = $row->verification_key;
            if ($this->db->rowcount() > 0)
            {
                $message = "Your Activation Code is " .$token . "";
                $to = $row->email;
                $subject = "Activation Code For Camagru";
                $from = 'no-reply@camagru.com';
                $body='Your Activation Code is '.$token.' Please Click On This link <a href="http://localhost/abelomar/users/verification/'.$token.'">Link</a> to activate  your account.';
                $headers = "From:".$from;
                mail($to,$subject,$body,$headers);
                return true;
            }else{
                return false;
            }
        }
        // work on email recover
        public function userRecover($valueIN)
        {
            $this->db->query('SELECT * FROM users WHERE (email = :email OR name = :name) AND is_verfied = :verfi');
            $this->db->bind('email', $valueIN);
            $this->db->bind('name', $valueIN);
            $this->db->bind('verfi', 1);
            $row = $this->db->single();
            if ($this->db->rowCount() > 0)
            {
                if ($token = $this->settoken($row))
                {
                    $message = '';
                    $to = $row->email;
                    $subject = "Reset your password";
                    $from = 'no-reply@camagru.com';
                    $body= 'No need to worry, you can reset your Camagru password by clicking the link below to activate  your account:<br>
                    <a href="http://localhost/abelomar/users/resetpass/'.$token.'">Link </a><br>' . 'Your username is: '. $row->name;
                    $headers = "From:".$from;
                    if (mail($to,$subject,$body,$headers))
                        return true;
                    else
                        return false;
                }
            }else {
                return false;
            }
        }
        // Lgoin User
        public function login($name, $password)
        {
            $this->db->query('SELECT * FROM users WHERE name = :name');
            $this->db->bind('name',$name);

            $row = $this->db->single();

            $hashed_password = $row->password;
            if (password_verify($password, $hashed_password))
            {
                return $row;
            }else {
                return false;
            }
        }

        // find user by email
        public function findUserByEmail($email)
        {
            $this->db->query('SELECT * FROM users WHERE email = :email');
            $this->db->bind(':email', $email);

            $row = $this->db->single();
            // check row
            if ($this->db->rowCount() > 0)
            {
                return $row;
            }else {
                return false;
            }
        }
        // find user by usename
        public function findUserByName($name)
        {
            $this->db->query('SELECT * FROM users WHERE name = :name');
            $this->db->bind(':name', $name);

            $row = $this->db->single();
            // check row
            if ($this->db->rowCount() > 0)
            {
                return $row;
            }else {
                return false;
            }
        }
        // Edite my profile function
        public function userEdit($name,$email,$password)
        {
            $this->db->query('UPDATE users SET name = :name, email = :email, password = :password WHERE  id = :userid');
            $this->db->bind('name', $name);
            $this->db->bind('password', $password);
            $this->db->bind('email', $email);
            $this->db->bind('userid', $_SESSION['user_id']);
            //Execute
            if ($this->db->execute())
            {
                return true;
            }else
            {
                return false;
            }
        }
        // reset password (update password)
        public function passwordrest($password, $name)
        {
            $this->db->query('UPDATE users SET password = :password WHERE name = :name');
            $this->db->bind('name', $name);
            $this->db->bind('password', $password);
            // Execute
            if ($this->db->execute())
            {
                return true;
            }else {
                return false;
            }
        }
        // check the token verification
        public function tokenverif($token)
        {
            $this->db->query('SELECT * FROM users WHERE verification_key = :token AND is_verfied = :isNan');
            $this->db->bind('token', $token);
            $this->db->bind('isNan', 0);
            $row = $this->db->single();
            if ($this->db->rowCount() > 0)
            {
                return $row;
            }else {
                return false;
            }
        }
        // delate the token after done verifing the email
        public function dealtoken($row)
        {
            $this->db->query('UPDATE users SET verification_key = :token, is_verfied = :verfied  WHERE verification_key = :rowtoken');
            $this->db->bind('token', '');
            $this->db->bind('verfied', 1);
            $this->db->bind('rowtoken',$row->verification_key);
            $this->db->execute();
        }
        // check the token reset 
        public function tokenvrest($token)
        {
            $this->db->query('SELECT * FROM users WHERE verification_key = :token');
            $this->db->bind('token', $token);
            $row = $this->db->single();
            if ($this->db->rowCount() > 0)
            {
                return $row;
            }else {
                return false;
            }
        }
        // remove the token reset
        public function removeresettoken($name)
        {
            $this->db->query('UPDATE users SET verification_key = :token WHERE name = :name');
            $this->db->bind('token', '');
            $this->db->bind('name', $name);
            if ($this->db->execute())
            {
                return true;
            }else
            {
                return false;
            }
        }

        // check if the user set the notification of  or off
        public function checkNofication()
        {
            // query our data
            $this->db->query('SELECT * FROM users WHERE name = :name');
            // bind our value
            $this->db->bind('name', $_SESSION['user_name']);
            // execute our data
            $result = $this->db->single();
            return $result;
        }

        // in this function we gonna work on set true or false depande on the request from the controller on the notifi row
        public function notifiUpdate($value)
        {
            // query our data
            $this->db->query('UPDATE users SET is_notifi = :notifi WHERE name = :name');
            // bind our value
            $this->db->bind('notifi', $value);
            $this->db->bind('name', $_SESSION['user_name']);
            $this->db->execute();
        }

        // in this function we come from the gallery model to see if the user set the notification email set on or  off
        public function checkForNotiStatus($name)
        {
            // query our data
            $this->db->query('SELECT * FROM users WHERE name = :name AND is_notifi = :valuetr');
            // bind our value
            $this->db->bind('name', $name);
            $this->db->bind('valuetr', 1);
            $row = $this->db->single();
            return $row;
        }
    }