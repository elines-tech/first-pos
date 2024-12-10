<?php

use Twilio\Rest\Client;

class Smstwilio
{
    function transact(array $configuration, string $phonenumber, string $message)
    {
        $sid = $configuration['sid'];
        $token = $configuration['token'];
        $twilionumber = $configuration['twilionumber'];
        $client = new Client($sid, $token);
        $result = $client->messages->create(
            $phonenumber,
            [
                'from' => $twilionumber,
                'body' => $message
            ]
        );
        return $result;
    }
}
