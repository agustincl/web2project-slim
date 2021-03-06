<?php
require_once 'vendor/autoload.php';

//NOTE: This must be configured manually:
$web2project_folder = '../web2project';

include_once 'web2project/web2project.php';

$app = new Slim(
            array('debug' => true)
        );

//TODO: figure out authentication
//$GLOBALS['acl'] = new w2p_Mocks_Permissions();
$GLOBALS['acl'] = null;
$AppUI = is_object($AppUI) ? $AppUI : new w2p_Core_CAppUI();

$app->options('/', function() use ($app) {
    $action = new web2project_API_Root($app);
    $app = $action->options();
});

$app->get('/:module(/(:id(/:submodule(/))))', function($module, $id = 0, $submodule = '') use ($app) {
    $action = new web2project_API_Get($app, $module, $id, $submodule);
    $app = $action->process();
});

$app->post('/:module(/)', function($module) use ($app) {
    $action = new web2project_API_Post($app, $module);
    $app = $action->process();
});

$app->delete('/:module/:id', function($module, $id) use ($app) {
    $action = new web2project_API_Delete($app, $module, $id);
    $app = $action->process();
});

$app->options('/:module(/)', function($module) use ($app) {
    $action = new web2project_API_Options($app, $module);
    $app = $action->process();
});

/*
 * NOTE:
 *    Generally, a PUT is supposed to accept the *entire* resource including the
 *    unchanged fields while a PATCH should effectively be a "diff." For the time
 *    being, PUT behaves as a PATCH. Get over it.
 */
$app->put('/:module/:id', function($module, $id) use ($app) {
    $action = new web2project_API_Put($app, $module, $id);
    $app = $action->process();
});

$app->run();
echo "\n\n";