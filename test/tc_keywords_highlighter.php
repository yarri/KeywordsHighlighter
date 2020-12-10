<?php
class TcKeywordsHighlighter extends TcBase {

	function test_basic_usage(){
		$html_text = '
			<h1>The truth about pizza and beer</h1>

			<p>
				Beer and pizza.
				It might be one of the most obvious food pairings on the planet...
			</p>
		';

		$highlighter = new \Yarri\KeywordsHighlighter();

		$this->assertEquals('
			<h1>The truth about <mark>pizza</mark> and <mark>beer</mark></h1>

			<p>
				<mark>Beer</mark> and <mark>pizza</mark>.
				It might be one of the most obvious food pairings on the planet...
			</p>
		',$highlighter->highlight($html_text,"beer pizza"));

		$this->assertEquals('
			<h1>The truth about <span class="highlight">pizza</span> and <span class="highlight">beer</span></h1>

			<p>
				<span class="highlight">Beer</span> and <span class="highlight">pizza</span>.
				It might be one of the most obvious food pairings on the planet...
			</p>
		',$highlighter->highlight($html_text,"beer pizza",["opening_tag" => '<span class="highlight">', "closing_tag" => '</span>']));
	}
	
	function test() {
		$kh = new \Yarri\KeywordsHighlighter(["opening_tag" => "<i>","closing_tag" => "</i>"]);

		$src = 'Příliš žluťoučký kůň úpěl ďábelské ódy.';

		$this->assertEquals('Příliš žluťoučký kůň úpěl ďábelské ódy.',$kh->highlight($src,''));
		$this->assertEquals('Příliš žluťoučký kůň úpěl ďábelské ódy.',$kh->highlight($src,'xxx'));
		$this->assertEquals('Příliš žluťoučký kůň úpěl ďábelské ódy.',$kh->highlight($src,'  '));

		$this->assertEquals('Příliš žluťoučký <i>kůň</i> úpěl ďábelské ódy.',$kh->highlight($src,'kůň'));
		$this->assertEquals('Příliš žluťoučký <i>kůň</i> úpěl ďábelské ódy.',$kh->highlight($src,'KŮŇ'));

		$this->assertEquals('Příliš žluťoučký <i>kůň</i> úpěl ďábelské ódy.',$kh->highlight($src,'kun'));
		$this->assertEquals('Příliš žluťoučký <i>kůň</i> úpěl ďábelské ódy.',$kh->highlight($src,'KUN'));

		$this->assertEquals('Příliš žluťoučký <i>kůň</i> úpěl ďábelské <i>ódy</i>.',$kh->highlight($src,'kůň ódy'));
		$this->assertEquals('Příliš žluťoučký <i>kůň</i> úpěl ďábelské <i>ódy</i>.',$kh->highlight($src,'kůň ODY'));
		$this->assertEquals('Pří<i>liš</i> žluťoučký <i>kůň</i> úpěl ďábelské <i>ódy</i>.',$kh->highlight($src,' kůň  ODY  lis '));

		$this->assertEquals('Příliš žluťoučký kůň úpěl ďábelské ódy<i>.</i>',$kh->highlight($src,'.'));
		$this->assertEquals('Příliš žluťoučký kůň úpěl ďábelské ódy.',$kh->highlight($src,'& ( ) [ ] / \\ \' " |'));

		$src = 'Příliš žluťoučký kůň
		úpěl ďábelské ódy.';

		$this->assertEquals('Příliš žluťoučký <i>kůň</i>
		úpěl ďábelské <i>ódy</i>.',$kh->highlight($src,'kůň ódy'));

		$src = '<h1>Title</h1> <p>Paragraph.</p>';

		$this->assertEquals('<h1>Title</h1> <p>Paragraph.</p>',$kh->highlight($src,'h1'));
		$this->assertEquals('<h1>Title</h1> <p><i>P</i>aragra<i>p</i><i>h</i>.</p>',$kh->highlight($src,'h1 p h'));
		$this->assertEquals('<h1>Title</h1> <p><i>P</i>aragra<i>p</i><i>h</i>.</p>',$kh->highlight($src,'h1 p h'));
		$this->assertEquals('<h1>Title</h1> <p>Paragraph.</p>',$kh->highlight($src,'<'));

		$src = '<p> Liberty &gt; freedom! </p>';
		
		$this->assertEquals('<p> <i>Liberty</i> &gt; <i>freedom</i>! </p>',$kh->highlight($src,'freedom liberty'));
		$this->assertEquals('<p> Liberty <i>&gt;</i> <i>freedom!</i> </p>',$kh->highlight($src,'freedom! >'));

		$this->assertEquals('<p> <i>Liberty</i> &gt; <i>free</i>dom! </p>',$kh->highlight($src,'lib libe liberty f ree free')); // 
	}

	function test2(){
		$ary = [
			"čepice" => ["cepice"],
			"cepice" => ["čepice"],

			"großer" => ["groser"],
			"GROSSER" => ["großer"],

			"bæd" => ["bad","bed"],
			"baed" => ["bæd"],
		];

		$tr_upper = [
			"großer" => "GROßER",
			"bæd" => "BæD"
		];

		$kh = new \Yarri\KeywordsHighlighter(["opening_tag" => "<i>","closing_tag" => "</i>"]);
		foreach($ary as $word => $keywords){

			foreach($keywords as $keyword){
				$keyword_upper = isset($tr_upper[$keyword]) ? $tr_upper[$keyword] : mb_strtoupper($keyword);

				$this->assertEquals("<i>$word</i>",$kh->highlight($word,$word));

				$this->assertEquals("<i>$word</i>",$kh->highlight($word,$keyword));
				$this->assertEquals("<i>$word</i>",$kh->highlight($word,$keyword_upper));
				$this->assertEquals("ab<i>$word</i>cd",$kh->highlight("ab{$word}cd",$keyword));
				$this->assertEquals("<a href=\"#\" title=\"$word\"><i>$word</i></a>",$kh->highlight("<a href=\"#\" title=\"$word\">$word</a>",$keyword));
				$this->assertEquals("<a href=\"#\" title=\"$word\"><i>$word</i></a>",$kh->highlight("<a href=\"#\" title=\"$word\">$word</a>",$keyword_upper));
			}
		}
	}
}
