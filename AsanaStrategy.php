<?php

/**
 * Asana strategy for Opauth
 * based on http://developers.asana.com/documentation/#AsanaConnect
 *
 * More information on Opauth: http://opauth.org
 *
 * @copyright Copyright © 2013 David Wambugu(https://github.com/marabooyankee)
 * @link http://opauth.org
 * @package Opauth.AsanaStrategy
 * @license MIT License
 */

/**
 * Description of AsanaStrategy 
 *
 * @author David Wambugu <maraboomint@gmail.com>
 */
class AsanaStrategy extends OpauthStrategy {

    /**
     * Compulsory config keys, listed as unassociative arrays
     */
    public $expects = array('client_id', 'client_secret');

    /**
     * Optional config keys, without predefining any default values.
     */
    public $optionals = array('redirect_uri');

    /**
     * Optional config keys with respective default values, listed as associative arrays
     * eg. array('scope' => 'email');
     */
    public $defaults = array(
        'redirect_uri' => '{complete_url_to_strategy}oauth2callback',
    );

    /**
     * Do the app request
     */
    public function request() {
        $url = 'https://app.asana.com/-/oauth_authorize';

        $params = array(
            'client_id' => $this->strategy['client_id'],
            'redirect_uri' => $this->strategy['redirect_uri'],
            'response_type' => 'code',
        );

        $this->clientGet($url, $params);
    }

    /**
     * Internal callback, after OAuth
     */
    public function oauth2callback() {

        if (array_key_exists('code', $_GET) and !empty($_GET['code'])) {
            $code = $_GET['code'];
            $url = 'https://app.asana.com/-/oauth_token';

            $params = array(
                'code' => $code,
                'client_id' => $this->strategy['client_id'],
                'client_secret' => $this->strategy['client_secret'],
                'redirect_uri' => $this->strategy['redirect_uri'],
                'grant_type' => 'authorization_code'
            );

            $response = $this->serverPost($url, $params, null, $headers);

            $results = json_decode($response);
            if (!empty($results) and isset($results->access_token)) {
                $this->auth = array(
                    "provider" => "Asana",
                    "uid" => $results->data->id,
                    "info" => array(
                        "name" => $results->data->name,
                        "email" => $results->data->email
                    ),
                    'credentials' => array(
                        'token' => $results->access_token,
                        'expires' => date('c', time() + $results->expires_in)
                    ),
                    'raw' => $results
                );

                $this->callback();
            }
            //no access token returned so request did not go as planned
            else {
                $error = array(
                    'provider' => 'Asana',
                    'code' => 'access_token_error',
                    'message' => 'Failed when attempting to obtain access token',
                    'raw' => array(
                        'headers' => $headers,
                        'response' => $response,
                    )
                );

                $this->errorCallback($error);
            }
        }
        else {

            $error = array(
                'message' => $_GET['error_description'],
                'error' => $_GET['error'],
                'raw' => $_GET
            );

            $this->errorCallback($error);
        }
    }

}