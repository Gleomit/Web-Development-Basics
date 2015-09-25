<?php

namespace SoftUni\Controllers;

use SoftUni\Helpers\RouteService;
use SoftUni\Models\Building;

class BuildingsController extends Controller
{
    public function evolve($id) {
        if($this->isLogged()) {
            RouteService::redirect('users', 'buildings', true);
        }

        if(isset($id)) {
            try {
                $buildingModel = new Building();

                if($buildingModel->evolve($id)) {
                    RouteService::redirect('users', 'buildings', true);
                } else {
                    RouteService::redirect('users', 'buildings', true);
                }
            } catch(\Exception $e) {
                //return $e->getMessage();
                RouteService::redirect('users', 'buildings', true);
            }
        } else {
            RouteService::redirect('users', 'buildings', true);
        }
    }
}