Keywords Highlighter
====================

Highlights keywords (typically a search query) in a HTML string.

Basic usage
-----------

    $highlighter = new KeywordsHighlighter([
      "opening_tag" => '<span style="background-color: #ffff00;">',
      "closing_tag" => '</span>',
    ]);

    echo $highlighter->highlight($html_text,"pizza beer");

Installation
------------

    composer require yarri/keywords-highlighter dev-master

Testing
-------

    composer update --dev
    cd test
    ../vendor/bin/run_unit_tests

[//]: # ( vim: set ts=2 et: )

