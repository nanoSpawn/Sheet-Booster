Sheet-Booster v1.73
=============

Script to generate an XML of numbers ready to ease up digital printing of numbered tickets.

You can find and use the latest operative versions in:

http://muntanercomunicacio.com/numerador.html
(Javascript based. Executed local-side)

http://www.muntanercomunicacio.com/num_alzado.php
(Deprecated. Old fashion PHP script)
/* Right now this small script requires PHP 5+. Also, doesn't work well with huge (10k+ elements) XML, getting stuck a lot. */

The javascript based numerador.html, while being done to practice my rusty Javascript, turned out to fix all the problems in PHP and still be heaps better. Instead of creating a valid XML tree it creates a simple string (thousands of times faster!), also while I was at it, coded per input validation when it detects changes, instead of checking on form submit.

And it no longer refreshes the page.

<h2>Changelog</h2>

<b>1.73</b>
- [HTML] Now automatically changes the filename of the xml to be downloaded based on the set numbers, takes in account if those are inverted or not.

<b>1.72</b>
- [HTML] Small patch, moved the inverted toggle to the generator area, makes more sense there since it's related to generation, not to printing.

<b>1.71</b>
- [HTML] Added option for inverting the numbers. Just allowing the "to" field to be smaller than the "from" one was too cumbersome, a simple field makes things a lot cleaner.

<b>1.7</b>
- [HTML] Added suffix.
- [HTML] Added option for toggling zero padding. If disabled, the "lenght" field will be ignored.

<b>1.6</b>
- [HTML] Added prefix.
- [HTML] Finetuned the default settings a bit.
