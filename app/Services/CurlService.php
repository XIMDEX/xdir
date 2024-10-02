<?php 

namespace App\Services;

use Exception;

class CurlService
{
    public function get(string $url, array $headers = []): ?string
    {
        try {
            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($curl);

            if ($response === false) {
                throw new Exception(curl_error($curl), curl_errno($curl));
            }

            curl_close($curl);
            return $response;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function post(string $url,  $data = [], array $headers = []): ?string
    {
        try {
            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array_merge($headers, ['Content-Type: application/json']));

            $response = curl_exec($curl);

            if ($response === false) {
                throw new Exception(curl_error($curl), curl_errno($curl));
            }

            curl_close($curl);
            return $response;
        } catch (Exception $e) {
            throw $e;
        }
    }

}