<?php

if (!function_exists('rupiah')) {
    function rupiah(int $amount): string
    {
        return 'IDR ' . number_format($amount, 0, ',', '.');
    }
}
