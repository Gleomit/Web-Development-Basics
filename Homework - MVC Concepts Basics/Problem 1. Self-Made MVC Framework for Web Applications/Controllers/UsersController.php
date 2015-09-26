<?php

namespace Framework\Controllers;

use Framework\Helpers\RouteService;
use Framework\Helpers\Session;
use Framework\Library\BaseController;
use Framework\Library\View;
use Framework\Models\UserModel;
use Framework\ViewModels\LoginInformation;
use Framework\ViewModels\RegisterInformation;

class UsersController extends BaseController
{
    private function initLogin($username, $password) {
        $userModel = new UserModel();

        $userId = $userModel->login($username, $password);
        Session::set('id', $userId);
    }

    public function login() {
        if($this->isLogged()) {
            //redirect to some page
        }

        $viewModel = new LoginInformation();

        if(isset($_POST['username'], $_POST['password'])) {
            try {
                $username = $_POST['username'];
                $password = $_POST['password'];

                $this->initLogin($username, $password);
            } catch (\Exception $e){
                $viewModel->error = $e->getMessage();
                return new View($viewModel);
            }
        }

        return new View($viewModel);
    }

    public function register() {
        if($this->isLogged()) {
            //redirect to some page
        }

        $viewModel = new RegisterInformation();

        if(isset($_POST['username'], $_POST['password'])) {
            try {
                $username = $_POST['username'];
                $password = $_POST['password'];

                $userModel = new UserModel();
                $userModel->register($username, $password);

                //$this->initLogin($username, $password);
            } catch (\Exception $e){
                $viewModel->error = $e->getMessage();
                return new View($viewModel);
            }
        }

        return new View($viewModel);
    }

    public function logout() {
        if($this->isLogged()) {
            Session::unsetKey('id');
            RouteService::redirect('users', 'login', true);
        } else {
            RouteService::redirect('users', 'login', true);
        }
    }
}