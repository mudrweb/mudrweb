<?php
namespace TextCaptcha;

require_once __DIR__."/backend/ArrayBackend.php";
require_once __DIR__."/other/IQuestion.php";

/**
 * Class for anti-spam protection.
 * It is easy and logic turing test.
 * @author Jan Drábek
 * @version 1.0
 * @license GNU-GPLv3
 */
class TextCaptcha extends \Nette\Forms\Controls\TextInput {
	
	/** @var \Nette\Http\Session */
	private static $session;
	
	/** @var IBackend */
	private static $backend;
	
	/** @var string */
	private static $language;
	
	/** @var IQuestion */
	private $question;
	
	/**
	 * Get current question
	 * @return IQuestion
	 */
	public function getQuestion() {
		return $this->question;
	}
	
	/**
	 * Set current question
	 * @param IQuestion $question 
	 */
	public function setQuestion (IQuestion $question) {
		$this->question = $question;
	}
	
	/**
	 * Validate Text Captcha
	 * @param TextCaptcha $control
	 * @return bool
	 */
	public static function validate(TextCaptcha $control) {
		// Prepare name
		$name = $control->getForm()->getName()."antispamtextcaptcha";                
		// Check if question is set
		if(!isSet(self::$session[$name])) {
			// No question found -> get new question -> fail
			self::$session[$name] = serialize(self::$backend->get(self::$language));
			return false;
		}
		// Get question and try to check
		$question = unserialize(self::$session[$name]);
		$ret = $question->check($control->getValue());
               
		if($ret) {
			// Validation OK
			return true;
		} else {                                           
			// Validation failed -> change question -> fail
			$question = self::$backend->get(self::$language);
			self::$session[$name] = serialize($question);
			$control->setQuestion($question);
			$control->setValue("");
			return false;
		}
	}
	
	/**
	 * Add Text Captcha anti spam protection to form
	 * @param \Nette\Forms\Form $form
	 * @return TextCaptcha
	 */
	public static function addTextCaptcha(\Nette\Application\UI\Form $form) {
		// Prepare name
		$name = $form->getName()."antispamtextcaptcha";
		// Check for duplicity
		if(isSet($form[$name])) {
			throw new \Nette\InvalidStateException("This form already contains text captcha protection.");
		}
		// Check if question exist
		if(!isSet(self::$session[$name])) {
			// No question -> get new question
			self::$session[$name] = serialize(self::$backend->get(self::$language));
			
		}
		// Add new component
		$question = unserialize(self::$session[$name]);
		// Check if the question is OK.
		if(! $question instanceof IQuestion) {
			self::$session[$name] = serialize(self::$backend->get(self::$language));
			$question = unserialize(self::$session[$name]);
		}
		$form[$name] = new self();	
		$form[$name]->setQuestion($question);
		$form[$name]->addRule("\TextCaptcha\TextCaptcha::validate","Zodpovězte správně bezpečnostní otázku.");
		$form[$name]->setRequired("Zodpovězte prosím bezpečnostní otázku.");
		return $form[$name];
	}
	
	public function getLabel($caption = NULL) {
		// Create container
		$label = \Nette\Utils\Html::el("div")->class("as-tc")->id($this->getName()."-as-tc");
		// Real LABEL
		$real = clone $this->label;
		$real->for = $this->getHtmlId();
		$real->add($this->translate("Zodpovězte bezpečnostní otázku:"));
		$label->add($real);
		// Question
		$question = \Nette\Utils\Html::el("div")->class("as-tc-question")->add($this->question->getQuestion());
		$label->add($question);
		return $label;
	}
	
	/**
	 * Inject session
	 * @param \Nette\Http\Session $session 
	 */
	public static function setSession(\Nette\Http\Session $session) {
		self::$session = $session->getSection("textcaptcha");
	}
	
	/**
	 * Inject backend with question
	 * @param IBackend $backend 
	 */
	public static function setBackend(IBackend $backend) {
		self::$backend = $backend;
	}
	
	/**
	 * Sets language of questions (question must exist in in backend)
	 * @param string $language 
	 */
	public static function setLanguage($language) {
		self::$language = $language;
	}
	
}

// Extend form class
\Nette\Forms\Container::extensionMethod('addTextCaptcha', array('\TextCaptcha\TextCaptcha', 'addTextCaptcha'));

// Set Text Captcha settings
TextCaptcha::setSession(\Nette\Environment::getSession());
TextCaptcha::setBackend(new ArrayBackend());
TextCaptcha::setLanguage("cs");

// For using remote backend (API)
//require_once __DIR__."/backend/RemoteBackend.php";
//$backend = new RemoteBackend();
//$backend->addAuth("cs", "http://textcaptcha/api/", "API_KEY", "SALT");
//TextCaptcha::setBackend($backend);