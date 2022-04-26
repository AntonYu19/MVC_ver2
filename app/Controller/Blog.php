<?php
namespace  App\Controller;

use App\Model\Message as MessageModel;
use Base\AbstractController;

class Blog extends AbstractController
{
    function indexAction()
    {
        if (!$this->user) {
            $this->redirect('/user/register');

        }
        if (isset($_POST['message'])) {
            $message = trim($_POST['message']);

            $image = (!empty($_FILES['image']['tmp_name'])) ? $_FILES['image'] : [];

            $success = true;
            if (!$message) {
                $this->view->assign('error', 'Messages не может быть пустым');
                $success = false;
            }

            if ($success) {
                $message = (new MessageModel())
                    ->setMessage($message)
                    ->setImage($image)
                    ->setUserId($_SESSION['id']);

                $message->save();
            }
        }

        return $this->view->render('Blog/index.phtml', [
            'user' => $this->user,
            'posts' =>MessageModel::getList()
        ]);

    }

    public function viewAction()
    {
        if (!$this->user) {
            $this->redirect('/user/register');
        }

        if (isset($_GET['id']) && $_GET['id']) {
            header('Content-Type: application/json');
            echo json_encode(MessageModel::getUserList((int)$_GET['id']));
            die;
        }
    }

    public function deleteAction()
    {
        if (!$this->user) {
            $this->redirect('/user/register');
        }

        MessageModel::getDelete((int)$_GET['id']);

        $this->redirect('/blog');
    }

    public function imageAction()
    {
        header('Content-type: image/png');
        $message = MessageModel::getById((int)$_GET['id']);

        include '\images\\' . $message['image'];
    }

}

