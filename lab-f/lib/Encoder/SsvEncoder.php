<?php

namespace App\Encoder;

class SsvEncoder implements EncoderInterface
{
    public function supports(string $format): bool
    {
        return $format === 'ssv';
    }

    public function decode(string $data): array
    {
        $lines = explode("\n", str_replace("\r\n", "\n", trim($data)));
        $headers = explode(";", array_shift($lines));

        $result = [];
        for ($i = 0; $i < count($lines); $i++)
        {
            if (trim($lines[$i]) === '')
                continue;

            $values = explode(";", $lines[$i]);
            $row = [];
            foreach ($headers as $index => $header)
                $row[$header] = $values[$index];

            $result[] = $row;
        }

        return $result;
    }

    public function encode(array $data): string
    {
        if (empty($data))
            return '';

        $headers = [];
        foreach ($data[0] as $key => $values)
            $headers[] = $key;

        $lines = [];
        $lines[] = implode(";", $headers);

        foreach ($data as $row)
        {
            $values = [];
            foreach ($row as $value)
                $values[] = $value;

            $lines[] = implode(";", $values);
        }

        return implode("\n", $lines);
    }
}