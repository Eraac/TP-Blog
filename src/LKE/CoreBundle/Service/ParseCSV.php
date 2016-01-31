<?php

namespace LKE\CoreBundle\Service;

class ParseCSV
{
    public function parse($filename, $separator = ',', $ignoreFirstLine = true)
    {
        $csv = $this->getFile($filename);
        $i = 0;

        $rows = array();

        while (($data = fgetcsv($csv, null, $separator)) !== false)
        {
            $i++;

            if ($ignoreFirstLine && $i == 1) {
                continue;
            }

            $rows[] = $data;
        }

        fclose($csv);

        return $rows;
    }

    private function getFile($filename)
    {
        $csvString = file_get_contents($filename);
        $tmp = tmpfile();

        fwrite($tmp, $csvString);
        fseek($tmp, 0);

        return $tmp;
    }
}
