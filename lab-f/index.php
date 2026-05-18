<?php
require_once 'autoload.php';

use App\Serializer;
use App\Encoder\CsvEncoder;
use App\Encoder\TsvEncoder;
use App\Encoder\SsvEncoder;
use App\Encoder\JsonEncoder;
use App\Encoder\YamlEncoder;

$output = '';

$serializer = new Serializer();
$serializer->addEncoder(new CsvEncoder());
$serializer->addEncoder(new TsvEncoder());
$serializer->addEncoder(new SsvEncoder());
$serializer->addEncoder(new JsonEncoder());
$serializer->addEncoder(new YamlEncoder());

$inputData = $_COOKIE['input'] ?? '';
$inputFormat = $_COOKIE['input_format'] ?? 'csv';
$outputFormat = $_COOKIE['output_format'] ?? 'csv';

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $inputData = $_POST['input'] ?? '';
    $inputFormat = $_POST['input_format'] ?? 'csv';
    $outputFormat = $_POST['output_format'] ?? 'csv';

    setcookie('input', $inputData, time() + 3600);
    setcookie('input_format', $inputFormat, time() + 3600);
    setcookie('output_format', $outputFormat, time() + 3600);

    $decoded = $serializer->decode($inputFormat, $inputData);
    $output = $serializer->encode($outputFormat, $decoded);
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Konwerter</title>
    <style>
        body {
            background-color: #1e1e1e;
            color: #d4d4d4;
            font-family: monospace;
            padding: 40px;
        }

        h1 {
            margin-bottom: 24px;
        }

        textarea {
            width: 100%;
            background-color: #2d2d2d;
            color: #d4d4d4;
            border: 1px solid #444;
            padding: 12px;
            font-family: monospace;
            font-size: 14px;
            resize: vertical;
        }

        .selects {
            display: flex;
            gap: 16px;
            margin-top: 12px;
        }

        .selects span {
            display: flex;
            align-items: center;
            font-size: 14px;
            color: #888;
        }

        select {
            background-color: #2d2d2d;
            color: #d4d4d4;
            border: 1px solid #444;
            padding: 8px 16px;
            font-family: monospace;
            font-size: 14px;
        }

        button {
            margin-top: 12px;
            background-color: #0e639c;
            color: white;
            border: none;
            padding: 10px 32px;
            font-family: monospace;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #1177bb;
        }

        pre {
            margin-top: 24px;
            background-color: #2d2d2d;
            border: 1px solid #444;
            padding: 16px;
            font-family: monospace;
            font-size: 14px;
            white-space: pre-wrap;
        }
    </style>
</head>

<body>
<h1>Konwerter danych</h1>

<form method="POST">
    <textarea name="input" rows="10"><?= $inputData ?></textarea>

    <div class="selects">
        <select name="input_format">
            <option value="csv" <?= $inputFormat === 'csv' ? 'selected' : '' ?>>CSV</option>
            <option value="ssv" <?= $inputFormat === 'ssv' ? 'selected' : '' ?>>SSV</option>
            <option value="tsv" <?= $inputFormat === 'tsv' ? 'selected' : '' ?>>TSV</option>
            <option value="json" <?= $inputFormat === 'json' ? 'selected' : '' ?>>JSON</option>
            <option value="yaml" <?= $inputFormat === 'yaml' ? 'selected' : '' ?>>YAML</option>
        </select>

        <span>na</span>

        <select name="output_format">
            <option value="csv" <?= $outputFormat === 'csv' ? 'selected' : '' ?>>CSV</option>
            <option value="ssv" <?= $outputFormat === 'ssv' ? 'selected' : '' ?>>SSV</option>
            <option value="tsv" <?= $outputFormat === 'tsv' ? 'selected' : '' ?>>TSV</option>
            <option value="json" <?= $outputFormat === 'json' ? 'selected' : '' ?>>JSON</option>
            <option value="yaml" <?= $outputFormat === 'yaml' ? 'selected' : '' ?>>YAML</option>
        </select>
    </div>

    <button type="submit">Konwertuj</button>
</form>

<pre><?= $output ?></pre>
</body>
</html>