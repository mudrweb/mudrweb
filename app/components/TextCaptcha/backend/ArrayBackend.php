<?php

namespace TextCaptcha;

require_once __DIR__."/../other/Question.php";
require_once __DIR__."/IBackend.php";

/**
 * Provider of questions.
 * All questions are in private array.
 * @author Jan Drábek
 * @version 1.0
 * @license GNU-GPLv3
 */
class ArrayBackend implements IBackend {
	private $data = array();
	
	public function __construct() {
		$data = array();
		// Czech
		$data["cs"][] = Question::create("Kolik má člověk nohou?","2","dvě");
		$data["cs"][] = Question::create("Kolik má pes nohou?","4","čtyři");
		$data["cs"][] = Question::create("Kolik má pavouk nohou?","osm","8");
		$data["cs"][] = Question::create("Jaká je barva letní oblohy?","modrá");
		$data["cs"][] = Question::create("Napište druhé číslo z 52, 98, 65, 32.","98");
		$data["cs"][] = Question::create("Napište část těla z preclík, voda, klávesnice, nos.","nos");
		$data["cs"][] = Question::create("Která číslice je poslední v čísle 87823?","3","tři");					
		$data["cs"][] = Question::create("Napište kolik slov z hruška, lampa, banán je ovoce.","2","dvě");
		$data["cs"][] = Question::create("Co mezi slova nepatří rajče, olivy, připínáček, chleba.","připínáček");
                $data["cs"][] = Question::create("Vyberte nejlepšího přítele člověka z pes, slon, moucha.","pes");
                $data["cs"][] = Question::create("Kolik desetinných míst má číslo 23.445?","3","tři");
                $data["cs"][] = Question::create("Která číslice je první v čísle 65535?","6","šest");
                $data["cs"][] = Question::create("Napište první číslo z 86, 52, 32, 10.","86");
                $data["cs"][] = Question::create("Která číslice je druhá v čísle 87400?","7","sedm");
                $data["cs"][] = Question::create("Napište třetí číslo z 12, 44, 29, 99.","29");
		$data["cs"][] = Question::create("Co mezi slova nepatří voda, tenis, fotbal, hokej.","voda");
                $data["cs"][] = Question::create("Kolik nohou mají dohromady 1 pes a 1 člověk?","6","šest");
                $data["cs"][] = Question::create("Kolik sousedů má Česká Republika?","4","čtyři");
                $data["cs"][] = Question::create("Napište výsledek výrazu 'tři + čtyři'.","7","sedm");
                $data["cs"][] = Question::create("Napište výsledek výrazu 'dva x pět'.","10","deset");
                $data["cs"][] = Question::create("Napište výsledek výrazu 'dva - dva'.","0","nula");
                $data["cs"][] = Question::create("Kolik nohou má 11 lidí?","22","dvadsetdva");
                $data["cs"][] = Question::create("Která číslice je třetí v čísle 92800?","8","osm");
                $data["cs"][] = Question::create("Kolik má člověk očí?","2","dvě");
                $data["cs"][] = Question::create("Kolik stran má čtverec?","4","čtyři");
                
		// English
		$data["en"][] = Question::create("What is the color of sky?","blue");
		$data["en"][] = Question::create("Write tool from mice, pie, hammer?","hammer");
		
		$this->data = $data;
	}
	public function get($language) {
		if(!isSet($this->data[$language])) {
			throw new \Nette\InvalidArgumentException("Language [".$language."] not exists.");
		}
		$num = count($this->data[$language]);
		if($num == 0) {
			throw new \Nette\InvalidArgumentException("Language [".$language."] has no questions.");
		}
		return $this->data[$language][rand(0,$num-1)];
	}
}