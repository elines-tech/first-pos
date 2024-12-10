<?php

use Plivo\RestClient;

class Smsplivo
{
    function transact(array $configuration, string $phonenumber, string $message)
    {
        $client = new RestClient($configuration['authId'], $configuration['authToken']);
        $response = $client->messages->create(
            [
                "src" => $configuration['senderId'],
                "dst" => $phonenumber,
                "text"  => $message,
            ]
        );
        return $response;
    }
}
