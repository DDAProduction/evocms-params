<?php
/**
 * Created by PhpStorm.
 * User: Pathologic
 * Date: 22.05.2015
 * Time: 20:43
 */

define('MODX_API_MODE', true);
define('IN_MANAGER_MODE', true);

include_once(__DIR__."/../../../index.php");
$modx->db->connect();
if (empty ($modx->config)) {
    $modx->getSettings();
}
if(!isset($_SESSION['mgrValidated'])){
    die();
}
$modx->invokeEvent('OnManagerPageInit',array('invokedBy'=>'Selector','tvId'=>(int)$_REQUEST['tvid'],'tvName'=>$_REQUEST['tvname']));

$mode = (isset($_REQUEST['mode']) && is_scalar($_REQUEST['mode'])) ? $_REQUEST['mode'] : null;
$out = null;

$controllerClass = isset($_REQUEST['tvname']) ? $_REQUEST['tvname'] : '';
$controllerClass = preg_replace('/[^A-Za-z_]/', '', $controllerClass);

if (!class_exists('\\DdaParams\\'.ucfirst($controllerClass.'Controller'), false)) {
    if (file_exists(MODX_BASE_PATH.'assets/tvs/ddaparams/lib/'.$controllerClass.'.controller.class.php')) {
        require_once (MODX_BASE_PATH.'assets/tvs/ddaparams/lib/'.$controllerClass.'.controller.class.php');
        $controllerClass = '\\DdaParams\\'.ucfirst($controllerClass.'Controller');
    } else {
        require_once (MODX_BASE_PATH . 'assets/tvs/ddaparams/lib/controller.class.php');
        $controllerClass = '\\DdaParams\\DdaParamsController';
    }
}

$controller = new $controllerClass($modx);
if($controller instanceof \DdaParams\DdaParamsController){
    if (!empty($mode) && method_exists($controller, $mode)) {
        $out = call_user_func_array(array($controller, $mode), array());
    }else{
        $out = call_user_func_array(array($controller, 'listing'), array());
    }
    $controller->callExit();
}

echo ($out = is_array($out) ? json_encode($out) : $out);