<?php

namespace AdminModule;

use Nette\Forms\Form;
use \AdminPresenter as AdminPresenter;

/**
 * AdminModule default presenter.
 *
 * @author     Zippo
 * @package    AdminModule
 */
class DefaultPresenter extends AdminPresenter {            
        
    private $header1;
    private $header2; 
    private $layout_name;
    private $layout_desc;
    private $subdomain;
    private $layout_group;
    private $title;
    private $description;
    private $keywords;
    private $headerImageData;
    private $colourSchemeData;
    
    /**
     * Check access and rights here only
     */
    public function startup()
    {
        parent::startup();
        $this->checkAccess(array('uživatel')); 
    }
    
    /**
     * renderDefault for default presenter. 
     */
    public function renderDefault() {                           
//        $salt = $this->extraMethods->generateSalt();
//        dump($salt);
//        dump(sha1('superman' . str_repeat('@Xw^~Mr4L60o;E1n', 10)));                                          
        
        $user = $this->db_users->getUserById($this->user->getId());
        $user_websiteData = $this->db_users->getUserWebsiteDataById($this->user->getId());                               
        
        if ($user && $user_websiteData) {                              
            // prepare data for output
            $this->subdomain = $user->subdomain;                         
            
            $this->layout_name = $user_websiteData->layout;            
            $this->layout_desc = $this->db_users->getLayout_descByLayout($user_websiteData->layout);            
            $this->layout_group = $user_websiteData->layout_group;
                        
            $this->header1 = $user_websiteData->header1;
            $this->header2 = $user_websiteData->header2;
            $this->headerImageData = $user_websiteData->headerImage;
            $this->colourSchemeData = $user_websiteData->colourScheme;
            
            $this->title = $user_websiteData->title;
            $this->description = $user_websiteData->description;
            $this->keywords = $user_websiteData->keywords;
            
            // prepare data for presenter
            $this->template->layout_desc = $this->layout_desc; 
            $this->template->subdomain = $user->subdomain;
        } else {
            throw new \Nette\Application\BadRequestException('Unable to load user profile or user websiteData (AdminModule - default presenter).', 404);
        }
    }       
    
    /**
     * Create form for default menuItem.
     * 
     * @return \Nette\Application\UI\Form 
     */
    protected function createComponentEditForm() {
        $form = new \Nette\Application\UI\Form;       
        $form->addHidden('userId', $this->user->getId());
        $form->addHidden('subdomain', $this->subdomain);
        
        // get list of available layouts for current user
        $layouts = $this->listLayouts($this->user->getId());   
        
        $form->addSelect('layouts', 'Výběr vzhledu:', $layouts)                
                ->setDefaultValue($this->layout_name)
                ->setAttribute('class', 'input_style_select');
        
        $form->addTextArea('header1', 'Hlavní nadpis:', 52, 40)                
//                ->addRule(Form::FILLED, 'Musíte zadat hlavní nadpis.')                
                ->addRule(Form::MAX_LENGTH, 'Hlavní nadpis: Maximální povolená délka hlavního nadpisu je 40 znaků.', 40)
                ->setValue($this->header1)
                ->setAttribute('class', 'textarea_default_header1');                                        
        $form->addTextArea('header2', 'Podnadpis:', 52, 40)                
//                ->addRule(Form::FILLED, 'Musíte zadat podnadpis.')                
                ->addRule(Form::MAX_LENGTH, 'Podnadpis: Maximální povolená délka podnadpisu je 40 znaků.', 40)
                ->setValue($this->header2)
                ->setAttribute('class', 'textarea_default_header2');     
        
        // header image
        // choose and set header image radiolist item
        $headerImage = null;
        if ($this->headerImageData) {
            $headerImage = 'userSpecific';
        } else {
            $headerImage = 'default';
        }        
        $form->addRadioList('headerImage', 'Použít obrázek:', array(
                'default' => 'výchozí',
                'userSpecific' => 'vlastní (z galerie)',
                ))
                ->setDefaultValue($headerImage)
                ->setAttribute('class', 'headerImage');
        $form->addHidden('headerImageData', $this->headerImageData);

        // colour scheme
        // choose and set colour scheme radiolist item        
        $colourScheme = null;
        if ($this->colourSchemeData) {
            $colourScheme = 'userSpecific';
        } else {
            $colourScheme = 'default';
        }          
        $form->addRadioList('colourScheme', 'Barva textu (hlavní nadpis, podnadpis):', array(
                'default' => 'výchozí',
                'userSpecific' => 'vlastní',
                ))
                ->setDefaultValue($colourScheme)
                ->setAttribute('class', 'headerImage');        
        
        // preprocess colours from DB format #colour1;#colour2;...
        $colours = $this->colourSchemeData;
        $colours = explode(";", $colours);
        
        foreach($colours as $key => $value) { 
            if($value == "") { 
                unset($colours[$key]); 
            } 
        }           
        if (isset($colours) && sizeof($colours) == 1) {
            $colour1 = $colours[0];
            $colour2 = '';
        } elseif (isset($colours) && sizeof($colours) == 2) {
            $colour1 = $colours[0];
            $colour2 = $colours[1];
        }
        else {
            $colour1 = '#000000';
            $colour2 = '#000000';
        }
        // set colour1
        $form->addHidden('colourSchemeData', $this->colourSchemeData);
        $form->addText('colour1', 'Barva hlavního nadpisu:')                
                ->addRule(Form::FILLED, 'Musíte vybrat barvu hlavního nadpisu.')                                                
                ->setDefaultValue($colour1)
                ->setAttribute('class', 'color-picker');                   

        $form->addText('colour2', 'Barva podnadpisu:')                
                ->addRule(Form::FILLED, 'Musíte vybrat barvu podnadpisu.')                                                
                ->setDefaultValue($colour2)
                ->setAttribute('class', 'color-picker');                           
        
        $form->addTextArea('title', 'Název stránky (title):', 52, 64)                
//                ->addRule(Form::FILLED, 'Musíte zadat název stránky.')                
//                ->addRule(Form::MIN_LENGTH, 'Minimální požadovaná délka názvu stránky je 10 znaků.', 10)
                ->addRule(Form::MAX_LENGTH, 'Název stránky: Maximální povolená délka názvu stránky je 64 znaků.', 64)
                ->setValue($this->title)
                ->setAttribute('class', 'textarea_default_title');
        $form->addTextArea('description', 'Popis stránky (description):', 52, 40)                
//                ->addRule(Form::FILLED, 'Musíte zadat popis stránky.')   
//                ->addRule(Form::MIN_LENGTH, 'Minimální požadovaná délka popisu stránky je 50 znaků.', 50)
                ->addRule(Form::MAX_LENGTH, 'Popis stránky: Maximální povolená délka popisu stránky je 149 znaků.', 149)
                ->setValue($this->description)
                ->setAttribute('class', 'textarea_default_desc');        
        $form->addTextArea('keywords', 'Klíčová slova (keywords):', 52, 40)                
//                ->addRule(Form::FILLED, 'Musíte zadat klíčová slova stránky.')                                
                ->setValue($this->keywords)
                ->setAttribute('class', 'textarea_default_keywords');                
        
        $form->addSubmit('submit', 'Uložit změny')
                ->setAttribute('class', 'button')
                ->onClick[] = callback($this, 'saveChanges');               
        
        return $form;
    }
    
