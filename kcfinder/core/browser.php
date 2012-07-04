<?php

/** This file is part of KCFinder project
  *
  *      @desc Browser actions class
  *   @package KCFinder
  *   @version 2.51
  *    @author Pavel Tzonkov <pavelc@users.sourceforge.net>
  * @copyright 2010, 2011 KCFinder Project
  *   @license http://www.opensource.org/licenses/gpl-2.0.php GPLv2
  *   @license http://www.opensource.org/licenses/lgpl-2.1.php LGPLv2
  *      @link http://kcfinder.sunhater.com
  */

class browser extends uploader {
    protected $action;
    protected $thumbsDir;
    protected $thumbsTypeDir;   
    protected $galleryPaths = array(
    //'galerie/zahlavi/praktik',
    //'galerie/zahlavi/pediatr',
    //'galerie/zahlavi/stomatolog',
    //'galerie/zahlavi/internista',
    //'galerie/zahlavi/angiolog',
    //'galerie/zahlavi/diabetolog',
    //'galerie/zahlavi/endokrinolog',
    //'galerie/zahlavi/gastroenterolog',
    'galerie/zahlavi/geriatr',
    'galerie/zahlavi/kardiolog',
    //'galerie/zahlavi/nefrolog',
    //'galerie/zahlavi/revmatolog',
    //'galerie/zahlavi/rehabilitace',
    //'galerie/zahlavi/hematolog',
    //'galerie/zahlavi/alergolog',
    //'galerie/zahlavi/neurolog',
    //'galerie/zahlavi/neonatolog',
    //'galerie/zahlavi/psychiatr',
    //'galerie/zahlavi/sexuolog',
    //'galerie/zahlavi/onkolog',
    'galerie/zahlavi/radiolog',
    //'galerie/zahlavi/dermatolog',
    //'galerie/zahlavi/chirurg',
    'galerie/zahlavi/gynekolog',
    //'galerie/zahlavi/ortoped',
    //'galerie/zahlavi/ORL',
    //'galerie/zahlavi/laborator',
    //'galerie/zahlavi/mamo',
    //'galerie/zahlavi/logoped',
    'galerie/zahlavi/praktik',
    //'galerie/zahlavi/pediatr',
    'galerie/zahlavi/stomatolog',
    //'galerie/zahlavi/internista',
    //'galerie/zahlavi/angiolog',
    //'galerie/zahlavi/diabetolog',
    //'galerie/zahlavi/endokrinolog',
    //'galerie/zahlavi/gastroenterolog',
    //'galerie/zahlavi/geriatr',
    //'galerie/zahlavi/kardiolog',
    //'galerie/zahlavi/nefrolog',
    //'galerie/zahlavi/revmatolog',
    //'galerie/zahlavi/rehabilitace',
    //'galerie/zahlavi/hematolog',
    //'galerie/zahlavi/alergolog',
    //'galerie/zahlavi/neurolog',
    //'galerie/zahlavi/neonatolog',
    //'galerie/zahlavi/psychiatr',
    //'galerie/zahlavi/sexuolog',
    //'galerie/zahlavi/onkolog',
    //'galerie/zahlavi/radiolog',
    //'galerie/zahlavi/dermatolog',
    //'galerie/zahlavi/chirurg',
    //'galerie/zahlavi/gynekolog',
    //'galerie/zahlavi/ortoped',
    //'galerie/zahlavi/ORL',
    //'galerie/zahlavi/laborator',
    //'galerie/zahlavi/mamo',
    //'galerie/zahlavi/logoped',
    //'galerie/zahlavi/praktik',
    //'galerie/zahlavi/pediatr',
    //'galerie/zahlavi/stomatolog',
    //'galerie/zahlavi/internista',
    //'galerie/zahlavi/angiolog',
    //'galerie/zahlavi/diabetolog',
    //'galerie/zahlavi/endokrinolog',
    //'galerie/zahlavi/gastroenterolog',
    //'galerie/zahlavi/geriatr',
    //'galerie/zahlavi/kardiolog',
    //'galerie/zahlavi/nefrolog',
    //'galerie/zahlavi/revmatolog',
    //'galerie/zahlavi/rehabilitace',
    //'galerie/zahlavi/hematolog',
    //'galerie/zahlavi/alergolog',
    //'galerie/zahlavi/neurolog',
    //'galerie/zahlavi/neonatolog',
    //'galerie/zahlavi/psychiatr',
    //'galerie/zahlavi/sexuolog',
    //'galerie/zahlavi/onkolog',
    //'galerie/zahlavi/radiolog',
    //'galerie/zahlavi/dermatolog',
    //'galerie/zahlavi/chirurg',
    //'galerie/zahlavi/gynekolog',
    //'galerie/zahlavi/ortoped',
    //'galerie/zahlavi/ORL',
    //'galerie/zahlavi/laborator',
    //'galerie/zahlavi/mamo',
    //'galerie/zahlavi/logoped',
    //'galerie/zahlavi/praktik',
    //'galerie/zahlavi/pediatr',
    //'galerie/zahlavi/stomatolog',
    //'galerie/zahlavi/internista',
    //'galerie/zahlavi/angiolog',
    //'galerie/zahlavi/diabetolog',
    //'galerie/zahlavi/endokrinolog',
    //'galerie/zahlavi/gastroenterolog',
    //'galerie/zahlavi/geriatr',
    //'galerie/zahlavi/kardiolog',
    //'galerie/zahlavi/nefrolog',
    //'galerie/zahlavi/revmatolog',
    //'galerie/zahlavi/rehabilitace',
    //'galerie/zahlavi/hematolog',
    //'galerie/zahlavi/alergolog',
    //'galerie/zahlavi/neurolog',
    //'galerie/zahlavi/neonatolog',
    //'galerie/zahlavi/psychiatr',
    //'galerie/zahlavi/sexuolog',
    //'galerie/zahlavi/onkolog',
    //'galerie/zahlavi/radiolog',
    //'galerie/zahlavi/dermatolog',
    //'galerie/zahlavi/chirurg',
    //'galerie/zahlavi/gynekolog',
    //'galerie/zahlavi/ortoped',
    //'galerie/zahlavi/ORL',
    //'galerie/zahlavi/laborator',
    //'galerie/zahlavi/mamo',
    //'galerie/zahlavi/logoped',
    //'galerie/zahlavi/praktik',
    //'galerie/zahlavi/pediatr',
    //'galerie/zahlavi/stomatolog',
    //'galerie/zahlavi/internista',
    //'galerie/zahlavi/angiolog',
    //'galerie/zahlavi/diabetolog',
    //'galerie/zahlavi/endokrinolog',
    //'galerie/zahlavi/gastroenterolog',
    //'galerie/zahlavi/geriatr',
    //'galerie/zahlavi/kardiolog',
    //'galerie/zahlavi/nefrolog',
    //'galerie/zahlavi/revmatolog',
    //'galerie/zahlavi/rehabilitace',
    //'galerie/zahlavi/hematolog',
    //'galerie/zahlavi/alergolog',
    //'galerie/zahlavi/neurolog',
    //'galerie/zahlavi/neonatolog',
    //'galerie/zahlavi/psychiatr',
    //'galerie/zahlavi/sexuolog',
    //'galerie/zahlavi/onkolog',
    //'galerie/zahlavi/radiolog',
    //'galerie/zahlavi/dermatolog',
    //'galerie/zahlavi/chirurg',
    //'galerie/zahlavi/gynekolog',
    //'galerie/zahlavi/ortoped',
    //'galerie/zahlavi/ORL',
    //'galerie/zahlavi/laborator',
    //'galerie/zahlavi/mamo',
    //'galerie/zahlavi/logoped',
    //'galerie/zahlavi/praktik',
    //'galerie/zahlavi/pediatr',
    //'galerie/zahlavi/stomatolog',
    //'galerie/zahlavi/internista',
    //'galerie/zahlavi/angiolog',
    //'galerie/zahlavi/diabetolog',
    //'galerie/zahlavi/endokrinolog',
    //'galerie/zahlavi/gastroenterolog',
    //'galerie/zahlavi/geriatr',
    //'galerie/zahlavi/kardiolog',
    //'galerie/zahlavi/nefrolog',
    //'galerie/zahlavi/revmatolog',
    //'galerie/zahlavi/rehabilitace',
    //'galerie/zahlavi/hematolog',
    //'galerie/zahlavi/alergolog',
    //'galerie/zahlavi/neurolog',
    //'galerie/zahlavi/neonatolog',
    //'galerie/zahlavi/psychiatr',
    //'galerie/zahlavi/sexuolog',
    //'galerie/zahlavi/onkolog',
    //'galerie/zahlavi/radiolog',
    //'galerie/zahlavi/dermatolog',
    //'galerie/zahlavi/chirurg',
    //'galerie/zahlavi/gynekolog',
    //'galerie/zahlavi/ortoped',
    //'galerie/zahlavi/ORL',
    //'galerie/zahlavi/laborator',
    //'galerie/zahlavi/mamo',
    //'galerie/zahlavi/logoped',        
    'galerie/zahlavi/ikony',        
    'galerie/zahlavi/leky',
    'galerie/zahlavi/oftalmolog'
    );    
    
