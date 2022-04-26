<?php

namespace App\Model;

use Base\AbstractModel;
use Base\Db;

class Message extends AbstractModel
{
    private $user_id;
    private $message;
    private $image;

    public function __construct($data = [])
    {
        if ($data) {
            $this->user_id = $data['user_id'];
            $this->message = $data['message'];
        }
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message)
    {
        $this->message = $message;

        return $this;
    }

    public function setImage(array $img)
    {
        if (!empty($img)) {
            $imagePath = DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
            $imageName = time();

            if ($img['type'] == 'image/jpeg') {
                $m_img = imagecreatefromjpeg($img['tmp_name']);
                imagejpeg($m_img, PROJECT_ROOT_DIR . $imagePath . $imageName . '.jpg');
                $image = $imageName . '.jpg';

            } else {
                if ($img['type'] == 'image/png') {
                    $m_img = imagecreatefrompng($img['tmp_name']);
                    imagepng($m_img, PROJECT_ROOT_DIR . $imagePath . $imageName . '.png');
                    $image = $imageName . '.png';

                } else {
                    $image = null;

                }
            }
        }

        $this->image = $image;

        return $this;
    }

    public static function getById(int $message_id)
    {
        $db = Db::getInstance();
        $select = "SELECT * FROM messages WHERE message_id = $message_id";
        $data = $db->fetchOne($select, __METHOD__);

        if (!$data) {
            return null;
        }

        return $data;
    }

    public function getId()
    {
        return $this->message_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId(int $user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    public static function getDelete(int $message_id)
    {
        $db = Db::getInstance();
        $select = "DELETE FROM messages WHERE message_id = $message_id";
        $data = $db->fetchOne($select, __METHOD__);
    }

    public function save()
    {
        $db = Db::getInstance();
        $insert = "INSERT INTO messages (`message`, `image`, `user_id`) VALUES (
            :message, :image, :user_id
        )";
        $db->exec($insert, __METHOD__, [
            ':message' => $this->message,
            ':image'   => $this->image,
            ':user_id' => $this->user_id
        ]);

        $message_id = $db->lastInsertId();
        $this->message_id = $message_id;

        return $message_id;
    }

    public static function getList()
    {
        $db = Db::getInstance();
        $select = "SELECT * FROM messages INNER JOIN users 
		ON messages.user_id=users.user_id ORDER BY messages.created_at DESC LIMIT 20";
        $data = $db->fetchAll($select, __METHOD__);

        if (!$data) {
            return [];
        }

        return $data;
    }

    public static function getUserList(int $user_id)
    {
        $db = Db::getInstance();
        $select = "SELECT * FROM messages WHERE user_id=" . $user_id . " ORDER BY messages.created_at DESC LIMIT 20";
        $data = $db->fetchAll($select, __METHOD__);

        if (!$data) {
            return [];
        }

        return $data;
    }
}
