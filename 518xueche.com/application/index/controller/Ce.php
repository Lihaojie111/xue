<?php
namespace app\index\controller;
use think\Controller;

class Ce extends Controller
{
    public function index()
    {     




include('application/extend/ceshi/Auth.php');
$Auth = new \Auth();
 $Auth->xx();
    }
}