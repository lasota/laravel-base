<?php
namespace LaSota\LaravelBase\Services;

use GuzzleHttp\Client as GuzzleClient;

/**
 * Class GoogleRecaptcha
 *
 * @package LaSota\LaravelBase\Services
 */
class GoogleRecaptcha
{
    public static function verify($input)
    {
	//test in remotes

        if ( env('RECAPTCHA_SKIP') ) {
            return true;
        }

        if (empty($input['g-recaptcha-response'])) {
            return false;
        }

        $client = new GuzzleClient();

        $endpoint = 'https://www.google.com/recaptcha/api/siteverify';

        $params = [
            'response' => $input['g-recaptcha-response'],
            'remoteip' => $input['remoteip'],
            'secret' => env('RECAPTCHA_SECRET'),
        ];

        $url = implode('?', [$endpoint, http_build_query($params)]);

        $response = $client->get($url);
        $response = json_decode( $response->getBody()->getContents(), true);

        $success = array_get($response, 'success') ? true: false;

        return $success;
    }
}
