<?php

function sanitize_entry($text, $as_html = true)
{
    $text = str_replace('\n', 'NNEWLINEE', $text);
    $text = stripslashes($text);
    $text = str_replace('NNEWLINEE', $as_html ? '<br />' : '&#10;', $text);
    return $text;
}


function entry_to_markdown($text, $as_html = true)
{
    $text = str_replace('\n', 'NNEWLINEE', $text);
    $text = stripslashes($text);
    $text = strip_tags($text);
    $text = str_replace('*', '✶', $text);
    $text = str_replace('NNEWLINEE', "\n", $text);
    return $text;
}

?>