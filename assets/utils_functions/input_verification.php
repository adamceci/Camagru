<?php

function input_useable(array $input, $key) {
    if (isset($input) && array_key_exists($key, $input) && !empty($input[$key])) {
        return (1);
    }
    return (0);
}