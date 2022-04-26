<?php
namespace App\Model;

use Base\AbstractModel;
use Base\Db;

class User extends AbstractModel
{

    private $user_id;
    private $name;
    private $email;
    private $password;
    private $createdAt;

    public  function  __construct($data = [])
    {
        if ($data) {
            $this->user_id = $data['user_id'];
            $this->name = $data['name'];
            $this->email = $data['email'];
            $this->password = $data['password'];
            $this->createdAt = $data['created_at'];
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt(string $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function save()
    {
        $db = Db::getInstance();
        $insert = "INSERT INTO users (`name`, `password`, `email`) VALUES  (
             :name, :password, :email
        )";
        $db->exec($insert, __METHOD__, [
            ':name' => $this->name,
            ':password' => $this->password,
            ':email' => $this->email
        ]);

        $user_id = $db->lastInsertId();
        $this->user_id = $user_id;

        return $user_id;
    }

    public static function  getById(int $user_id): ?self
    {
        $db = Db::getInstance();
        $select = "SELECT * FROM users WHERE user_id = $user_id";
        $data = $db->fetchOne($select, __METHOD__);

        if (!$data) {
            return null;
        }

       return new self($data);
    }

    public static function  getByEmail(string $email): ?self
    {
        $db = Db::getInstance();
        $select = "SELECT * FROM users WHERE `email` = :email";
        $data = $db->fetchOne($select, __METHOD__,[
            ':email' => $email
        ]);

        if (!$data) {
            return null;
        }

       return new self($data);
    }

    public static function getPasswordHash(string $password)
    {
        return sha1('.,jhkju,' . $password);
    }

    public static function getData(int $user_id)
    {
        $db = Db::getInstance();
        $select = "SELECT * FROM users WHERE user_id = $user_id";
        $data = $db->fetchOne($select, __METHOD__);

        if (!$data) {
            return null;
        }

        return $data;
    }

}