    protected $colours = array(
    'bila',
    'cerna',
    'cervena',
    'zelena',
    'zluta',
    'modra'            
    );    
    
    protected $galleryPathsComplete = array(
    '/galerie/zahlavi/praktik/bila',
    //'/galerie/zahlavi/pediatr/bila',
    '/galerie/zahlavi/stomatolog/bila',
    //'/galerie/zahlavi/internista/bila',
    //'/galerie/zahlavi/angiolog/bila',
    //'/galerie/zahlavi/diabetolog/bila',
    //'/galerie/zahlavi/endokrinolog/bila',
    //'/galerie/zahlavi/gastroenterolog/bila',
    '/galerie/zahlavi/geriatr/bila',
    '/galerie/zahlavi/kardiolog/bila',
    //'/galerie/zahlavi/nefrolog/bila',
    //'/galerie/zahlavi/revmatolog/bila',
    //'/galerie/zahlavi/rehabilitace/bila',
    //'/galerie/zahlavi/hematolog/bila',
    //'/galerie/zahlavi/alergolog/bila',
    //'/galerie/zahlavi/neurolog/bila',
    //'/galerie/zahlavi/neonatolog/bila',
    //'/galerie/zahlavi/psychiatr/bila',
    //'/galerie/zahlavi/sexuolog/bila',
    //'/galerie/zahlavi/onkolog/bila',
    '/galerie/zahlavi/radiolog/bila',
    //'/galerie/zahlavi/dermatolog/bila',
    //'/galerie/zahlavi/chirurg/bila',
    '/galerie/zahlavi/gynekolog/bila',
    //'/galerie/zahlavi/ortoped/bila',
    //'/galerie/zahlavi/ORL/bila',
    //'/galerie/zahlavi/laborator/bila',
    //'/galerie/zahlavi/mamo/bila',
    //'/galerie/zahlavi/logoped/bila',
    '/galerie/zahlavi/praktik/cerna',
    //'/galerie/zahlavi/pediatr/cerna',
    '/galerie/zahlavi/stomatolog/cerna',
    //'/galerie/zahlavi/internista/cerna',
    //'/galerie/zahlavi/angiolog/cerna',
    //'/galerie/zahlavi/diabetolog/cerna',
    //'/galerie/zahlavi/endokrinolog/cerna',
    //'/galerie/zahlavi/gastroenterolog/cerna',
    '/galerie/zahlavi/geriatr/cerna',
    '/galerie/zahlavi/kardiolog/cerna',
    //'/galerie/zahlavi/nefrolog/cerna',
    //'/galerie/zahlavi/revmatolog/cerna',
    //'/galerie/zahlavi/rehabilitace/cerna',
    //'/galerie/zahlavi/hematolog/cerna',
    //'/galerie/zahlavi/alergolog/cerna',
    //'/galerie/zahlavi/neurolog/cerna',
    //'/galerie/zahlavi/neonatolog/cerna',
    //'/galerie/zahlavi/psychiatr/cerna',
    //'/galerie/zahlavi/sexuolog/cerna',
    //'/galerie/zahlavi/onkolog/cerna',
    '/galerie/zahlavi/radiolog/cerna',
    //'/galerie/zahlavi/dermatolog/cerna',
    //'/galerie/zahlavi/chirurg/cerna',
    '/galerie/zahlavi/gynekolog/cerna',
    //'/galerie/zahlavi/ortoped/cerna',
    //'/galerie/zahlavi/ORL/cerna',
    //'/galerie/zahlavi/laborator/cerna',
    //'/galerie/zahlavi/mamo/cerna',
    //'/galerie/zahlavi/logoped/cerna',
    '/galerie/zahlavi/praktik/cervena',
    //'/galerie/zahlavi/pediatr/cervena',
    '/galerie/zahlavi/stomatolog/cervena',
    //'/galerie/zahlavi/internista/cervena',
    //'/galerie/zahlavi/angiolog/cervena',
    //'/galerie/zahlavi/diabetolog/cervena',
    //'/galerie/zahlavi/endokrinolog/cervena',
    //'/galerie/zahlavi/gastroenterolog/cervena',
    '/galerie/zahlavi/geriatr/cervena',
    '/galerie/zahlavi/kardiolog/cervena',
    //'/galerie/zahlavi/nefrolog/cervena',
    //'/galerie/zahlavi/revmatolog/cervena',
    //'/galerie/zahlavi/rehabilitace/cervena',
    //'/galerie/zahlavi/hematolog/cervena',
    //'/galerie/zahlavi/alergolog/cervena',
    //'/galerie/zahlavi/neurolog/cervena',
    //'/galerie/zahlavi/neonatolog/cervena',
    //'/galerie/zahlavi/psychiatr/cervena',
    //'/galerie/zahlavi/sexuolog/cervena',
    //'/galerie/zahlavi/onkolog/cervena',
    '/galerie/zahlavi/radiolog/cervena',
    //'/galerie/zahlavi/dermatolog/cervena',
    //'/galerie/zahlavi/chirurg/cervena',
    '/galerie/zahlavi/gynekolog/cervena',
    //'/galerie/zahlavi/ortoped/cervena',
    //'/galerie/zahlavi/ORL/cervena',
    //'/galerie/zahlavi/laborator/cervena',
    //'/galerie/zahlavi/mamo/cervena',
    //'/galerie/zahlavi/logoped/cervena',
    '/galerie/zahlavi/praktik/zelena',
    //'/galerie/zahlavi/pediatr/zelena',
    '/galerie/zahlavi/stomatolog/zelena',
    //'/galerie/zahlavi/internista/zelena',
    //'/galerie/zahlavi/angiolog/zelena',
    //'/galerie/zahlavi/diabetolog/zelena',
    //'/galerie/zahlavi/endokrinolog/zelena',
    //'/galerie/zahlavi/gastroenterolog/zelena',
    '/galerie/zahlavi/geriatr/zelena',
    '/galerie/zahlavi/kardiolog/zelena',
    //'/galerie/zahlavi/nefrolog/zelena',
    //'/galerie/zahlavi/revmatolog/zelena',
    //'/galerie/zahlavi/rehabilitace/zelena',
    //'/galerie/zahlavi/hematolog/zelena',
    //'/galerie/zahlavi/alergolog/zelena',
    //'/galerie/zahlavi/neurolog/zelena',
    //'/galerie/zahlavi/neonatolog/zelena',
    //'/galerie/zahlavi/psychiatr/zelena',
    //'/galerie/zahlavi/sexuolog/zelena',
    //'/galerie/zahlavi/onkolog/zelena',
    '/galerie/zahlavi/radiolog/zelena',
    //'/galerie/zahlavi/dermatolog/zelena',
    //'/galerie/zahlavi/chirurg/zelena',
    '/galerie/zahlavi/gynekolog/zelena',
    //'/galerie/zahlavi/ortoped/zelena',
    //'/galerie/zahlavi/ORL/zelena',
    //'/galerie/zahlavi/laborator/zelena',
    //'/galerie/zahlavi/mamo/zelena',
    //'/galerie/zahlavi/logoped/zelena',
    '/galerie/zahlavi/praktik/zluta',
    //'/galerie/zahlavi/pediatr/zluta',
    '/galerie/zahlavi/stomatolog/zluta',
    //'/galerie/zahlavi/internista/zluta',
    //'/galerie/zahlavi/angiolog/zluta',
    //'/galerie/zahlavi/diabetolog/zluta',
    //'/galerie/zahlavi/endokrinolog/zluta',
    //'/galerie/zahlavi/gastroenterolog/zluta',
    '/galerie/zahlavi/geriatr/zluta',
    '/galerie/zahlavi/kardiolog/zluta',
    //'/galerie/zahlavi/nefrolog/zluta',
    //'/galerie/zahlavi/revmatolog/zluta',
    //'/galerie/zahlavi/rehabilitace/zluta',
    //'/galerie/zahlavi/hematolog/zluta',
    //'/galerie/zahlavi/alergolog/zluta',
    //'/galerie/zahlavi/neurolog/zluta',
    //'/galerie/zahlavi/neonatolog/zluta',
    //'/galerie/zahlavi/psychiatr/zluta',
    //'/galerie/zahlavi/sexuolog/zluta',
    //'/galerie/zahlavi/onkolog/zluta',
    '/galerie/zahlavi/radiolog/zluta',
    //'/galerie/zahlavi/dermatolog/zluta',
    //'/galerie/zahlavi/chirurg/zluta',
    '/galerie/zahlavi/gynekolog/zluta',
    //'/galerie/zahlavi/ortoped/zluta',
    //'/galerie/zahlavi/ORL/zluta',
    //'/galerie/zahlavi/laborator/zluta',
    //'/galerie/zahlavi/mamo/zluta',
    //'/galerie/zahlavi/logoped/zluta',
    '/galerie/zahlavi/praktik/modra',
    //'/galerie/zahlavi/pediatr/modra',
    '/galerie/zahlavi/stomatolog/modra',
    //'/galerie/zahlavi/internista/modra',
    //'/galerie/zahlavi/angiolog/modra',
    //'/galerie/zahlavi/diabetolog/modra',
    //'/galerie/zahlavi/endokrinolog/modra',
    //'/galerie/zahlavi/gastroenterolog/modra',
    '/galerie/zahlavi/geriatr/modra',
    '/galerie/zahlavi/kardiolog/modra',
    //'/galerie/zahlavi/nefrolog/modra',
    //'/galerie/zahlavi/revmatolog/modra',
    //'/galerie/zahlavi/rehabilitace/modra',
    //'/galerie/zahlavi/hematolog/modra',
    //'/galerie/zahlavi/alergolog/modra',
    //'/galerie/zahlavi/neurolog/modra',
    //'/galerie/zahlavi/neonatolog/modra',
    //'/galerie/zahlavi/psychiatr/modra',
    //'/galerie/zahlavi/sexuolog/modra',
    //'/galerie/zahlavi/onkolog/modra',
    '/galerie/zahlavi/radiolog/modra',
    //'/galerie/zahlavi/dermatolog/modra',
    //'/galerie/zahlavi/chirurg/modra',
    '/galerie/zahlavi/gynekolog/modra',
    //'/galerie/zahlavi/ortoped/modra',
    //'/galerie/zahlavi/ORL/modra',
    //'/galerie/zahlavi/laborator/modra',
    //'/galerie/zahlavi/mamo/modra',
    //'/galerie/zahlavi/logoped/modra',       
    '/galerie/zahlavi/ikony/bila',        
    '/galerie/zahlavi/ikony/cerna',
    '/galerie/zahlavi/ikony/cervena',
    '/galerie/zahlavi/ikony/zelena',           
    '/galerie/zahlavi/ikony/zluta',
    '/galerie/zahlavi/ikony/modra',        
    '/galerie/zahlavi/leky/bila',        
    '/galerie/zahlavi/leky/cerna',
    '/galerie/zahlavi/leky/cervena',
    '/galerie/zahlavi/leky/zelena',        
    '/galerie/zahlavi/leky/zluta',
    '/galerie/zahlavi/leky/modra',        
    '/galerie/zahlavi/oftalmolog/bila',        
    '/galerie/zahlavi/oftalmolog/cerna',
    '/galerie/zahlavi/oftalmolog/cervena',
    '/galerie/zahlavi/oftalmolog/zelena',        
    '/galerie/zahlavi/oftalmolog/zluta',
    '/galerie/zahlavi/oftalmolog/modra'             
    );    

