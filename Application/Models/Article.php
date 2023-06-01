<?php

namespace Models;

class Article extends Generique {

	/**
	 * @schema VARCHAR(255) NOT NULL
	 */
	public $title;

	/**
	 * @schema VARCHAR(255) NOT NULL
	 */
	public $description;

}