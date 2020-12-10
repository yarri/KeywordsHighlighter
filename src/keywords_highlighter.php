<?php
namespace Yarri;

class KeywordsHighlighter {

	protected static $CHAR_MAP;

	protected $default_options;

	function __construct($options = []){
		$options += [
			"opening_tag" => '<mark>',
			"closing_tag" => '</mark>'
		];

		$this->default_options = $options;

		if(!isset(self::$CHAR_MAP)){
			$char_map = [
				"a" => "áàáâãäåæ",
				"c" => "čç©",
				"d" => "ď",
				"e" => "ěéèêëæ",
				"i" => "íìîï",
				"l" => "ľĺ",
				"n" => "ňñ",
				"o" => "óòôõö",
				"r" => "ř®",
				"s" => "šß",
				"S" => "Šß", // mb_strtoupper("ß") -> "SS"
				"t" => "ť",
				"u" => "úůùûü",
				"y" => "ýÿ",
				"z" => "ž",
			];

			foreach($char_map as $letter => $alternatives){
				self::$CHAR_MAP[$letter] = $alternatives;
				$letter_upper = mb_strtoupper($letter);
				if(!isset(self::$CHAR_MAP[$letter_upper])){
					self::$CHAR_MAP[$letter_upper] = mb_strtoupper($alternatives);
				}
			}
		}
	}

	function highlight($text,$keywords,$options = []){
		static $CACHE = [];

		$options += $this->default_options;

		if(!isset($CACHE[$keywords])){
			$SPECIALS = [
				"<" => "(&lt;)",
				">" => "(&gt;)",
				"/" => '\/',
				'"' => '("|&quot;)',
				"'" => "('|&#039;)",
				"ß" => "(ß|SS)",
				"æ" => "(æ|Æ|a|e|ae)",
			];

			$kwds = trim($keywords);
			$kwds = preg_replace('/\s/su',' ',$kwds);
			$_kwds = [];
			foreach(preg_split('/\s/u',$kwds) as $keyword){
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

					$_chars = [];

					if(isset($SPECIALS[$ch])){
						$_chars[] = $SPECIALS[$ch];
					}else{
						$_chars[] = preg_quote($ch);
					}

					if(isset(self::$CHAR_MAP[$ch])){
						$_chars[] = "[$ch".self::$CHAR_MAP[$ch]."]";
					}

					foreach(self::$CHAR_MAP as $letter => $alternatives){
						if(strpos($alternatives,$ch)!==false){
							$_chars[] = "[$ch$letter]";
						}
					}

					if($_chars){
						$chars[] = sizeof($_chars)>1 ? "(".join("|",$_chars).")" : $_chars[0];
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

		$opening_tag = $options["opening_tag"];
		$closing_tag = $options["closing_tag"];

		$uniqid = uniqid();
		$opening_tag_placeholder = "--begin@$uniqid--";
		$closing_tag_placeholder = "--end@$uniqid--";
		$pattern = "(".join("|",$words).")";
		//echo $pattern,"\n"; exit;
		$out = preg_replace("/$pattern/iu","$opening_tag_placeholder\\1$closing_tag_placeholder",$text);

		// removing placeholders from HTML tags
		$out = preg_replace_callback('/(<[^>]*>)/u',function($matches) use($opening_tag_placeholder,$closing_tag_placeholder){
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
