<?php
namespace  App\Controller;

use App\Model\User as UserModel;
use Base\AbstractController;

class User extends AbstractController
{
    public function loginAction()
    {
        $email = trim($_POST['email']);

        if ($email) {
            $password = $_POST['password'];
            $user = UserModel::getByEmail($email);
            if (!$user) {
                $this->view->assign('error', 'Неверный логин и пароль');
            }

            if ($user) {
                if ($user->getPassword() != UserModel::getPasswordHash($password)) {
                    $this->view->assign('error', 'Неверный логин и пароль');
                } else {
                    $_SESSION['id'] = $user->getId();

                    $this->redirect('/blog/index');
                }
            }
        }
        else {
            $this->view->assign('error', 'Введите имя и пароль');
        }

        return $this->view->render('User/register.phtml', [
            'user' => UserModel::getById((int)$_GET['id'])
        ]);
    }

    public function registerAction()
    {

        $name = (isset($_POST['name'])) ? trim($_POST['name']) : '';
        $email = (isset($_POST['email'])) ? trim($_POST['email']) : '';
        $password = (isset($_POST['password'])) ? trim($_POST['password']) : '';
        $password_repeat = (isset($_POST['password_repeat'])) ? trim($_POST['password_repeat']) : '';

        $success = true;

        if (isset($_POST['email'])) {

            if (!$name) {
                $this->view->assign('error', 'Имя не может быть пустым');
                $success = false;
            }

            if (!$email) {
                $this->view->assign('error', 'Email не может быть пустым');
                $success = false;
            }
            if (!$password) {
                $this->view->assign('error', 'Пароль не может быть пустым');
                $success = false;
            }
            if (mb_strlen($password) < 4) {
                $this->view->assign('error', 'длина пароля (не менее 4х символов)');
                $success = false;
            }
            if ($password != $password_repeat) {
                $this->view->assign('error', 'Пароли не совпадают');
                $success = false;
            }

            $user = UserModel::getByEmail($email);
            if ($user) {
                $this->view->assign('error', 'Пользователь с таким Email уже существует');
                $success = false;
            }

            if ($success) {
                $user = (new UserModel())
                    ->setName($name)
                    ->setEmail($email)
                    ->setPassword(UserModel::getPasswordHash($password));

                $user->save();

                $_SESSION['id'] = $user->getId();

                $this->setUser($user);

                $this->redirect('/blog/index');

            } else {
                $this->view->assign('error', 'Поля ввода не должны быть пустыми');


            }
        }


        return $this->view->render('User/register.phtml', [
            'user' => UserModel::getById((int)$_GET['id'])
        ]);
    }

    public function profileAction()
    {
        return $this->view->render('User/profile.phtml', [
            'user' => UserModel::getById((int)$_GET['id'])
        ]);

    }

    public function logoutAction()
    {
    session_destroy();

    $this->redirect('/user/login');

    }
}

