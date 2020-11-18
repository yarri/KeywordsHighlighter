<?php
class KeywordsHighlighter {

	protected $char_map = [
		"a" => "áàáâãäåæ",
		"c" => "čç",
		"d" => "ď",
		"e" => "éèêë",
		"l" => "ľ",
		"n" => "ň",
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

		foreach($this->char_map as $letter => $alternatives){
			$this->char_map[mb_strtoupper($letter)] = mb_strtoupper($alternatives);
		}
	}

	function highlight($text,$keywords){
		$keywords = trim($keywords);
		$options = $this->default_options;
		$opening_tag = $options["opening_tag"];
		$closing_tag = $options["closing_tag"];
		$words = [];
		$word = $keywords;
		$chars = [];
		foreach(preg_split('//u',$word) as $ch){
			if(strlen($ch)==0){ continue; }
			if(isset($this->char_map[$ch])){
				$chars[] = "[$ch".$this->char_map[$ch]."]";
				continue;
			}
			foreach($this->char_map as $letter => $alternatives){
				if(strpos($alternatives,$ch)!==false){
					$chars[] = "[$ch$letter]";
					continue(2);
				}
			}
			$chars[] = $ch;
		}
		$word = join($chars);
		
		$out = preg_replace("/($word)/iu","$opening_tag\\1$closing_tag",$text);
		return $out;
	}
}
