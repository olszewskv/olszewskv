<?php

namespace App\Encoder;

class JsonEncoder implements EncoderInterface
{
    public function supports(string $format): bool
    {
        return $format === 'json';
    }

    public function decode(string $data): array
    {
        return json_decode($data);
    }

    public function encode(array $data): string
    {
        return json_encode($data, JSON_PRETTY_PRINT);
    }
}