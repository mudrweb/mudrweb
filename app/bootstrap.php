<?php

/**
 * MUDRweb.cz bootstrap file.
 */
use Nette\Diagnostics\Debugger,        
	Nette\Application\Routers\Route,  
        Nette\Application\Routers\RouteList,
        Nette\Application\Routers\CliRouter,
        Nette\Environment,
        Nette\Utils\Strings,        
        Nette\Http\Url,
        Nette\Http\UrlScript,
        Nette\Http;     

use Basepresenter as BasePresenter;


// Load Nette Framework
require LIBS_DIR . '/Nette/loader.php';
require APP_DIR . '/components/class.chip_password_generator.php';
require APP_DIR . '/components/ExtraMethods.php';

// Configure application
$configurator = new Nette\Config\Configurator;

// Enable Nette Debugger for error visualisation & logging
$configurator->setProductionMode(TRUE);//$configurator::AUTO);
$configurator->enableDebugger(__DIR__ . '/../log');

// Enable RobotLoader - this will load all classes automatically
$configurator->setTempDirectory(__DIR__ . '/../temp');
$configurator->createRobotLoader()
	->addDirectory(APP_DIR)
	->addDirectory(LIBS_DIR)
	->register();

// Create Dependency Injection container from config.neon file
$configurator->addConfig(__DIR__ . '/config/config.neon');
$container = $configurator->createContainer();

// get host and prepare sharedBasePath
$host = Nette\Environment::getHttpRequest()->getUrl()->getHost();
Nette\Environment::setVariable('sharedBasePath', "http://".$host);

// Setup router
$router = $container->router;
if ($container->params['consoleMode']) {        
    if (DOMAIN == 'index.cronjobrunner1') {
        $router[] = new CliRouter(array(
                    'action' => 'CronJobRunner1:statusCheck',
                    ));
    }    
} else {
    
$router[] = new Route('index.php', array(            
            'presenter' => 'Default',
            'action' => 'default',
             ), Route::ONE_WAY);

$router[] = new Route('index.cronjobrunner1.php', array(                        
            'presenter' => 'CronJobRunner1',
            'action' => 'default',
             ), Route::ONE_WAY);

$router[] = new Route('/admin/[<lang cs>/]<presenter>[/<action>]', array(  
            'module' => 'Admin',
            'lang' => 'cs',
            'presenter' => 'Default',
            'action' => 'default',
        ));    

//$router[] = new Route('[<lang cs|en>/]<presenter>/<action>', array(  
//            'lang' => 'cs',            
//            'presenter' => 'Default',
//            'action' => 'default',            
//        ));    

$router[] = $route = new FilterRoute('[<lang cs>/]<presenter>[/<action>]', array(  
            'lang' => 'cs',           
//            'presenter' => 'Default',    
            'presenter' => array(
                Route::VALUE => 'Default',
                Route::FILTER_IN => null,
                Route::FILTER_OUT => null
            ),
            'action' => 'default',            
        ));

$route->addFilter('presenter', 'BasePresenter::urlToPresenter', 'BasePresenter::presenterToUrl');
}   

$container->application->run();