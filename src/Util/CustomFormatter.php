<?php
namespace App\Util;

use Monolog\Formatter\FormatterInterface;

class CustomFormatter implements FormatterInterface {

    public function format(array $record):string {
        return "custom-formatter!";
    }


    public function formatBatch(array $records):string {
        $message = '';
        foreach ($records as $record) {
            $message .= $this->format($record);
        }

        return $message;
    }

}