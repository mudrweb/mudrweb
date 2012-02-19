<?php

namespace TextCaptcha;

/**
 * Provider of questions
 * @author Jan Drábek
 * @version 1.0
 * @license GNU-GPLv3
 */
interface IBackend {
	/**
	 * Get random question
	 * @param string $language
	 * @return IQuestion
	 */
	public function get($language);
}