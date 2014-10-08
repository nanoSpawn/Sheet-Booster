Sheet-Booster
=============

PHP script to generate an XML of numbers ready to ease up digital printing of numbered tickets.

You can find the latest operative versions in:

http://www.muntanercomunicacio.com/num_alzado.php
(Deprecated. Old fashion PHP script)

http://muntanercomunicacio.com/numerador.html
(Javascript based. Executed local-side)

/* Right now this small script requires PHP 5+, I understand it's a big hassle for such a thing, so it's in the TODO list to convert this to HTML+JS, so it'd be a standalone script.

Also, doesn't work well with huge (10k+ elements) XML, getting stuck a lot. Hopefully the conversion to JS will fix it. Or not. */

The javascript based numerador.html, while being done to practice my rusty Javascript, turned out to fix all the problems in PHP and still be heaps better. Instead of creating a valid XML tree it creates a simple string (thousands of times faster!), also while I was at it, coded per input validation when it detects changes, instead of checking on form submit.

And it no longer refreshes the page.