    protected $galleryPathsIllustrativeImages = array(    
    'galerie/ilustracni_obrazky/3Dman',
    'galerie/ilustracni_obrazky/bunky',
    'galerie/ilustracni_obrazky/cviceni',
    'galerie/ilustracni_obrazky/deti',
    'galerie/ilustracni_obrazky/chirurgie',
    'galerie/ilustracni_obrazky/laborator',
    'galerie/ilustracni_obrazky/lekari',
    'galerie/ilustracni_obrazky/leky',
    'galerie/ilustracni_obrazky/ocni',
    'galerie/ilustracni_obrazky/pacienti',
    'galerie/ilustracni_obrazky/priroda',
    'galerie/ilustracni_obrazky/pristroje',
    'galerie/ilustracni_obrazky/RTG',
    'galerie/ilustracni_obrazky/veterina',
    'galerie/ilustracni_obrazky/vyziva_jidlo',
    'galerie/ilustracni_obrazky/zubni',
    'galerie/ilustracni_obrazky/pristroje',
    'galerie/ilustracni_obrazky/pristroje',
    'galerie/ilustracni_obrazky/pristroje',
    'galerie/ilustracni_obrazky/pristroje',
    'galerie/ilustracni_obrazky/pristroje',
    'galerie/ilustracni_obrazky/pristroje',
    'galerie/ilustracni_obrazky/pristroje'
    );
    
    protected $galleryPathsIllustrativeImagesPristroje = array (
    'galerie/ilustracni_obrazky/pristroje/_DOZY',
    'galerie/ilustracni_obrazky/pristroje/_HMOZDIR',
    'galerie/ilustracni_obrazky/pristroje/_JINE',
    'galerie/ilustracni_obrazky/pristroje/_MIKRO',
    'galerie/ilustracni_obrazky/pristroje/_STETO',
    'galerie/ilustracni_obrazky/pristroje/_TLAK',
    'galerie/ilustracni_obrazky/pristroje/_VAHY'  
    );
    
    protected $illustrativeImagesEndFolders = array(
    '3Dman',
    'bunky',
    'cviceni',
    'deti',
    'chirurgie',
    'laborator',
    'lekari',
    'leky',
    'ocni',
    'pacienti',
    'priroda',    
    'RTG',
    'veterina',
    'vyziva_jidlo',
    'zubni',
    '_DOZY',
    '_HMOZDIR',
    '_JINE',
    '_MIKRO',
    '_STETO',
    '_TLAK',
    '_VAHY'        
    );
    
    protected $galleryPathsIllustrativeImagesComplete = array(    
    '/galerie/ilustracni_obrazky/3Dman',
    '/galerie/ilustracni_obrazky/bunky',
    '/galerie/ilustracni_obrazky/cviceni',
    '/galerie/ilustracni_obrazky/deti',
    '/galerie/ilustracni_obrazky/chirurgie',
    '/galerie/ilustracni_obrazky/laborator',
    '/galerie/ilustracni_obrazky/lekari',
    '/galerie/ilustracni_obrazky/leky',
    '/galerie/ilustracni_obrazky/ocni',
    '/galerie/ilustracni_obrazky/pacienti',
    '/galerie/ilustracni_obrazky/priroda',
    '/galerie/ilustracni_obrazky/pristroje',
    '/galerie/ilustracni_obrazky/RTG',
    '/galerie/ilustracni_obrazky/veterina',
    '/galerie/ilustracni_obrazky/vyziva_jidlo',
    '/galerie/ilustracni_obrazky/zubni',
    '/galerie/ilustracni_obrazky/pristroje/_DOZY',
    '/galerie/ilustracni_obrazky/pristroje/_HMOZDIR',
    '/galerie/ilustracni_obrazky/pristroje/_JINE',
    '/galerie/ilustracni_obrazky/pristroje/_MIKRO',
    '/galerie/ilustracni_obrazky/pristroje/_STETO',
    '/galerie/ilustracni_obrazky/pristroje/_TLAK',
    '/galerie/ilustracni_obrazky/pristroje/_VAHY'
    );    

