<?php

namespace App\Listeners;

use App\Events\SaveBangso;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateJsonAfterSave implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SaveBangso  $event
     * @return void
     */
    public function handle(SaveBangso $event)
    {
        $filePath = $event->filePath;
        $listSim = [];
        if ($file = fopen($filePath, "r")) {
            $i = 0;
            while (!feof($file)) {
                $line = fgets($file);
                $line = explode("\t", $line);
                if (empty($line[1])) {
                    break;
                }
                $gia = trim($line[1]);
                if (empty($gia)) {
                    continue;
                }
                $sosim = trim($line[0]);
                $sosim = $line[0][0] != '0' ? '0' . $sosim : $sosim;
                $sosimFull = str_replace([',', ' '], ['', ''], $sosim);
                $sosim = str_replace(['.', ' ', ','], ['', '', ''], $sosim);
                $price = str_replace([',', '.'], ['', ''], $gia);
                $idloai = get_cat_id($sosim);
                $mang = checkmang($sosim);
                $idmang = convertmang($mang);
                $listSim[] = [
                    'sim' => $sosim,
                    'simfull' => $sosimFull,
                    'price' => $price,
                    'cat_id' => $idloai,
                    'telco' => $idmang
                ];
                $i++;
            }
            fclose($file);
        }
        $bangsoPath = dirname($filePath) . '/bangsodata.txt';
        file_put_contents($bangsoPath, json_encode($listSim));
    }
}
