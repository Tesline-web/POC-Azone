<?php
class Router {
    public function route() {
        $controller = isset($_GET['controller']) ? $_GET['controller'] : 'home';
        $action = isset($_GET['action']) ? $_GET['action'] : 'index';

        $controllerName = ucfirst($controller) . 'Controller';
        $controllerFile = CONTROLLERS_PATH . $controllerName . '.php';

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controller = new $controllerName();
            if (method_exists($controller, $action)) {
                $controller->$action();
            } else {
                $this->error404();
            }
        } else {
            $this->error404();
        }
    }

    private function error404() {
        header("HTTP/1.0 404 Not Found");
        require_once VIEWS_PATH . '404.php';
    }
}
