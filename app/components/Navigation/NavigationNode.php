<?php

/**
 * Navigation node
 *
 * @author Jan Marek
 * @license MIT
 */

namespace Navigation;

use Nette\ComponentModel\Container;


class NavigationNode extends Container
{

	/** @var string */
	public $label;

	/** @var string */
	public $url;

	/** @var bool */
	public $isCurrent = false;
        
        /** @var bool */
	public $isPublished = false;



	/**
	 * Add navigation node as a child
	 * @staticvar int $counter
	 * @param string $label
	 * @param string $url
	 * @return NavigationNode
	 */
	public function add($label, $url, $isPublished) {
		$navigationNode = new self;
		$navigationNode->label = $label;
		$navigationNode->url = $url;
                if ($isPublished == 'yes') {
                    $navigationNode->isPublished = true;
                } else {
                    $navigationNode->isPublished = false;
                }                

		static $counter;
		$this->addComponent($navigationNode, ++$counter);               
                
		return $navigationNode;
	}



	/**
	 * Set node as current
	 * @param NavigationNode $node
	 */
	public function setCurrent(NavigationNode $node)
	{
		return $this->parent->setCurrent($node);
	}

}
