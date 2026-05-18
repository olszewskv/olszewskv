<?php

namespace App;

use App\Encoder\EncoderInterface;

class Serializer
{
    private array $encoders = [];

    public function addEncoder(EncoderInterface $encoder): void
    {
        $this->encoders[] = $encoder;
    }

    public function decode(string $format, string $data): array
    {
        foreach ($this->encoders as $encoder)
        {
            if ($encoder->supports($format))
                return $encoder->decode($data);
        }
        return [];
    }

    public function encode(string $format, array $data): string
    {
        foreach ($this->encoders as $encoder)
        {
            if ($encoder->supports($format))
                return $encoder->encode($data);
        }
        return '';
    }
}