    /**
     * Save changes for current menuItem.
     * 
     * @param \Nette\Forms\Controls\Button $button 
     */
    public function saveChanges(\Nette\Forms\Controls\Button $button)
    {   
        // get data from form
        $data = $button->form->getValues();   
        
        // prepare data for update
        if ($data->headerImage == 'default') {
            $headerImage = null;
        } else {
            $headerImage = $data->headerImageData;
            
            // update header.css
            $wwwDir = WWW_DIR;
            $pathToUserSpecificDir = $wwwDir . '/' . $data->subdomain;
            
            // open header.css file
            $indexFile = fopen($pathToUserSpecificDir . '/header.css', 'w');        
            fclose($indexFile);

            // update its content
            $updatedIndexContent = "#header { background-image: url(\"$data->headerImageData\"); width: 850px; height: 150px; background-size: auto 150px; background-repeat: no-repeat; padding: 0px; margin: 0 auto }";

            $updatedFile = fopen($pathToUserSpecificDir . '/header.css', 'w+');
            fwrite($updatedFile, $updatedIndexContent);
            fclose($updatedFile);            
        }
        
        if ($data->colourScheme == 'default') {
            $colourScheme = null;
        } else {
            $colourScheme = $data->colour1 . ';' . $data->colour2 . ';';
            
            // update colour_scheme.css
            $wwwDir = WWW_DIR;
            $pathToUserSpecificDir = $wwwDir . '/' . $data->subdomain;
            
            // open colour_scheme.css file
            $indexFile = fopen($pathToUserSpecificDir . '/colour_scheme.css', 'w');        
            fclose($indexFile);

            // update its content            
            $updatedIndexContent = "#header h1 { color: ". $data->colour1 . "; } #header h2 { color: ". $data->colour2 . "; }";
                
            $updatedFile = fopen($pathToUserSpecificDir . '/colour_scheme.css', 'w+');
            fwrite($updatedFile, $updatedIndexContent);
            fclose($updatedFile);                
        }
        
        $dataArray = array(intval($data->userId), $data->header1, $data->header2, 
                     $data->layouts, $data->title, $data->description, $data->keywords,
                     $headerImage, $colourScheme);                                                               

        // update current user data + lastChange timestamp for user website        
        $this->db_users->updateUserWebsiteData($dataArray);
        $this->db_users->saveLastChangesForUserWebsiteData($this->user->getId());
        $this->flashMessage('Změny byly úspěšně uloženy.', 'info');
        $this->redirect('this');
    }   
}
