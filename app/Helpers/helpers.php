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

function optimized_asset(string $relativePath): string {
    $extension = pathinfo($relativePath, PATHINFO_EXTENSION);
    $webpPath = substr($relativePath, 0, -(strlen($extension) + 1)) . '.webp';

    return file_exists(public_path($webpPath)) ? asset($webpPath) : asset($relativePath);
}
