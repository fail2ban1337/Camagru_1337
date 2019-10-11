<?php 
// session_start();
class Gallery extends Controller {
    public function __construct()
    {
        $this->galleryModel = $this->model('photo');
        $this->useryModel = $this->model('user');
    }

    public function index($page = 1)
    {
        if ($page == 0 || $page < 0)
        {
            $page = 1;
        } 
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if (!empty($_POST['likephotoname']))
            {
                // lets first check if the user already like  this  photo or not
                if ($this->galleryModel->checkforlikes($_POST['likephotoname']))
                {
                    echo 'user already liked it';
                    // here we gonna remove row of the like of user checked with session from  table likes 
                    if ($this->galleryModel->removerowliketblike($_POST['likephotoname']))
                    {
                        // here we gonnae Decrementing one like from our photos table
                        $this->galleryModel->decliketbphotos($_POST['likephotoname']);
                    }
                }else {
                    // there is no like on the database for the user on the chosen image
                    // so we have to add  it to our database
                    if ($this->galleryModel->addliketblikes($_POST['likephotoname']))
                    {
                        // Incrementing like to our photos table
                        $this->galleryModel->incliketbphotos($_POST['likephotoname']);
                        echo "the like hasss ben added";
                    }else {
                        die ('error to add like');
                    }
                }
            }
            if (!empty($_POST['commentofthephoto']) && !empty($_POST['photoname']))
            {
                $_POST['commentofthephoto'] = htmlspecialchars($_POST['commentofthephoto'], ENT_QUOTES);
                // lets now work on getting comments on tb of comments
                if ($this->galleryModel->addcommenttbcom($_POST['photoname'],$_POST['commentofthephoto']))
                {
                    // here we gonna send  an email to the user to informe him that there is  a new comments on his photo
                    // but first lets check if its his photo or not
                    if (!$this->galleryModel->checkBeforeSendNotif($_POST['photoname']))
                    {
                        // its not his image so lets send him the email
                        // also check if that user set the notification on on his settings
                        // lets first get the name of the image user
                        if ($row = $this->galleryModel->checkForNamePhoto($_POST['photoname']))
                        {
                            // we get the  name of the owner of the photo
                            // now lets check his setting to see if the notificatiion email set on or off (we gonna nee the user method)
                            if($result = $this->useryModel->checkForNotiStatus($row->name))
                            {
                                // lets send the email now after checking that the user set the notificatiom email on
                                $this->galleryModel->sendNotificationMail($result->name, $result->email);
                            }  
                        }
                    }
                    echo "the comment haas been added";
                }else {
                    die('something goes wrong');
                }
            }
        }else {
        
        // first of all lets get all the images for the index
        $count =  $this->galleryModel->countphotos();
        $total =  $count[0]->id;
        $limit = 5;
        $pages = (int)ceil($total / $limit);
        if ($page > $pages)
            $page = $pages;
        if ($page <= 0)
            $page = 1;
        $start = ($page - 1) * $limit;
        $mydata = array();
        $mydata['data'] = $this->galleryModel->getPhotosIndex($start, $limit);
        $like = array();
        $comment = array();
        $i = 0;
        foreach ($mydata['data'] as $result)
        {
                $comment[$i] = $this->galleryModel->getcomment($result->photoname);
                if ($this->galleryModel->getlikes($result->photoname)) {
                    $like[$i] = "Y";
                } else 
                    $like[$i] = "N";
                $i++;
        }
        $pageinat = array();
        $previous = $page - 1;
        $next = $page + 1;
        if ($next > $pages)
        {
            $next = $pages;
        }
        $pageinat[0] = $previous;
        $pageinat[1] = $next;
        $mydata['likestb'] = $like;
        $mydata['comments'] = $comment;
        $mydata['pages'] = $pageinat;
        $this->view('gallery/index', $mydata);
        }
    }
    
    public function profile()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if (!empty($_POST['imgdl']))
            {
                // here we gonnae delete the image
                if ($this->galleryModel->deletePhoto($_POST['imgdl']))
                {
                unlink('/var/www/html/abelomar/public/uploded/'.$_POST['imgdl'].'.png');
                // and we gonna delete all the likes of the image chossen
                $this->galleryModel->deleteAllLikeOfImage($_POST['imgdl']);
                // and the last thing we gonna do is remove the comments from our database
                $this->galleryModel->deleteCommentFromData($_POST['imgdl']);
                }
                $mydata = array();
                $mydata['data'] = $this->galleryModel->getphotos();
                $like = array();
                $comment = array();
                $i = 0;
                foreach ($mydata['data'] as $result)
                {
                    $comment[$i] = $this->galleryModel->getcomment($result->photoname);
                    if ($this->galleryModel->getlikes($result->photoname)) {
                        $like[$i] = "Y";
                    } else 
                        $like[$i] = "N";
                    $i++;
                }
                $mydata['likestb'] = $like;
                $mydata['comments'] = $comment;
                $this->view('gallery/profile', $mydata);
            }
            if (!empty($_POST['likephotoname']))
            {
                // lets first check if the user already like  this  photo or not
                if ($this->galleryModel->checkforlikes($_POST['likephotoname']))
                {
                    echo 'user already liked it';
                    // here we gonna remove row of the like of user checked with session from  table likes 
                    if ($this->galleryModel->removerowliketblike($_POST['likephotoname']))
                    {
                        // here we gonnae Decrementing one like from our photos table
                        $this->galleryModel->decliketbphotos($_POST['likephotoname']);
                    }
                }else {
                    // there is no like on the database for the user on the chosen image
                    // so we have to add  it to our database
                    if ($this->galleryModel->addliketblikes($_POST['likephotoname']))
                    {
                        // Incrementing like to our photos table
                        $this->galleryModel->incliketbphotos($_POST['likephotoname']);
                        echo "the like hasss ben added";
                    }else {
                        die ('error to add like');
                    }
                }
            }
            if (!empty($_POST['commentofthephoto']) && !empty($_POST['photoname']))
            {
                $_POST['commentofthephoto'] = htmlspecialchars($_POST['commentofthephoto'], ENT_QUOTES);
                // lets now work on getting comments on tb of comments
                if ($this->galleryModel->addcommenttbcom($_POST['photoname'],$_POST['commentofthephoto']))
                {
                    echo "the comment haas been added";
                    
                }else {
                    die('something goes wrong');
                }
            }
            }else {
            $mydata = array();
            $mydata['data'] = $this->galleryModel->getphotos();
            $like = array();
            $comment = array();
            $i = 0;
            foreach ($mydata['data'] as $result)
            {
                $comment[$i] = $this->galleryModel->getcomment($result->photoname);
                if ($this->galleryModel->getlikes($result->photoname)) {
                    $like[$i] = "Y";
                } else 
                    $like[$i] = "N";
                $i++;
            }
            $mydata['likestb'] = $like;
            $mydata['comments'] = $comment;
             $this->view('gallery/profile', $mydata);
        }
    }




    public function add()
    {
        // $source = file_get_contents("php://input");
          if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if (!empty($_POST['hidden_data']))
            {
                if ($_POST['hidden_data'] === 'data:,')
                {
                $data = $this->galleryModel->getphotos();
                $this->view('gallery/add',$data);
                }else {
                if (!file_exists('/var/www/html/abelomar/public/uploded')) {
                    mkdir('/var/www/html/abelomar/public/uploded', 0777, true);
                }
                $upload_dir = '/var/www/html/abelomar/public/uploded/';
                $img = $_POST['hidden_data'];
             
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                $file = $upload_dir . time() . ".png";
                file_put_contents($file, $data);
                $src = '/var/www/html/abelomar/public/img/' . $_POST['sticker_data'];
                $dst = imagecreatefrompng($file);
                if (!empty($_POST['sticker_data']))
                {
                    $src = imagecreatefrompng($src);
                    imagecopy($dst,$src,0,0,0,0,216,216);
                }
                imagepng($dst,$file,0);
                // first of all lets remove the upload directory from the string file name
                $photoname = str_replace($upload_dir,'',$file);
                // lets remove .png 
                $photoname =  pathinfo($photoname, PATHINFO_FILENAME);
                // let save name of the photo to the database
                if(!$this->galleryModel->addphotos($photoname))
                {
                    die("there is an error ");
                }
            }
            }
            if (!empty($_POST['delete_file']))
            {
                if ($this->galleryModel->deletePhoto($_POST['delete_file']))
                {
                unlink('/var/www/html/abelomar/public/uploded/'.$_POST['delete_file'].'.png');
                // and we gonna delete all the likes of the image chossen
                $this->galleryModel->deleteAllLikeOfImage($_POST['delete_file']);
                // and the last thing we gonna do is remove the comments from our database
                $this->galleryModel->deleteCommentFromData($_POST['delete_file']);
                }
            }
        }
        $data = $this->galleryModel->getphotos();
        $this->view('gallery/add',$data);
    }
}