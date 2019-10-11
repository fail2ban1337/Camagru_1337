<?php
class Photo extends Database {
    private $db;

        public function __construct()
        {
            $this->db = new Database;
        }
   
        // here we work on the likes table
        // check if the user did like the photo given on the argument or  no
        public function checkforlikes($photoname)
        {
            // query our data
            $this->db->query('SELECT * FROM likes WHERE name = :name && photoname = :photoname');
            // bind the values
            $this->db->bind('name', $_SESSION['user_name']);
            $this->db->bind('photoname', $photoname);
            // lets execute our query
            $this->db->execute();
            // lets see if there a like register with that username
            if ($this->db->rowCount() > 0)
            {
                return true;
            }else {
                return false;
            }
        }

        // here after we did delete our photo we need to delete all likes of it
        public function deleteAllLikeOfImage($photoname)
        {
            // query our data
            $this->db->query('DELETE FROM likes WHERE photoname = :photoname');
            // bind our values
            $this->db->bind('photoname', $photoname);
            $this->db->execute();
        }

        // get all likes data from likes table
        public function getlikes($photo)
        {
            if (isset($_SESSION['user_name']))
            {
            // query our data
            $this->db->query('SELECT * FROM likes WHERE name = :name AND photoname = :photo');
            // bind value
            $this->db->bind('name', $_SESSION['user_name']);
            $this->db->bind('photo', $photo);
            $row = $this->db->single();
            return $row;
            }
            return false;
        }

        // here we add like to our  likes table for the image
        public function addliketblikes($photoname)
        {
            // query our data
            $this->db->query('INSERT INTO likes (name,photoname) VALUES (:name,:photoname)');
            // bind our values
            $this->db->bind('name', $_SESSION['user_name']);
            $this->db->bind('photoname', $photoname);
            if ($this->db->execute())
            {
                return true;
            }else {
                return false;
            }
        }

        // here we get all likes of the user
        public function getalllikes($name)
        {
            // query our data
            $this->db->query('SELECT * FROM likes WHERE name = :name');
            // bind the value
            $this->db->bind('name', $name);
            if ($row = $this->db->resultSet())
            {
                return $row;
            }else {
                return false;
            }
        }

        // here we gonna remove the row of the like from the database
        public function removerowliketblike($photoname)
        {
            // query our data
            $this->db->query('DELETE FROM likes WHERE name = :name && photoname = :photoname');
            //bind values
            $this->db->bind('name', $_SESSION['user_name']);
            $this->db->bind('photoname', $photoname);
            $this->db->execute();
            if ($this->db->rowCount() > 0)
            {
                return true;
            }else {
                return false;
            }
        }
        // this is after the user changing his name of the setting page (update likes with the new name of the user)
        public function updateNameOnLikes($name)
        {
            // query our data
            $this->db->query('UPDATE likes SET name = :name WHERE name = :oldename');
            // bind the value
            $this->db->bind('name', $name);
            $this->db->bind('oldename', $_SESSION['user_name']);
            $this->db->execute();
        }

            // here we gonna delete all likes of the user from likes tb
            public function removeAlllikestb($name)
            {
                //query our data
                $this->db->query('DELETE FROM likes WHERE name = :name');
                // bind values
                $this->db->bind('name', $name);
                $this->db->execute();
            }


            // here we gonna delete all likes from other user on the user that delete his account
            public function removeLikesFromdlimages($photoname)
            {
                //query our data
                $this->db->query('DELETE FROM likes WHERE photoname = :photoname');
                // bind values
                $this->db->bind('photoname', $photoname);
                $this->db->execute();
            }
    
        // work on the photos table

        // here we add like to our photos table 
        public function incliketbphotos($photoname)
        {
            // query our data
            $this->db->query('UPDATE photos SET likes = likes + 1 WHERE photoname = :photoname');
            // bind the value
            $this->db->bind('photoname', $photoname);
            $this->db->execute();
        }

        // here we gonna dec one like from our photos tb
        public function decliketbphotos($photoname)
        {
            // query our data 
            $this->db->query('UPDATE photos SET likes = likes - 1 WHERE photoname = :photoname');
            // bin value
            $this->db->bind('photoname' , $photoname);
            $this->db->execute();
        }

        // get all image of the user to show tham on the add page
        public function getphotos()
        {
            // qeury our data
            $this->db->query('SELECT * FROM photos WHERE name = :name ORDER BY id DESC');
            $this->db->bind('name', $_SESSION['user_name']);
            // execute
            $result = $this->db->resultSet();
            return $result;
        }

