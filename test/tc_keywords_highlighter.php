<?php
class TcKeywordsHighlighter extends TcBase {
	
	function test() {
		$kh = new KeywordsHighlighter(["opening_tag" => "<i>","closing_tag" => "</i>"]);

		$src = 'Příliš žluťoučký kůň úpěl ďábelské ódy.';

		$this->assertEquals('Příliš žluťoučký <i>kůň</i> úpěl ďábelské ódy.',$kh->highlight($src,'kůň'));
		$this->assertEquals('Příliš žluťoučký <i>kůň</i> úpěl ďábelské ódy.',$kh->highlight($src,'KŮŇ'));

		$this->assertEquals('Příliš žluťoučký <i>kůň</i> úpěl ďábelské ódy.',$kh->highlight($src,'kun'));
		$this->assertEquals('Příliš žluťoučký <i>kůň</i> úpěl ďábelské ódy.',$kh->highlight($src,'KUN'));
	}
}
