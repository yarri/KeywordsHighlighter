Keywords Highlighter
====================

Highlights keywords (typically a search query) in a HTML string.

Basic usage
-----------

    $html_text = '
      <h1>The truth about pizza and beer</h1>

      <p>
        Beer and pizza.
        It might be one of the most obvious food pairings on the planet...
      </p>
    ';

    $highlighter = new \Yarri\KeywordsHighlighter([
      "opening_tag" => '<span class="highlight">',
      "closing_tag" => '</span>',
    ]);

    echo $highlighter->highlight($html_text,"pizza beer");

    //  <h1>The truth about <span class="highlight">pizza</span> and <span class="highlight">beer</span></h1>
    //
    //  <p>
    //    <span class="highlight">Beer</span> and <span class="highlight">pizza</span>.
    /     It might be one of the most obvious food pairings on the planet...
    //  </p>

Installation
------------

    composer require yarri/keywords-highlighter

Testing
-------

    composer update --dev
    cd test
    ../vendor/bin/run_unit_tests

[//]: # ( vim: set ts=2 et: )

