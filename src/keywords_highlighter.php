<?php
class KeywordsHighlighter {

	protected $char_map = [
		"a" => "áàáâãäåæ",
		"c" => "čç",
		"d" => "ď",
		"e" => "éèêë",
		"l" => "ľ",
		"o" => "óòôõö",
		"r" => "ř",
		"s" => "š",
		"t" => "ť",
		"u" => "úůùûü",
		"y" => "ýÿ",
		"z" => "ž"
	];

	protected $default_options;

	function __construct($options = []){
		$options += [
			"opening_tag" => '<span style="background-color: #ffff00;">',
			"closing_tag" => '</span>'
		];

		$this->default_options = $options;
	}

	function highlight($text,$keywords){
		$keywords = trim($keywords);
		$options = $this->default_options;
		$opening_tag = $options["opening_tag"];
		$closing_tag = $options["closing_tag"];
		$words = [];
		$word = $keywords;
		$out = preg_replace("/($word)/iu","$opening_tag\\1$closing_tag",$text);
		return $out;
	}
}
