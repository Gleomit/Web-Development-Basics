<?php

namespace SoftUni\Controllers;

use SoftUni\Helpers\RouteService;
use SoftUni\Helpers\Session;
use SoftUni\Models\User;
use SoftUni\View;
use SoftUni\ViewModels\BuildingsInformation;
use SoftUni\ViewModels\LoginInformation;
use SoftUni\ViewModels\ProfileInformation;
use SoftUni\ViewModels\RegisterInformation;
use SoftUni\ViewModels\UserInformation;

class UsersController extends Controller
{
    private function initLogin($username, $password) {
        $userModel = new User();

        $userId = $userModel->login($username, $password);
        Session::set('id', $userId);
        RouteService::redirect('users', 'profile');
    }

    public function login() {
        if($this->isLogged()) {
            RouteService::redirect('users', 'profile', true);
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
            RouteService::redirect('users', 'profile', true);
        }

        $viewModel = new RegisterInformation();

        if(isset($_POST['username'], $_POST['password'])) {
            try {
                $username = $_POST['username'];
                $password = $_POST['password'];

                $userModel = new User();
                $userModel->register($username, $password);

                $this->initLogin($username, $password);
            } catch (\Exception $e){
                $viewModel->error = $e->getMessage();
                return new View($viewModel);
            }
        }

        return new View($viewModel);
    }

    public function profile() {
        if(!$this->isLogged()) {
            RouteService::redirect('users', 'login', true);
        }

        $viewModel = new ProfileInformation();
        $userModel = new User();

        $userInfo = $this->fillUserInformation($userModel);
        $viewModel->user = $userInfo;

        if(isset($_POST['username'], $_POST['password'], $_POST['confirm'])) {
            try {
                $userModel->edit($_POST['username'], $_POST['password'], $_POST['confirm']);

                $userInfo = $this->fillUserInformation($userModel);

                $viewModel->success = 'Successfully changed password';
            } catch (\Exception $e) {
                $viewModel->error = $e->getMessage();
                return new View($viewModel);
            }
        }

        $viewModel->user = $userInfo;

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

    private function fillUserInformation($model) {
        $userInfo = $model->getInfo(Session::get('id'));

        $result = new UserInformation(
            $userInfo['username'],
            $userInfo['id'],
            $userInfo['gold'],
            $userInfo['food']
        );

        return $result;
    }

    public function buildings() {
        if(!$this->isLogged()) {
            RouteService::redirect('users', 'login', true);
        }

        $viewModel = new BuildingsInformation();
        $userModel = new User();

        $buildings = $userModel->getBuildings();
        $userInfo = $userModel->getInfo(Session::get('id'));

        $viewModel->user = new UserInformation(
            $userInfo['username'],
            $userInfo['id'],
            $userInfo['gold'],
            $userInfo['food']
        );

        $viewModel->buildings = $buildings;

        return new View($viewModel);
    }
}