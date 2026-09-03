<?php

// app/helpers.php or similar
function split_address_lines(string $address): array {
    $words = explode(' ', $address);
    $half = ceil(count($words) / 2);
    return [
        implode(' ', array_slice($words, 0, $half)),
        implode(' ', array_slice($words, $half)),
    ];
}