    protected $galleryPathsIcons = array(
    'galerie/ikony/KAT01',
    'galerie/ikony/KAT02',
    'galerie/ikony/KAT03',
    'galerie/ikony/KAT04',
    'galerie/ikony/KAT05',
    'galerie/ikony/KAT06',
    'galerie/ikony/KAT07',
    'galerie/ikony/KAT08',
    'galerie/ikony/KAT09',
    'galerie/ikony/KAT10',
    'galerie/ikony/KAT11',
    'galerie/ikony/KAT12',
    'galerie/ikony/KAT13',
    'galerie/ikony/KAT14',
    'galerie/ikony/KAT15'            
    );

    protected $iconsEndFolders = array (
    'KAT01',
    'KAT02',
    'KAT03',
    'KAT04',
    'KAT05',
    'KAT06',
    'KAT07',
    'KAT08',
    'KAT09',
    'KAT10',
    'KAT11',
    'KAT12',
    'KAT13',
    'KAT14',
    'KAT15'           
    );
    
    protected $galleryPathsIconsComplete = array(
    '/galerie/ikony/KAT01',
    '/galerie/ikony/KAT02',
    '/galerie/ikony/KAT03',
    '/galerie/ikony/KAT04',
    '/galerie/ikony/KAT05',
    '/galerie/ikony/KAT06',
    '/galerie/ikony/KAT07',
    '/galerie/ikony/KAT08',
    '/galerie/ikony/KAT09',
    '/galerie/ikony/KAT10',
    '/galerie/ikony/KAT11',
    '/galerie/ikony/KAT12',
    '/galerie/ikony/KAT13',
    '/galerie/ikony/KAT14',
    '/galerie/ikony/KAT15'            
    );    
    
    public function __construct() {
        parent::__construct();

        if (isset($this->post['dir'])) {
            $dir = $this->checkInputDir($this->post['dir'], true, false);
            if ($dir === false) unset($this->post['dir']);
            $this->post['dir'] = $dir;
        }

        if (isset($this->get['dir'])) {
            $dir = $this->checkInputDir($this->get['dir'], true, false);
            if ($dir === false) unset($this->get['dir']);
            $this->get['dir'] = $dir;
        }

        $thumbsDir = $this->config['uploadDir'] . "/" . $this->config['thumbsDir'];
        if ((
                !is_dir($thumbsDir) &&
                !@mkdir($thumbsDir, $this->config['dirPerms'])
            ) ||

            !is_readable($thumbsDir) ||
            !dir::isWritable($thumbsDir) ||
            (
                !is_dir("$thumbsDir/{$this->type}") &&
                !@mkdir("$thumbsDir/{$this->type}", $this->config['dirPerms'])
            )
        )
            $this->errorMsg("Cannot access or create thumbnails folder.");

        // gallery addon start    
        // vlastni_obrazky
        $galleryDir = $this->config['uploadDir'] . "/galerie/vlastni_obrazky";
        if (is_dir($galleryDir)) {            
        } else {
            @mkdir($galleryDir, $this->config['dirPerms']);
        }
        // ikony
        $galleryDir = $this->config['uploadDir'] . "/galerie/ikony";
        if (is_dir($galleryDir)) {            
        } else {
            @mkdir($galleryDir, $this->config['dirPerms']);
        }
            // ikony/categories
            foreach($this->galleryPathsIcons as $galleryPathsIcon) {
                $galleryDir = $this->config['uploadDir'] . '/' .$galleryPathsIcon;
                if (is_dir($galleryDir)) {            
                } else {
                    @mkdir($galleryDir, $this->config['dirPerms']);
                }            
            }        
        // ilustracni_obrazky
        $galleryDir = $this->config['uploadDir'] . "/galerie/ilustracni_obrazky";
        if (is_dir($galleryDir)) {            
        } else {
            @mkdir($galleryDir, $this->config['dirPerms']);
        }    
            // ilustracni_obrazky/categories
            foreach($this->galleryPathsIllustrativeImages as $galleryPathsIllustrativeImage) {
                $galleryDir = $this->config['uploadDir'] . '/' .$galleryPathsIllustrativeImage;
                if (is_dir($galleryDir)) {            
                } else {
                    @mkdir($galleryDir, $this->config['dirPerms']);
                }            
            }
                // ilustracni_obrazky/pristroje/
                foreach($this->galleryPathsIllustrativeImagesPristroje as $galleryPathsIllustrativeImagesPristroj) {
                    $galleryDir = $this->config['uploadDir'] . '/' .$galleryPathsIllustrativeImagesPristroj;
                    if (is_dir($galleryDir)) {            
                    } else {
                        @mkdir($galleryDir, $this->config['dirPerms']);
                    }            
                }            
        // zahlavi
        $galleryDir = $this->config['uploadDir'] . "/galerie/zahlavi";
        if (is_dir($galleryDir)) {            
        } else {
            @mkdir($galleryDir, $this->config['dirPerms']);
        }                
            // zahlavi/doctors
            foreach($this->galleryPaths as $galleryPath) {            
                $galleryDir = $this->config['uploadDir'] . '/' .$galleryPath;
                if (is_dir($galleryDir)) {            
                } else {
                    @mkdir($galleryDir, $this->config['dirPerms']);
                }
            }        
            // zahlavi/doctors/colours
            foreach($this->galleryPaths as $galleryPath) {            
                foreach($this->colours as $colour) {            
                    $galleryDir = $this->config['uploadDir'] . '/' .$galleryPath . '/' . $colour;
                    if (is_dir($galleryDir)) {            
                    } else {
                        @mkdir($galleryDir, $this->config['dirPerms']);
                    }
                }
            }                       
        // gallery addon end                
                
        $this->thumbsDir = $thumbsDir;
        $this->thumbsTypeDir = "$thumbsDir/{$this->type}";

        // Remove temporary zip downloads if exists
        $files = dir::content($this->config['uploadDir'], array(
            'types' => "file",
            'pattern' => '/^.*\.zip$/i'
        ));

        if (is_array($files) && count($files)) {
            $time = time();
            foreach ($files as $file)
                if (is_file($file) && ($time - filemtime($file) > 3600))
                    unlink($file);
        }

        if (isset($this->get['theme']) &&
            ($this->get['theme'] == basename($this->get['theme'])) &&
            is_dir("themes/{$this->get['theme']}")
        )
            $this->config['theme'] = $this->get['theme'];           
    }

    public function action() {
        $act = isset($this->get['act']) ? $this->get['act'] : "browser";
        if (!method_exists($this, "act_$act"))
            $act = "browser";
        $this->action = $act;
        $method = "act_$act";

        if ($this->config['disabled']) {
            $message = $this->label("You don't have permissions to browse server.");
            if (in_array($act, array("browser", "upload")) ||
                (substr($act, 0, 8) == "download")
            )
                $this->backMsg($message);
            else {
                header("Content-Type: text/plain; charset={$this->charset}");
                die(json_encode(array('error' => $message)));
            }
        }

        if (!isset($this->session['dir']))
            $this->session['dir'] = $this->type;
        else {
            $type = $this->getTypeFromPath($this->session['dir']);
            $dir = $this->config['uploadDir'] . "/" . $this->session['dir'];
            if (($type != $this->type) || !is_dir($dir) || !is_readable($dir))
                $this->session['dir'] = $this->type;
        }
        $this->session['dir'] = path::normalize($this->session['dir']);

        if ($act == "browser") {
            header("X-UA-Compatible: chrome=1");
            header("Content-Type: text/html; charset={$this->charset}");
        } elseif (
            (substr($act, 0, 8) != "download") &&
            !in_array($act, array("thumb", "upload"))
        )
            header("Content-Type: text/plain; charset={$this->charset}");

        $return = $this->$method();
        echo ($return === true)
            ? '{}'
            : $return;
    }

    protected function act_browser() {
        if (isset($this->get['dir']) &&
            is_dir("{$this->typeDir}/{$this->get['dir']}") &&
            is_readable("{$this->typeDir}/{$this->get['dir']}")
        )
            $this->session['dir'] = path::normalize("{$this->type}/{$this->get['dir']}");

        return $this->output();
    }

