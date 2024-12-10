<?php
class Planchecker
{

    function modifyexpiry($clientCode, $expiryDate, $status)
    {
        $filename = APPPATH . "subscribers/expiry.json";
        $clientCode = $clientCode;
        $expiryDate = $expiryDate;
        if ($status == "EXPIRED") {
            $filedata = file_get_contents($filename);
            if ($filedata != "") {
                $data_array = json_decode($filedata, true);
                if (array_key_exists($clientCode, $data_array)) {
                    unset($data_array[$clientCode]);
                    file_put_contents($filename, json_encode($data_array));
                }
            }
        } else {
            $filedata = file_get_contents($filename);
            if ($filedata != "") {
                $data_array = json_decode($filedata, true);
                if (array_key_exists($clientCode, $data_array)) {
                    $data_array[$clientCode]["expiryDate"] = $expiryDate;
                } else {
                    $data_array[$clientCode]["expiryDate"] = $expiryDate;
                }
            } else {
                $data_array[$clientCode]["expiryDate"] = $expiryDate;
            }
            file_put_contents($filename, json_encode($data_array));
        }
    }

    function checkexpiry($clientCode)
    {
        $today = date('Y-m-d H:i:00');
        $filename = APPPATH . "subscribers/expiry.json";
        $filedata = file_get_contents($filename);
        if ($filedata != "") {
            $data_array = json_decode($filedata, true);
            if (array_key_exists($clientCode, $data_array)) {
                $existingDate = $data_array[$clientCode]['expiryDate'];
                if ($existingDate >= $today) {
                    return "ACTIVE";
                } else {
                    unset($data_array[$clientCode]);
                    file_put_contents($filename, json_encode($data_array));
                    return "EXPIRED";
                }
            }
        } else {
            return "EXPIRED";
        }
    }
}
