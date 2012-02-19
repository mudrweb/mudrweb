<?php

namespace TextCaptcha;

/**
 * All questions need checking
 * @author Jan Drábek
 * @version 1.0
 * @license GNU-GPLv3
 */
interface IQuestion {
	
	/**
	 * Check if given answer is correct
	 * @param string $answer
	 */
	public function check($answer);
}