    protected function act_init() {
        $tree = $this->getDirInfo($this->typeDir);
        $tree['dirs'] = $this->getTree($this->session['dir']);
        if (!is_array($tree['dirs']) || !count($tree['dirs']))
            unset($tree['dirs']);
        $files = $this->getFiles($this->session['dir']);
        $dirWritable = dir::isWritable("{$this->config['uploadDir']}/{$this->session['dir']}");
        $data = array(
            'tree' => &$tree,
            'files' => &$files,
            'dirWritable' => $dirWritable
        );
        return json_encode($data);
    }

    protected function act_thumb() {
        $this->getDir($this->get['dir'], true);
        if (!isset($this->get['file']) || !isset($this->get['dir']))
            $this->sendDefaultThumb();
        $file = $this->get['file'];
        if (basename($file) != $file)
            $this->sendDefaultThumb();
        $file = "{$this->thumbsDir}/{$this->type}/{$this->get['dir']}/$file";
        if (!is_file($file) || !is_readable($file)) {
            $file = "{$this->config['uploadDir']}/{$this->type}/{$this->get['dir']}/" . basename($file);
            if (!is_file($file) || !is_readable($file))
                $this->sendDefaultThumb($file);
            $image = new gd($file);
            if ($image->init_error)
                $this->sendDefaultThumb($file);
            $browsable = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG);
            if (in_array($image->type, $browsable) &&
                ($image->get_width() <= $this->config['thumbWidth']) &&
                ($image->get_height() <= $this->config['thumbHeight'])
            ) {
                $type =
                    ($image->type == IMAGETYPE_GIF) ? "gif" : (
                    ($image->type == IMAGETYPE_PNG) ? "png" : "jpeg");
                $type = "image/$type";
                httpCache::file($file, $type);
            } else
                $this->sendDefaultThumb($file);
        }
        httpCache::file($file, "image/jpeg");
    }

    protected function act_expand() {
        return json_encode(array('dirs' => $this->getDirs($this->postDir())));
    }

    protected function act_chDir() {
        $this->postDir(); // Just for existing check
        $this->session['dir'] = $this->type . "/" . $this->post['dir'];
        $dirWritable = dir::isWritable("{$this->config['uploadDir']}/{$this->session['dir']}");
        return json_encode(array(
            'files' => $this->getFiles($this->session['dir']),
            'dirWritable' => $dirWritable
        ));
    }

    protected function act_newDir() {
        if (!$this->config['access']['dirs']['create'] ||
            !isset($this->post['dir']) ||
            !isset($this->post['newDir'])
        )
            $this->errorMsg("Unknown error.");

        $dir = $this->postDir();
        $newDir = $this->normalizeDirname(trim($this->post['newDir']));
        if (!strlen($newDir))
            $this->errorMsg("Please enter new folder name.");
        if (preg_match('/[\/\\\\]/s', $newDir))
            $this->errorMsg("Unallowable characters in folder name.");
        if (substr($newDir, 0, 1) == ".")
            $this->errorMsg("Folder name shouldn't begins with '.'");
        if (file_exists("$dir/$newDir"))
            $this->errorMsg("A file or folder with that name already exists.");
        if (!@mkdir("$dir/$newDir", $this->config['dirPerms']))
            $this->errorMsg("Cannot create {dir} folder.", array('dir' => $newDir));
        return true;
    }

    protected function act_renameDir() {
        if (!$this->config['access']['dirs']['rename'] ||
            !isset($this->post['dir']) ||
            !isset($this->post['newName'])
        )
            $this->errorMsg("Unknown error.");

        $dir = $this->postDir();
        $newName = $this->normalizeDirname(trim($this->post['newName']));
        if (!strlen($newName))
            $this->errorMsg("Please enter new folder name.");
        if (preg_match('/[\/\\\\]/s', $newName))
            $this->errorMsg("Unallowable characters in folder name.");
        if (substr($newName, 0, 1) == ".")
            $this->errorMsg("Folder name shouldn't begins with '.'");
        if (!@rename($dir, dirname($dir) . "/$newName"))
            $this->errorMsg("Cannot rename the folder.");
        $thumbDir = "$this->thumbsTypeDir/{$this->post['dir']}";
        if (is_dir($thumbDir))
            @rename($thumbDir, dirname($thumbDir) . "/$newName");
        return json_encode(array('name' => $newName));
    }

    protected function act_deleteDir() {
        if (!$this->config['access']['dirs']['delete'] ||
            !isset($this->post['dir']) ||
            !strlen(trim($this->post['dir']))
        )
            $this->errorMsg("Unknown error.");

        $dir = $this->postDir();

        if (!dir::isWritable($dir))
            $this->errorMsg("Cannot delete the folder.");
        $result = !dir::prune($dir, false);
        if (is_array($result) && count($result))
            $this->errorMsg("Failed to delete {count} files/folders.",
                array('count' => count($result)));
        $thumbDir = "$this->thumbsTypeDir/{$this->post['dir']}";
        if (is_dir($thumbDir)) dir::prune($thumbDir);
        return true;
    }

    protected function act_upload() {
        if (!$this->config['access']['files']['upload'] ||
            !isset($this->post['dir'])
        )
            $this->errorMsg("Unknown error.");

        $dir = $this->postDir();

        if (!dir::isWritable($dir))
            $this->errorMsg("Cannot access or write to upload folder.");

        if (is_array($this->file['name'])) {
            $return = array();
            foreach ($this->file['name'] as $i => $name) {
                $return[] = $this->moveUploadFile(array(
                    'name' => $name,
                    'tmp_name' => $this->file['tmp_name'][$i],
                    'error' => $this->file['error'][$i]
                ), $dir);
            }
            return implode("\n", $return);
        } else
            return $this->moveUploadFile($this->file, $dir);
    }

    protected function act_download() {
        $dir = $this->postDir();
        // gallery addon start              
        $dir_tmp = substr($dir, strlen($this->config['uploadDir']));                             
        if (in_array($dir_tmp, $this->galleryPathsComplete)) {
            $dir = "/CORE/mudrweb.cz/www/images/commonGallery/" . $dir_tmp;
        }                   
        else if (in_array($dir_tmp, $this->galleryPathsIllustrativeImagesComplete)) {
            $dir = "/CORE/mudrweb.cz/www/images/commonGallery/" . $dir_tmp;
        }     
        else if (in_array($dir_tmp, $this->galleryPathsIconsComplete)) {
            $dir = "/CORE/mudrweb.cz/www/images/commonGallery/" . $dir_tmp;
        }             
        // gallery addon end
        
        if (!isset($this->post['dir']) ||
            !isset($this->post['file']) ||
            (false === ($file = "$dir/{$this->post['file']}")) ||
            !file_exists($file) || !is_readable($file)                          
        )
            $this->errorMsg("Unknown error.");

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
        header("Content-Type: application/octet-stream");
        header('Content-Disposition: attachment; filename="' . str_replace('"', "_", $this->post['file']) . '"');
        header("Content-Transfer-Encoding:­ binary");
        header("Content-Length: " . filesize($file));
        readfile($file);
        die;
    }

    protected function act_rename() {
        $dir = $this->postDir();
        if (!$this->config['access']['files']['rename'] ||
            !isset($this->post['dir']) ||
            !isset($this->post['file']) ||
            !isset($this->post['newName']) ||
            (false === ($file = "$dir/{$this->post['file']}")) ||
            !file_exists($file) || !is_readable($file) || !file::isWritable($file)
        )
            $this->errorMsg("Unknown error.");

        if (isset($this->config['denyExtensionRename']) &&
            $this->config['denyExtensionRename'] &&
            (file::getExtension($this->post['file'], true) !==
                file::getExtension($this->post['newName'], true)
            )
        )
            $this->errorMsg("You cannot rename the extension of files!");

        $newName = $this->normalizeFilename(trim($this->post['newName']));
        if (!strlen($newName))
            $this->errorMsg("Please enter new file name.");
        if (preg_match('/[\/\\\\]/s', $newName))
            $this->errorMsg("Unallowable characters in file name.");
        if (substr($newName, 0, 1) == ".")
            $this->errorMsg("File name shouldn't begins with '.'");
        $newName = "$dir/$newName";
        if (file_exists($newName))
            $this->errorMsg("A file or folder with that name already exists.");
        $ext = file::getExtension($newName);
        if (!$this->validateExtension($ext, $this->type))
            $this->errorMsg("Denied file extension.");
        if (!@rename($file, $newName))
            $this->errorMsg("Unknown error.");

        $thumbDir = "{$this->thumbsTypeDir}/{$this->post['dir']}";
        $thumbFile = "$thumbDir/{$this->post['file']}";

        if (file_exists($thumbFile))
            @rename($thumbFile, "$thumbDir/" . basename($newName));
        return true;
    }

    protected function act_delete() {
        $dir = $this->postDir();
        if (!$this->config['access']['files']['delete'] ||
            !isset($this->post['dir']) ||
            !isset($this->post['file']) ||
            (false === ($file = "$dir/{$this->post['file']}")) ||
            !file_exists($file) || !is_readable($file) || !file::isWritable($file) ||
            !@unlink($file)
        )
            $this->errorMsg("Unknown error.");

        $thumb = "{$this->thumbsTypeDir}/{$this->post['dir']}/{$this->post['file']}";
        if (file_exists($thumb)) @unlink($thumb);
        return true;
    }

    protected function act_cp_cbd() {
        $dir = $this->postDir();
        if (!$this->config['access']['files']['copy'] ||
            !isset($this->post['dir']) ||
            !is_dir($dir) || !is_readable($dir) || !dir::isWritable($dir) ||
            !isset($this->post['files']) || !is_array($this->post['files']) ||
            !count($this->post['files'])
        )
            $this->errorMsg("Unknown error.");

        $error = array();
        foreach($this->post['files'] as $file) {
            $file = path::normalize($file);
            if (substr($file, 0, 1) == ".") continue;
            $type = explode("/", $file);
            $type = $type[0];
            if ($type != $this->type) continue;
            $path = "{$this->config['uploadDir']}/$file";
            $base = basename($file);
            $replace = array('file' => $base);
            $ext = file::getExtension($base);
            if (!file_exists($path))
                $error[] = $this->label("The file '{file}' does not exist.", $replace);
            elseif (substr($base, 0, 1) == ".")
                $error[] = "$base: " . $this->label("File name shouldn't begins with '.'");
            elseif (!$this->validateExtension($ext, $type))
                $error[] = "$base: " . $this->label("Denied file extension.");
            elseif (file_exists("$dir/$base"))
                $error[] = "$base: " . $this->label("A file or folder with that name already exists.");
            elseif (!is_readable($path) || !is_file($path))
                $error[] = $this->label("Cannot read '{file}'.", $replace);
            elseif (!@copy($path, "$dir/$base"))
                $error[] = $this->label("Cannot copy '{file}'.", $replace);
            else {
                if (function_exists("chmod"))
                    @chmod("$dir/$base", $this->config['filePerms']);
                $fromThumb = "{$this->thumbsDir}/$file";
                if (is_file($fromThumb) && is_readable($fromThumb)) {
                    $toThumb = "{$this->thumbsTypeDir}/{$this->post['dir']}";
                    if (!is_dir($toThumb))
                        @mkdir($toThumb, $this->config['dirPerms'], true);
                    $toThumb .= "/$base";
                    @copy($fromThumb, $toThumb);
                }
            }
        }
        if (count($error))
            return json_encode(array('error' => $error));
        return true;
    }

    protected function act_mv_cbd() {
        $dir = $this->postDir();
        if (!$this->config['access']['files']['move'] ||
            !isset($this->post['dir']) ||
            !is_dir($dir) || !is_readable($dir) || !dir::isWritable($dir) ||
            !isset($this->post['files']) || !is_array($this->post['files']) ||
            !count($this->post['files'])
        )
            $this->errorMsg("Unknown error.");

        $error = array();
        foreach($this->post['files'] as $file) {
            $file = path::normalize($file);
            if (substr($file, 0, 1) == ".") continue;
            $type = explode("/", $file);
            $type = $type[0];
            if ($type != $this->type) continue;
            $path = "{$this->config['uploadDir']}/$file";
            $base = basename($file);
            $replace = array('file' => $base);
            $ext = file::getExtension($base);
            if (!file_exists($path))
                $error[] = $this->label("The file '{file}' does not exist.", $replace);
            elseif (substr($base, 0, 1) == ".")
                $error[] = "$base: " . $this->label("File name shouldn't begins with '.'");
            elseif (!$this->validateExtension($ext, $type))
                $error[] = "$base: " . $this->label("Denied file extension.");
            elseif (file_exists("$dir/$base"))
                $error[] = "$base: " . $this->label("A file or folder with that name already exists.");
            elseif (!is_readable($path) || !is_file($path))
                $error[] = $this->label("Cannot read '{file}'.", $replace);
            elseif (!file::isWritable($path) || !@rename($path, "$dir/$base"))
                $error[] = $this->label("Cannot move '{file}'.", $replace);
            else {
                if (function_exists("chmod"))
                    @chmod("$dir/$base", $this->config['filePerms']);
                $fromThumb = "{$this->thumbsDir}/$file";
                if (is_file($fromThumb) && is_readable($fromThumb)) {
                    $toThumb = "{$this->thumbsTypeDir}/{$this->post['dir']}";
                    if (!is_dir($toThumb))
                        @mkdir($toThumb, $this->config['dirPerms'], true);
                    $toThumb .= "/$base";
                    @rename($fromThumb, $toThumb);
                }
            }
        }
        if (count($error))
            return json_encode(array('error' => $error));
        return true;
    }

    protected function act_rm_cbd() {
        if (!$this->config['access']['files']['delete'] ||
            !isset($this->post['files']) ||
            !is_array($this->post['files']) ||
            !count($this->post['files'])
        )
            $this->errorMsg("Unknown error.");

        $error = array();
        foreach($this->post['files'] as $file) {
            $file = path::normalize($file);
            if (substr($file, 0, 1) == ".") continue;
            $type = explode("/", $file);
            $type = $type[0];
            if ($type != $this->type) continue;
            $path = "{$this->config['uploadDir']}/$file";
            $base = basename($file);
            $replace = array('file' => $base);
            if (!is_file($path))
                $error[] = $this->label("The file '{file}' does not exist.", $replace);
            elseif (!@unlink($path))
                $error[] = $this->label("Cannot delete '{file}'.", $replace);
            else {
                $thumb = "{$this->thumbsDir}/$file";
                if (is_file($thumb)) @unlink($thumb);
            }
        }
        if (count($error))
            return json_encode(array('error' => $error));
        return true;
    }

    protected function act_downloadDir() {
        $dir = $this->postDir();
        if (!isset($this->post['dir']) || $this->config['denyZipDownload'])
            $this->errorMsg("Unknown error.");
        $filename = basename($dir) . ".zip";
        do {
            $file = md5(time() . session_id());
            $file = "{$this->config['uploadDir']}/$file.zip";
        } while (file_exists($file));
        new zipFolder($file, $dir);
        header("Content-Type: application/x-zip");
        header('Content-Disposition: attachment; filename="' . str_replace('"', "_", $filename) . '"');
        header("Content-Length: " . filesize($file));
        readfile($file);
        unlink($file);
        die;
    }

    protected function act_downloadSelected() {
        $dir = $this->postDir();
        if (!isset($this->post['dir']) ||
            !isset($this->post['files']) ||
            !is_array($this->post['files']) ||
            $this->config['denyZipDownload']
        )
            $this->errorMsg("Unknown error.");

        $zipFiles = array();
        foreach ($this->post['files'] as $file) {
            $file = path::normalize($file);
            if ((substr($file, 0, 1) == ".") || (strpos($file, '/') !== false))
                continue;
            $file = "$dir/$file";
            if (!is_file($file) || !is_readable($file))
                continue;
            $zipFiles[] = $file;
        }

        do {
            $file = md5(time() . session_id());
            $file = "{$this->config['uploadDir']}/$file.zip";
        } while (file_exists($file));

        $zip = new ZipArchive();
        $res = $zip->open($file, ZipArchive::CREATE);
        if ($res === TRUE) {
            foreach ($zipFiles as $cfile)
                $zip->addFile($cfile, basename($cfile));
            $zip->close();
        }
        header("Content-Type: application/x-zip");
        header('Content-Disposition: attachment; filename="selected_files_' . basename($file) . '"');
        header("Content-Length: " . filesize($file));
        readfile($file);
        unlink($file);
        die;
    }

    protected function act_downloadClipboard() {
        if (!isset($this->post['files']) ||
            !is_array($this->post['files']) ||
            $this->config['denyZipDownload']
        )
            $this->errorMsg("Unknown error.");

        $zipFiles = array();
        foreach ($this->post['files'] as $file) {
            $file = path::normalize($file);
            if ((substr($file, 0, 1) == "."))
                continue;
            $type = explode("/", $file);
            $type = $type[0];
            if ($type != $this->type)
                continue;
            $file = $this->config['uploadDir'] . "/$file";
            if (!is_file($file) || !is_readable($file))
                continue;
            $zipFiles[] = $file;
        }

        do {
            $file = md5(time() . session_id());
            $file = "{$this->config['uploadDir']}/$file.zip";
        } while (file_exists($file));

        $zip = new ZipArchive();
        $res = $zip->open($file, ZipArchive::CREATE);
        if ($res === TRUE) {
            foreach ($zipFiles as $cfile)
                $zip->addFile($cfile, basename($cfile));
            $zip->close();
        }
        header("Content-Type: application/x-zip");
        header('Content-Disposition: attachment; filename="clipboard_' . basename($file) . '"');
        header("Content-Length: " . filesize($file));
        readfile($file);
        unlink($file);
        die;
    }

    protected function act_check4Update() {
        if ($this->config['denyUpdateCheck'])
            return json_encode(array('version' => false));

        // Caching HTTP request for 6 hours
        if (isset($this->session['checkVersion']) &&
            isset($this->session['checkVersionTime']) &&
            ((time() - $this->session['checkVersionTime']) < 21600)
        )
            return json_encode(array('version' => $this->session['checkVersion']));

        $protocol = "http";
        $host = "kcfinder.sunhater.com";
        $port = 80;
        $path = "/checkVersion.php";

        $url = "$protocol://$host:$port$path";
        $pattern = '/^\d+\.\d+$/';
        $responsePattern = '/^[A-Z]+\/\d+\.\d+\s+\d+\s+OK\s*([a-zA-Z0-9\-]+\:\s*[^\n]*\n)*\s*(.*)\s*$/';

        // file_get_contents()
        if (ini_get("allow_url_fopen") &&
            (false !== ($ver = file_get_contents($url))) &&
            preg_match($pattern, $ver)

        // HTTP extension
        ) {} elseif (
            function_exists("http_get") &&
            (false !== ($ver = @http_get($url))) &&
            (
                (
                    preg_match($responsePattern, $ver, $match) &&
                    false !== ($ver = $match[2])
                ) || true
            ) &&
            preg_match($pattern, $ver)

        // Curl extension
        ) {} elseif (
            function_exists("curl_init") &&
            (false !== (   $curl = @curl_init($url)                                    )) &&
            (              @ob_start()                 ||  (@curl_close($curl) && false)) &&
            (              @curl_exec($curl)           ||  (@curl_close($curl) && false)) &&
            ((false !== (  $ver = @ob_get_clean()   )) ||  (@curl_close($curl) && false)) &&
            (              @curl_close($curl)          ||  true                         ) &&
            preg_match($pattern, $ver)

        // Socket extension
        ) {} elseif (function_exists('socket_create')) {
            $cmd =
                "GET $path " . strtoupper($protocol) . "/1.1\r\n" .
                "Host: $host\r\n" .
                "Connection: Close\r\n\r\n";

            if ((false !== (  $socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP)  )) &&
                (false !==    @socket_connect($socket, $host, $port)                    ) &&
                (false !==    @socket_write($socket, $cmd, strlen($cmd))                ) &&
                (false !== (  $ver = @socket_read($socket, 2048)                       )) &&
                preg_match($responsePattern, $ver, $match)
            )
                $ver = $match[2];

            if (isset($socket) && is_resource($socket))
                @socket_close($socket);
        }

        if (isset($ver) && preg_match($pattern, $ver)) {
            $this->session['checkVersion'] = $ver;
            $this->session['checkVersionTime'] = time();
            return json_encode(array('version' => $ver));
        } else
            return json_encode(array('version' => false));
    }

    protected function moveUploadFile($file, $dir) {
        $message = $this->checkUploadedFile($file);

        if ($message !== true) {
            if (isset($file['tmp_name']))
                @unlink($file['tmp_name']);
            return "{$file['name']}: $message";
        }

        $filename = $this->normalizeFilename($file['name']);
        $target = "$dir/" . file::getInexistantFilename($filename, $dir);

        if (!@move_uploaded_file($file['tmp_name'], $target) &&
            !@rename($file['tmp_name'], $target) &&
            !@copy($file['tmp_name'], $target)
        ) {
            @unlink($file['tmp_name']);
            return "{$file['name']}: " . $this->label("Cannot move uploaded file to target folder.");
        } elseif (function_exists('chmod'))
            chmod($target, $this->config['filePerms']);

        $this->makeThumb($target);
        return "/" . basename($target);
    }

    protected function sendDefaultThumb($file=null) {
        if ($file !== null) {
            $ext = file::getExtension($file);
            $thumb = "themes/{$this->config['theme']}/img/files/big/$ext.png";
        }
        if (!isset($thumb) || !file_exists($thumb))
            $thumb = "themes/{$this->config['theme']}/img/files/big/..png";
        header("Content-Type: image/png");
        readfile($thumb);
        die;
    }

    protected function getFiles($dir) {
        $thumbDir = "{$this->config['uploadDir']}/{$this->config['thumbsDir']}/$dir";
        $dir = "{$this->config['uploadDir']}/$dir";
        $return = array();
        $files = dir::content($dir, array('types' => "file"));
        if ($files === false)
            return $return;

        foreach ($files as $file) {
            $size = @getimagesize($file);
            if (is_array($size) && count($size)) {
                $thumb_file = "$thumbDir/" . basename($file);
                if (!is_file($thumb_file))
                    $this->makeThumb($file, false);
                $smallThumb =
                    ($size[0] <= $this->config['thumbWidth']) &&
                    ($size[1] <= $this->config['thumbHeight']) &&
                    in_array($size[2], array(IMAGETYPE_GIF, IMAGETYPE_PNG, IMAGETYPE_JPEG));
            } else
                $smallThumb = false;

            $stat = stat($file);
            if ($stat === false) continue;
            $name = basename($file);
            $ext = file::getExtension($file);
            $bigIcon = file_exists("themes/{$this->config['theme']}/img/files/big/$ext.png");
            $smallIcon = file_exists("themes/{$this->config['theme']}/img/files/small/$ext.png");
            $thumb = file_exists("$thumbDir/$name");
            $return[] = array(
                'name' => stripcslashes($name),
                'size' => $stat['size'],
                'mtime' => $stat['mtime'],
                'date' => @strftime($this->dateTimeSmall, $stat['mtime']),
                'readable' => is_readable($file),
                'writable' => file::isWritable($file),
                'bigIcon' => $bigIcon,
                'smallIcon' => $smallIcon,
                'thumb' => $thumb,
                'smallThumb' => $smallThumb
            );                      
        }
             
        // gallery addon start        
        $thumbDir_cgallery = array();
        $dir_cgallery = array();
        $files_cgallery = array();
        $commonGalleryPath = array();
        $tumbsPath = array();
        $return_cgallery = array();               
        // get dir starting from /galerie/...
        $tmp_dir = substr($dir, strlen($this->config['uploadDir']));            
        if (in_array($tmp_dir, $this->galleryPathsComplete)) {      
            $thumbDir_cgallery = "{$this->config['uploadDir']}/{$this->config['thumbsDir']}/galerie";
            $dir_cgallery = "/CORE/mudrweb.cz/www/images/commonGallery" . $tmp_dir;        
            $files_cgallery = dir::content($dir_cgallery, array('types' => "file"));                             
            $commonGalleryPath = 'commonGallery' . $tmp_dir;
            $tumbsPath = '/.thumbs' . $tmp_dir . '/';        
        } 
        else if (in_array($tmp_dir, $this->galleryPathsIllustrativeImagesComplete)) {                 
            $thumbDir_cgallery = "{$this->config['uploadDir']}/{$this->config['thumbsDir']}/galerie";
            $dir_cgallery = "/CORE/mudrweb.cz/www/images/commonGallery" . $tmp_dir;        
            $files_cgallery = dir::content($dir_cgallery, array('types' => "file"));                             
            $commonGalleryPath = 'commonGallery' . $tmp_dir;
            $tumbsPath = '/.thumbs' . $tmp_dir . '/';        
        }     
        else if (in_array($tmp_dir, $this->galleryPathsIconsComplete)) {                 
            $thumbDir_cgallery = "{$this->config['uploadDir']}/{$this->config['thumbsDir']}/galerie";
            $dir_cgallery = "/CORE/mudrweb.cz/www/images/commonGallery" . $tmp_dir;        
            $files_cgallery = dir::content($dir_cgallery, array('types' => "file"));                             
            $commonGalleryPath = 'commonGallery' . $tmp_dir;
            $tumbsPath = '/.thumbs' . $tmp_dir . '/';        
        }             
//        $myFile = "testFile.txt";
//        $fh = fopen($myFile, 'w');
//        fwrite($fh, count($files_cgallery)); 
//        fclose($fh);                          
        if ($files_cgallery !== false) {
            foreach ($files_cgallery as $file_cgallery) {
                $size = @getimagesize($file_cgallery);
                if (is_array($size) && count($size)) {
                    $thumb_file = "$thumbDir_cgallery/" . basename($file_cgallery);
                    if (!is_file($thumb_file))
                    $this->makeThumb_universal($file_cgallery, false, 1, $commonGalleryPath, $tumbsPath);
                    $this->makeThumb_universal($file_cgallery, false, 2, $commonGalleryPath, $tumbsPath);
                    $smallThumb =
                            ($size[0] <= $this->config['thumbWidth']) &&
                            ($size[1] <= $this->config['thumbHeight']) &&
                            in_array($size[2], array(IMAGETYPE_GIF, IMAGETYPE_PNG, IMAGETYPE_JPEG));
                } else
                    $smallThumb = false;

                $stat = stat($file_cgallery);
                if ($stat === false)
                    continue;
                $name = basename($file_cgallery);
                $ext = file::getExtension($file_cgallery);
                $bigIcon = file_exists("themes/{$this->config['theme']}/img/files/big/$ext.png");
                $smallIcon = file_exists("themes/{$this->config['theme']}/img/files/small/$ext.png");
                $thumb = file_exists("$thumbDir_cgallery/$name");
                $return_cgallery[] = array(
                    'name' => stripcslashes($name),
                    'size' => $stat['size'],
                    'mtime' => $stat['mtime'],
                    'date' => @strftime($this->dateTimeSmall, $stat['mtime']),
                    'readable' => is_readable($file_cgallery),
                    'writable' => false,
                    'bigIcon' => $bigIcon,
                    'smallIcon' => $smallIcon,
                    'thumb' => $thumb,
                    'smallThumb' => $smallThumb
                );
            }
        } else {
            return $return;
        }
        // gallery addon end
        
        return array_merge($return, $return_cgallery);
    }

    protected function getTree($dir, $index=0) {
        $path = explode("/", $dir);

        $pdir = "";
        for ($i = 0; ($i <= $index && $i < count($path)); $i++)
            $pdir .= "/{$path[$i]}";
        if (strlen($pdir))
            $pdir = substr($pdir, 1);

        $fdir = "{$this->config['uploadDir']}/$pdir";

        $dirs = $this->getDirs($fdir);

        if (is_array($dirs) && count($dirs) && ($index <= count($path) - 1)) {

            foreach ($dirs as $i => $cdir) {
                if ($cdir['hasDirs'] &&
                    (
                        ($index == count($path) - 1) ||
                        ($cdir['name'] == $path[$index + 1])
                    )
                ) {
                    $dirs[$i]['dirs'] = $this->getTree($dir, $index + 1);
                    if (!is_array($dirs[$i]['dirs']) || !count($dirs[$i]['dirs'])) {
                        unset($dirs[$i]['dirs']);
                        continue;
                    }
                }
            }
        } else
            return false;
        
        return $dirs;
    }

    protected function postDir($existent=true) {
        $dir = $this->typeDir;
        if (isset($this->post['dir']))
            $dir .= "/" . $this->post['dir'];
        if ($existent && (!is_dir($dir) || !is_readable($dir)))
            $this->errorMsg("Inexistant or inaccessible folder.");
        return $dir;
    }

    protected function getDir($existent=true) {
        $dir = $this->typeDir;
        if (isset($this->get['dir']))
            $dir .= "/" . $this->get['dir'];
        if ($existent && (!is_dir($dir) || !is_readable($dir)))
            $this->errorMsg("Inexistant or inaccessible folder.");
        return $dir;
    }

    protected function getDirs($dir) {
        $dirs = dir::content($dir, array('types' => "dir"));
        $return = array();
        if (is_array($dirs)) {
            $writable = dir::isWritable($dir);
            foreach ($dirs as $cdir) {
                $info = $this->getDirInfo($cdir);
                if ($info === false) continue;
                $info['removable'] = $writable && $info['writable'];
                $return[] = $info;
            }
        }
        
        return $return;
    }

    protected function getDirInfo($dir, $removable=false) {
        if ((substr(basename($dir), 0, 1) == ".") || !is_dir($dir) || !is_readable($dir))
            return false;
        $dirs = dir::content($dir, array('types' => "dir"));
        if (is_array($dirs)) {
            foreach ($dirs as $key => $cdir)
                if (substr(basename($cdir), 0, 1) == ".")
                    unset($dirs[$key]);
            $hasDirs = count($dirs) ? true : false;
        } else
            $hasDirs = false;              
        
        // gallery addon start        
        $stringAfterLastSlash = strrchr($dir, '/');
        $matchCounterColours = 0;       
        // colours as end folders
        if (strpos($dir, 'zahlavi')) {
            if (in_array(substr($stringAfterLastSlash, 1), $this->colours)) {
                $matchCounterColours++;
            }
        }
        // end folder from ilustracni_obrazky
        $matchCounterIllustrativeImagesEndFolders = 0;        
        if (strpos($dir, 'ilustracni_obrazky')) {        
            if (in_array(substr($stringAfterLastSlash, 1), $this->illustrativeImagesEndFolders)) {
                $matchCounterIllustrativeImagesEndFolders++;
            }        
        }
        // end folder from ikony
        $matchCounterIconsEndFolders = 0;        
        if (strpos($dir, 'ikony')) {        
            if (in_array(substr($stringAfterLastSlash, 1), $this->iconsEndFolders)) {
                $matchCounterIconsEndFolders++;
            }        
        }        
                     
        if ($matchCounterColours > 0 || $matchCounterIllustrativeImagesEndFolders > 0 || $matchCounterIconsEndFolders > 0) {
            $writable = dir::isWritable($dir);
            $info = array(
                'name' => stripslashes(basename($dir)),
                'readable' => true,
                'writable' => false,
                'removable' => false,
                'hasDirs' => false
            );
        }            
        else {   
        // gallery addon end            
            $writable = dir::isWritable($dir);
            $info = array(
                'name' => stripslashes(basename($dir)),
                'readable' => is_readable($dir),
                'writable' => $writable,
                'removable' => $removable && $writable && dir::isWritable(dirname($dir)),
                'hasDirs' => $hasDirs
            );
        }

        if ($dir == "{$this->config['uploadDir']}/{$this->session['dir']}")
            $info['current'] = true;

        return $info;
    }

    protected function output($data=null, $template=null) {
        if (!is_array($data)) $data = array();
        if ($template === null)
            $template = $this->action;

        if (file_exists("tpl/tpl_$template.php")) {
            ob_start();
            $eval = "unset(\$data);unset(\$template);unset(\$eval);";
            $_ = $data;
            foreach (array_keys($data) as $key)
                if (preg_match('/^[a-z\d_]+$/i', $key))
                    $eval .= "\$$key=\$_['$key'];";
            $eval .= "unset(\$_);require \"tpl/tpl_$template.php\";";
            eval($eval);
            return ob_get_clean();
        }

        return "";
    }

    protected function errorMsg($message, array $data=null) {
        if (in_array($this->action, array("thumb", "upload", "download", "downloadDir")))
            die($this->label($message, $data));
        if (($this->action === null) || ($this->action == "browser"))
            $this->backMsg($message, $data);
        else {
            $message = $this->label($message, $data);
            die(json_encode(array('error' => $message)));
        }
    }
}

?>