<?php
class setup extends Database {
    private $db;
    public $tableuser = 'users';
    public $phototable = 'photos';
    public $likestable = 'likes';
    public $commentstable = 'comments';

    public function __construct()
    {
        $this->db = new Database;
        $this->db->query("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
        $this->db->execute();
        $this->mytalbes();
    }

    public function tableExists($tabel)
    {
        try {
            $this->db->query("SELECT 1 FROM $tabel LIMIT 1");
            $this->db->execute();
        }catch (Exception $e){
            echo 'creating '.$tabel.' table'.'<br>';
            return false;
        }
        return true;
    }

    public function mytalbes()
    {
        if (!$this->tableExists($this->tableuser))
        {
            try {
            $this->db->query("CREATE table $this->tableuser(
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(45),
                email VARCHAR(100),
                password VARCHAR(255),
                created_at datetime DEFAULT CURRENT_TIMESTAMP,
                verification_key VARCHAR(255),
                is_verfied boolean not null default 0,
                is_notifi boolean not null default 1,
                token_del VARCHAR(255)
            )");
            $this->db->execute();
            }catch(PDOException $e) {
                echo $e->getMessage();
            }
        }
        if (!$this->tableExists($this->phototable))
        {
            try {
            $this->db->query("CREATE table $this->phototable(
                id INT (11) AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(45),
                likes INT(11) default 0,
                photoname VARCHAR(100)
            )");
            $this->db->execute();
            }catch(PDOException $e) {
                echo $e->getMessage();
            }
        }
        if (!$this->tableExists($this->likestable))
        {
            try {
            $this->db->query("CREATE table $this->likestable(
                id INT (11) AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(45),
                photoname VARCHAR(100)
            )");
            $this->db->execute();
            }catch(PDOException $e) {
                echo $e->getMessage();
            }
        }

        if (!$this->tableExists($this->commentstable))
        {
            try {
            $this->db->query("CREATE table $this->commentstable(
                id INT (11) AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(45),
                photoname VARCHAR(100),
                comments VARCHAR(255)
            )");
            $this->db->execute();
            }catch(PDOException $e) {
                echo $e->getMessage();
            }
        }
    }
} 
$install = new setup;
