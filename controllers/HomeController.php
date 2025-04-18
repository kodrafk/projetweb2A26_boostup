<?php
class HomeController {
    public function index() {
        header("Location: index.php?action=index");
        exit();
    }
}
?>