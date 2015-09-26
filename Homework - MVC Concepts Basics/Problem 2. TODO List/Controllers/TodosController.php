<?php

namespace Framework\Controllers;

use Framework\Helpers\Session;
use Framework\Library\BaseController;
use Framework\Library\View;
use Framework\Models\TodoModel;
use Framework\ViewModels\TodoAddInformation;
use Framework\Helpers\RouteService;
use Framework\ViewModels\TodosInformation;

class TodosController extends BaseController
{
    public function index() {
        $this->authorize();

        $viewModel = new TodosInformation();
        $viewModel->username = Session::get('username');

        try {
            $todoModel = new TodoModel();
            $todos = $todoModel->getTodos(Session::get('id'));

            $viewModel->todos = $todos;
        } catch(\Exception $e) {
            $viewModel->error = $e->getMessage();
            return new View($viewModel);
        }

        return new View($viewModel);
    }

    public function add() {
        $this->authorize();

        $viewModel = new TodoAddInformation();

        if(isset($_POST['add'], $_POST['todo_text'])) {
            try {
                $todo_text = $_POST['todo_text'];

                $todoModel = new TodoModel();

                $todoModel->add(Session::get('id'), $todo_text);
                RouteService::redirect('todos', '', true);
            } catch (\Exception $e) {
                $viewModel->error = $e->getMessage();
                return new View($viewModel);
            }
        }

        return new View($viewModel);
    }

    public function delete($id) {
        $this->authorize();

        if(!isset($id)) {
            RouteService::redirect('todos', '', true);
        }

        try {
            $todoModel = new TodoModel();

            $todoModel->delete(Session::get('id'), $id);

            RouteService::redirect('todos', '', true);
        } catch(\Exception $e) {
            RouteService::redirect('todos', '', true);
        }
    }
}