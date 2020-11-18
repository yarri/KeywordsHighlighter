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
		$keywords = preg_replace('/[\s<>]/s',' ',$keywords);

		$options = $this->default_options;
		$opening_tag = $options["opening_tag"];
		$closing_tag = $options["closing_tag"];

		$_keywords = [];
		foreach(preg_split('/\s/',$keywords) as $keyword){
			if(!strlen($keyword)){ continue; }
			$_keywords[] = $keyword;
		}

		$keywords = $_keywords;
		if(!$keywords){ return $text; }

		foreach($keywords as $keyword){
			$chars = [];
			foreach(preg_split('//u',$keyword) as $ch){
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
				if(preg_match('/^[.*?\[\]{}]$/',$ch)){
					$chars[] = "\\$ch";
				}elseif(preg_match('/^[a-z0-9,:-]$/i',$ch)){
					$chars[] = $ch;
				}else{
					$chars[] = ".";
				}
			}
			$word = join($chars);
			$words[] = $word;
		}

		$words = join('|',$words);

		$opening_tag_placeholder = "--begin@".uniqid()."--";
		$closing_tag_placeholder = "--begin@".uniqid()."--";

		$out = preg_replace("/($words)/iu","$opening_tag_placeholder\\1$closing_tag_placeholder",$text);

		$out = preg_replace_callback('/(<[^>]*>)/',function($matches) use($opening_tag_placeholder,$closing_tag_placeholder){
			return strtr($matches[1],[
				"$opening_tag_placeholder" => "",
				"$closing_tag_placeholder" => "",
			]);
		},$out);

		$out = str_replace($opening_tag_placeholder,$opening_tag,$out);
		$out = str_replace($closing_tag_placeholder,$closing_tag,$out);

		return $out;
	}
}
