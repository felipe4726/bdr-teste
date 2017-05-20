<?php
fizzbuss();
function fizzbuss($i = 1)
{
    if ($i % 3 == 0)
        print "Fizz";
    if ($i % 5 == 0)
        print "Buzz";
    if ($i % 3 !== 0 && $i % 5 !== 0)
        print $i;
    print "<br>";
    if ($i < 100)
        fizzbuss(++$i);
}