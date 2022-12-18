<?php
namespace App\Util;

use Monolog\Formatter\FormatterInterface;

class CustomFormatter implements FormatterInterface {

    /**
     * De onderstaande methode formatteert de log-messages zodat ze makkelijk op één regel passen.
     * Hierdoor kun je met `tail -f log` tijdens het ontwikkelen de logs in de gaten houden.
     *
     * Pas deze methode aan als je je eigen formaat wilt hebben. Om te zien welke data er allemaal
     * in zo'n records zit, kun je de laatste regel uit het commentaar halen (en de rest in commentaar
     * zetten, natuurlijk; of weghalen). Je loopt dan wel het risico dat je output-buffer volloopt.
     */

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