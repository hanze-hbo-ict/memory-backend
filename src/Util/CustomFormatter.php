<?php
namespace App\Util;

use Monolog\Formatter\FormatterInterface;

class CustomFormatter implements FormatterInterface {

    public function format(array $record):string {
        $msg = $record['datetime']->date ?? date("Y-m-d H:i:s");
        $msg .= "\t".($record['context']['request_uri'] ?? '');;
        $msg .= "\t".$record['message']."\r\n";
        return $msg;

//        return print_r ($record, true);
    }


    public function formatBatch(array $records):string {
        $message = '';
        foreach ($records as $record) {
            $message .= $this->format($record);
        }

        return $message;
    }

}