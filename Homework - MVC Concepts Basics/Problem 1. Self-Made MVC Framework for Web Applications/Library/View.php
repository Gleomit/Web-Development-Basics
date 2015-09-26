<?php

namespace Framework\Library;

use Framework\Config\Config;

class View
{
    public static $controllerName;
    public static $actionName;

    public function __construct() {
        $params = func_get_args();

        if(count($params) == Config::PARAMS_COUNT_MODEL_AND_VIEW) {
            $view = $params[0];
            $model = $params[1];
            $this->initModelView($model, $view);
        } elseif(count($params) == Config::PARAMS_COUNT_MODEL_ONLY){
            $model = isset($params[0]) ? $params[0] : null;
            $this->initModelOnly($model);
        } elseif(count($params) == Config::PARAMS_COUNT_MODEL_VIEW_AND_LAYOUT){
            $view = $params[0];
            $model = $params[1];
            $layout = $params[2];

            $this->initModelViewLayout($model, $view, $layout);
        } elseif(count($params) == Config::PARAMS_COUNT_MODEL_VIEW_LAYOUT_AND_IS_PARTIAL){
            $view = $params[0];
            $model = $params[1];
            $layout = $params[2];

            $this->initModelViewLayout($model, $view, $layout, true);
        }
    }

    private function initModelOnly($model) {
        require(Config::VIEW_FOLDER . '/layouts/' . Config::DEFAULT_LAYOUT . '/header' . Config::VIEW_EXTENSION);

        require Config::VIEW_FOLDER
            . DIRECTORY_SEPARATOR
            . self::$controllerName
            . DIRECTORY_SEPARATOR
            . self::$actionName
            . Config::VIEW_EXTENSION;

        require(Config::VIEW_FOLDER . '/layouts/' . Config::DEFAULT_LAYOUT . '/footer' . Config::VIEW_EXTENSION);
    }

    private function initModelView($model, $view) {
        require(Config::VIEW_FOLDER . '/layouts/' . Config::DEFAULT_LAYOUT . '/header' . Config::VIEW_EXTENSION);

        require self::VIEW_FOLDER
            . DIRECTORY_SEPARATOR
            . $view
            . self::VIEW_EXTENSION;

        require(Config::VIEW_FOLDER . '/layouts/' . Config::DEFAULT_LAYOUT . '/footer' . Config::VIEW_EXTENSION);
    }

    private function initModelViewLayout($model, $view, $layout, $isPartial = false) {

        if (!$isPartial) {
            require(Config::VIEW_FOLDER . '/layouts/' . $layout . '/header' . Config::VIEW_EXTENSION);
        }

        require Config::VIEW_FOLDER
            . DIRECTORY_SEPARATOR
            . $view
            . Config::VIEW_EXTENSION;

        if (!$isPartial) {
            require(Config::VIEW_FOLDER . '/layouts/' . $layout . '/footer' . Config::VIEW_EXTENSION);
        }
    }
}