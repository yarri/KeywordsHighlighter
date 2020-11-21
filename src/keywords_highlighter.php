<?php
namespace Yarri;

class KeywordsHighlighter {

	protected static $CHAR_MAP;

	protected $default_options;

	function __construct($options = []){
		$options += [
			"opening_tag" => '<span style="background-color: #ffff00;">',
			"closing_tag" => '</span>'
		];

		$this->default_options = $options;

		if(!isset(self::$CHAR_MAP)){
			$char_map = [
				"a" => "áàáâãäåæ",
				"c" => "čç©",
				"d" => "ď",
				"e" => "éèêë",
				"i" => "íìîï",
				"l" => "ľ",
				"n" => "ňñ",
				"o" => "óòôõö",
				"r" => "ř®",
				"s" => "š",
				"t" => "ť",
				"u" => "úůùûü",
				"y" => "ýÿ",
				"z" => "ž"
			];

			foreach($char_map as $letter => $alternatives){
				self::$CHAR_MAP[$letter] = $alternatives;
				self::$CHAR_MAP[mb_strtoupper($letter)] = mb_strtoupper($alternatives);
			}
		}
	}

	function highlight($text,$keywords){
		static $CACHE = [];

		if(!isset($CACHE[$keywords])){
			$SPECIALS = [
				"<" => "(&lt;)",
				">" => "(&gt;)",
				'"' => '("|&quot;)',
				"'" => "('|&#039;)",
			];

			$kwds = trim($keywords);
			$kwds = preg_replace('/\s/s',' ',$kwds);
			$_kwds = [];
			foreach(preg_split('/\s/',$kwds) as $keyword){
				if(!strlen($keyword)){ continue; }
				$_kwds[] = $keyword;
			}
			$kwds = array_unique($_kwds);

			$words = [];
			$chars = [];
			foreach($kwds as $keyword){
				$chars = [];
				foreach(preg_split('//u',$keyword) as $ch){
					if(strlen($ch)==0){ continue; }
					if(isset(self::$CHAR_MAP[$ch])){
						$chars[] = "[$ch".self::$CHAR_MAP[$ch]."]";
						continue;
					}
					foreach(self::$CHAR_MAP as $letter => $alternatives){
						if(strpos($alternatives,$ch)!==false){
							$chars[] = "[$ch$letter]";
							continue(2);
						}
					}
					if(isset($SPECIALS[$ch])){
						$chars[] = $SPECIALS[$ch];
					}elseif(strpos(".+*?[](){}\\/^$|",$ch)){ // regular exppressions special chars
						$chars[] = "\\$ch";
					}elseif(preg_match('/^[a-z0-9,:#@!=~-]$/i',$ch)){
						$chars[] = $ch;
					}else{
						// unhandled char
						$words[] = join("",$chars);
						$chars = [];
					}
				}
				$words[] = join("",$chars);
			}

			$words = array_filter($words,function($word){ return strlen($word)>0; });
			
			// sorting words according length, the longest word must be at the first place
			$_words = [];
			$i = 0;
			foreach($words as $w){
				$i++;
				$_words[strlen($w).".".$i] = $w;
			}
			krsort($_words,SORT_NUMERIC);
			$words = array_values($_words);

			$CACHE[$keywords] = $words;
		}

		$words = $CACHE[$keywords];

		if(!$words){ return $text; }

		$options = $this->default_options;

		$opening_tag = $options["opening_tag"];
		$closing_tag = $options["closing_tag"];

		$opening_tag_placeholder = "--begin@".uniqid()."--";
		$closing_tag_placeholder = "--begin@".uniqid()."--";
		$out = preg_replace("/(".join("|",$words).")/iu","$opening_tag_placeholder\\1$closing_tag_placeholder",$text);

		// removing placeholders from HTML tags
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
