<?php

namespace TextCaptcha;

require_once __DIR__."/IQuestion.php";

/**
 * Question - answer(s) component which do check of correctness.
 * @author Jan Drábek
 * @version 1.0
 * @license GNU-GPLv3
 */
class Question implements IQuestion {
	/** @var string */
	private $question;
	
	/** @var mixed */
	private $answers = array();
	
	/**
	 * Create new question - answer(s). First parameter is always the question.
	 * @return self 
	 */
	public static function create() {
		$ret = new self();
		$params = func_get_args();
		if (count($params) > 0) {
			foreach($params as $key => $param) {
				if ($key == 0) $ret->setQuestion ($param);
				$ret->addAnswer($param);
			}
		}
		return $ret;
	}
	
	/**
	 * Get question
	 * @return string
	 */
	public function getQuestion() {
		return $this->question;
	}
	
	/**
	 * Get answer(s)
	 * @return mixed
	 */
	public function getAnswers() {
		return $this->answers;
	}
	
	/**
	 * Add one answer
	 * @param string $answer
	 * @return Question
	 */
	public function addAnswer($answer) {
		$this->answers[] = self::process($answer);
		return $this;
	}
	
	/**
	 * Set question
	 * @param string $question
	 * @return Question 
	 */
	public function setQuestion($question) {
		$this->question = $question;
		return $this;
	}
	
	private static function process($value) {
		return strtolower(\Nette\Utils\Strings::toAscii($value));
	}
	
	public function check($answer) {
		return in_array(self::process($answer), $this->answers);
	}
}