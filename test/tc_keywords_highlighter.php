<?php
class TcKeywordsHighlighter extends TcBase {
	
	function test() {
		$kh = new KeywordsHighlighter(["opening_tag" => "<i>","closing_tag" => "</i>"]);

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
		
		$this->assertEquals('<p> Liberty <i>&gt;</i> <i>freedom!</i> </p>',$kh->highlight($src,'freedom! >'));
	}
}