        // get all the photos to delete them after
        public function getphotoToRemove($name)
        {
            // query our data
            $this->db->query('SELECT * FROM photos WHERE name = :name');
            // bind values
            $this->db->bind('name', $name);
            if ($row = $this->db->resultSet())
            {
                return $row;
            }else {
                return false;
            }
        }
        // remove image from database
        public function deletePhoto($photoname)
        {
            // query our dat
            $this->db->query('DELETE FROM photos WHERE photoname = :photoname AND name = :username');
            // lets bind our values
            $this->db->bind('photoname', $photoname);
            $this->db->bind('username', $_SESSION['user_name']);
            // lets execute our query
            $this->db->execute();
            if ($this->db->rowCount() > 0)
            {
                return true;
            }else {
                return false;
            }
        }
             // save image name on the database
        public function addphotos($data)
        {
            // qeury our data
            $this->db->query('INSERT INTO photos (name,photoname) VALUES (:name, :photoname)');
            // bind our values
            $this->db->bind('name', $_SESSION['user_name']);
            $this->db->bind('photoname',$data);
            //Execute
            if ($this->db->execute())
            {
                return true;
            }else
            {
                return false;
            }
        }
        // this for get the number of images set the database
        public function countphotos()
        {
            // query database
            $this->db->query('SELECT count(id) AS id FROM photos');
            $row = $this->db->resultSet();
            return $row;
        }
        
        // lets get photo to our index
        public function  getPhotosIndex($start, $limit)
        {
            // query our data
            $this->db->query('SELECT * FROM photos ORDER BY id DESC LIMIT :start, :limit');
            $this->db->bind('start', (int)$start);
            $this->db->bind('limit', (int)$limit);
            // execute our data
            $result = $this->db->resultSet();
            return $result;
        }

        // this is after the user changing his name of the setting page (update likes with the new name of the user)
        public function updateNameOnPhotos($name)
        {
            // query our data
            $this->db->query('UPDATE photos SET name = :name WHERE name = :oldename');
            // bind the value
            $this->db->bind('name', $name);
            $this->db->bind('oldename', $_SESSION['user_name']);
            $this->db->execute();
        }

        // just to check before send the netification if its his own photo or not
        public function checkBeforeSendNotif($photoname)
        {
            // query our data
            $this->db->query('SELECT * FROM photos WHERE name = :name AND photoname = :photoname');
            // bind our value
            $this->db->bind('name', $_SESSION['user_name']);
            $this->db->bind('photoname', $photoname);
            $row = $this->db->single();
            return $row;
        }
        // here we want to get the owner of the photo name
        public function checkForNamePhoto($photoname)
        {
            // query our data
            $this->db->query('SELECT * FROM photos WHERE photoname = :photoname');
            // bind the value
            $this->db->bind('photoname', $photoname);
            $row = $this->db->single();
            return $row;
        }

        // here is the final step to remove all the photo of the user chossen to remove his account
        public function removeAllphotostb($name)
        {
            // query our data
            $this->db->query('DELETE FROM photos WHERE name = :name');
            // bind values
            $this->db->bind('name', $name);
            $this->db->execute();
        }


        /// here we models of the comment table
        
        // get comment by photoname
        public function getcomment($photoname)
        {
            //query our data
            $this->db->query('SELECT * FROM comments  WHERE photoname = :photoname ORDER BY id DESC');
            // bind value 
            $this->db->bind('photoname', $photoname);
            // execute our query
            $result = $this->db->resultSet();
            return $result;
        }
        // adding new comment
        public function addcommenttbcom($photoname, $comment)
        {
            // query our data
            $this->db->query('INSERT INTO comments (name,photoname,comments) VALUES (:name, :photoname, :comment)');
            // bind our values
            $this->db->bind('name' , $_SESSION['user_name']);
            $this->db->bind('photoname', $photoname);
            $this->db->bind('comment', $comment);
            // execute 
           if ($this->db->execute())
            {
                return true;
            }else {
                return false;
            }
        }
        // Here we gonna remove all the commants from our database of the chosen image
        public function deleteCommentFromData($photoname)
        {
            // query our data
            $this->db->query('DELETE FROM comments WHERE photoname = :photoname');
            // bind the value
            $this->db->bind('photoname', $photoname);
            $this->db->execute();
        }
        // this is after the user changing his name of the setting page
        public function updataNameOnComments($name)
        {
            // query our data
            $this->db->query('UPDATE comments SET name = :name WHERE name = :oldename');
            // bind the value
            $this->db->bind('name', $name);
            $this->db->bind('oldename', $_SESSION['user_name']);
            $this->db->execute();
        }

        // here we gonna remove all the comments
        public function removeAllComments($name)
        {
            // query our data
            $this->db->query('DELETE FROM comments WHERE name = :name');
            // bind values 
            $this->db->bind('name', $name);
            $this->db->execute();
        }

        // here we gonna remove all comments on images of the user that he delete his account
        public function removeCommentsFromimages($photoname)
        {
            // query our data
            $this->db->query('DELETE FROM comments WHERE photoname = :photoname');
            // bind values 
            $this->db->bind('photoname', $photoname);
            $this->db->execute();
        }

        // lets send the email to the user to enform him that there is a new comment on his photo
        public function sendNotificationMail($name, $email)
        {
            $to = $email;
            $subject = "Notification A New Comment On Your Photo";
            $message='Hello '.$name.' ,your friend '.$_SESSION['user_name'].' add a new comment on your photo';
            $headers = 'From: no-reply@camagru.com' . "\r\n" .
            'Reply-To: no-reply@camagru.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
            mail($to,$subject,$message,$headers);
        }
}