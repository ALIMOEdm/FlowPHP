<?php
namespace Src\Controller;
use Classes\Core\Controller;
use Classes\Core\Request;
use Src\Model\File;

class DefaultController extends Controller{
    public function indexAction(){
        return $this->render('index', array('body' => 'main_page'), array());
    }
}
