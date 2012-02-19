<?php

namespace TextCaptcha;

require_once __DIR__."/../other/SaltedQuestion.php";
require_once __DIR__."/IBackend.php";

/**
 * Provider of questions.
 * All questions fetched from remote server
 * (use with ../../../../web/api/)
 * @author Jan Drábek
 * @version 1.0
 * @license GNU-GPLv3
 */
class RemoteBackend implements IBackend {
	
	const URL = "url";
	
	const KEY = "key";
	
	const SALT = "salt";
	
	/** @var mixed */
	private $auth;
	
	public function addAuth($language,$url,$key,$salt) {
		if(isSet($this->auth[$language])) {
			throw new \Nette\InvalidArgumentException("Authentication information for language [".$language."] already exists.");
		}
		$this->auth[$language] = array(
		    self::URL => $url,
		    self::KEY => $key,
		    self::SALT => $salt
		);
	}
	
	public function get($language) {
		if(!isSet($this->auth[$language])) {
			throw new \Nette\InvalidArgumentException("Authentication information for language [".$language."] not exists.");
		}
		$auth = $this->auth[$language];
		try {
			$xml = new \SimpleXMLElement($auth[self::URL]."?key=".$auth[self::KEY], null, true);
		} catch (Exception $e) {
		}
		$question = SaltedQuestion::create();
		$question->setSalt($auth[self::SALT]);
		$question->setQuestion((string) $xml->question);
		foreach ($xml->answer as $answer) {
			$question->addAnswer((string) $answer);
		}
		return $question;
	}
}