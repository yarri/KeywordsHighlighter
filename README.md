Keywords Highlighter
====================

[![Build Status](https://app.travis-ci.com/yarri/KeywordsHighlighter.svg?branch=master)](https://app.travis-ci.com/yarri/KeywordsHighlighter)
[![Downloads](https://img.shields.io/packagist/dt/yarri/keywords-highlighter.svg)](https://packagist.org/packages/yarri/keywords-highlighter)

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

    $highlighter = new \Yarri\KeywordsHighlighter();

    echo $highlighter->highlight($html_text,"pizza beer");

    //  <h1>The truth about <mark>pizza</mark> and <mark>beer</mark></h1>
    //
    //  <p>
    //    <mark>Beer</mark> and <mark>pizza</mark>.
    //    It might be one of the most obvious food pairings on the planet...
    //  </p>

The default tag for highlighting can be overwritten in options of the constructor.
    
    $highlighter = new \Yarri\KeywordsHighlighter([
      "opening_tag" => '<span class="highlight">',
      "closing_tag" => '</span>',
    ]);

    echo $highlighter->highlight($html_text,"pizza beer");

    //  <h1>The truth about <span class="highlight">pizza</span> and <span class="highlight">beer</span></h1>
    //
    //  <p>
    //    <span class="highlight">Beer</span> and <span class="highlight">pizza</span>.
    //    It might be one of the most obvious food pairings on the planet...
    //  </p>

KeywordsHighlighter doesn't highlight something inside a html tag.

    $html_text = '
      <h1>The truth about the H1 element</h1>
    ';

    $highlighter = new \Yarri\KeywordsHighlighter();

    echo $highlighter->highlight($html_text,"h1");

    // <h1>The truth about the <mark>H1</mark> element</h1>

Installation
------------

    composer require yarri/keywords-highlighter

Testing
-------

    composer update --dev
    cd test
    ../vendor/bin/run_unit_tests

[//]: # ( vim: set ts=2 et: )

