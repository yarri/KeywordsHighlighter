Keywords Highlighter
====================

Highlights keywords (typically a search query) in a HTML string.

Basic usage
-----------

    $html_text = '
      <h1>The truth about pizza and beer</h1>

      <p>
        Beer and pizza. It might be one of the most obvious food pairings on the planet...
      </p>
    ';

    $highlighter = new \Yarri\KeywordsHighlighter([
      "opening_tag" => '<span style="background-color: #ffff00;">',
      "closing_tag" => '</span>',
    ]);

    echo $highlighter->highlight($html_text,"pizza beer");

    //  <h1>The truth about <span style="background-color: #ffff00;">pizza</span> and <span style="background-color: #ffff00;">beer</span></h1>
    //
    //  <p>
    //    <span style="background-color: #ffff00;">Beer</span> and <span style="background-color: #ffff00;">pizza</span>. It might be one of the most obvious food pairings on the planet...
    //  </p>

Installation
------------

    composer require yarri/keywords-highlighter dev-master

Testing
-------

    composer update --dev
    cd test
    ../vendor/bin/run_unit_tests

[//]: # ( vim: set ts=2 et: )

