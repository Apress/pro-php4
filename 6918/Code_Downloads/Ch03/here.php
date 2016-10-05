<?php

$hereText=<<<end_delimiter
All of the text of the here document is included, starting on this line,
and spanning multiple lines if necessary,
until, once it finishes, we include the final delimiter on the next line
end_delimiter;

echo($hereText);

?>