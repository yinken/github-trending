<?php
$uri = 'https://www.hotelcareer.de/popup.php?sei_id=655';      


echo $uri;

// Helpers
function DOMinnerHTML(DOMNode $element) 
{ 
    $innerHTML = ""; 
    $children  = $element->childNodes;

    foreach ($children as $child) 
    { 
        $innerHTML .= $element->ownerDocument->saveHTML($child);
    }

    return html_entity_decode($innerHTML); 
} 