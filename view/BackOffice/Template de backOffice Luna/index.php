<?php
require_once 'controller/TacheController.php';

$controller = new TacheController();

$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

switch ($action) {
    case 'index':
        $controller->index();
        break;
    case 'create':
        $controller->create();
        break;
    case 'store':
        $controller->store($_POST);
        break;
    case 'edit':
        if ($id) {
            $controller->edit($id);
        } else {
            echo "ID non fourni.";
        }
        break;
    case 'update':
        if ($id) {
            $controller->update($id, $_POST);
        } else {
            echo "ID non fourni.";
        }
        break;
    case 'delete':
        if ($id) {
            $controller->delete($id);
        } else {
            echo "ID non fourni.";
        }
        break;
    default:
        echo "Action inconnue.";
}
