<?php

namespace Config;

namespace Framework\Config;

class Config
{
    const DB_HOST = "localhost";
    const DB_USER = "root";
    const DB_PASSWORD = "";
    const DB_NAME = "todo_mvc";
    const DB_INSTANCE = "default";
    const DB_DRIVER = "mysql";

    const DEFAULT_CONTROLLER = "users";
    const DEFAULT_ACTION = "index";

    const DEFAULT_LAYOUT = "default";

    const AUTO_LOGIN_ON_REGISTER = true;

    const PARAMS_COUNT_MODEL_ONLY = 1;
    const PARAMS_COUNT_MODEL_AND_VIEW = 2;
    const PARAMS_COUNT_MODEL_VIEW_AND_LAYOUT = 3;
    const PARAMS_COUNT_MODEL_VIEW_LAYOUT_AND_IS_PARTIAL = 4;

    const VIEW_FOLDER = 'Views';
    const VIEW_EXTENSION = '.php';

    const CONTROLLERS_NAMESPACE = 'Framework\\Controllers\\';
    const CONTROLLERS_SUFFIX = 'Controller';
}