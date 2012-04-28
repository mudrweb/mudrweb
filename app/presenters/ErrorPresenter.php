<?php

use Nette\Diagnostics\Debugger,
	Nette\Application as NA;

/**
 * Error presenter.
 *
 * @author     Zippo
 * @package    MainModule
 */
class ErrorPresenter extends BasePresenter
{
    /**
     * @param  Exception
     * @return void
     */
    public function renderDefault($exception) {
        if ($this->isAjax()) { // AJAX request? Just note this error in payload.            
            $this->payload->error = TRUE;
            $this->terminate();
            
        } elseif ($exception instanceof NA\BadRequestException) {            
            $code = $exception->getCode();            
            $params = $this->application->requests[0]->getParameters();            

            if (isset($params['lang'])) {
                $lang = $params['lang'];            
                $this->setView(in_array($code, array(403, 404, 405, 410, 500)) ? '/'.$lang.'/'.$code : '4xx'); // load template 403.latte or 404.latte or ... 4xx.latte            
            } else {
                $this->setView(in_array($code, array(403, 404, 405, 410, 500)) ? $code : '4xx'); // load template 403.latte or 404.latte or ... 4xx.latte                                        
            }
//            $this->setView(in_array($code, array(403, 404, 405, 410, 500)) ? $code : '4xx'); // load template 403.latte or 404.latte or ... 4xx.latte                        
            Debugger::log("HTTP code $code", 'access'); // log to access.log            
        } else {       
            $params = $this->application->requests[0]->getParameters();
            if (isset($params['lang'])) {
                $lang = $params['lang'];    
                $this->setView('/'.$lang.'/500'); // load template 500.latte
            } else {
                $this->setView('500'); // load template 500.latte
            }
            Debugger::log($exception, Debugger::ERROR); // and log exception
        }
    }
}
