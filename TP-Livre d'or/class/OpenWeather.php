<?php

namespace App;

use \DateTime;
use App\Exceptions\{CurlException, HTTPException, UnauthorizedHTTPException};

    require 'HTTPException.php';
    require 'CurlException.php';
    require 'UnauthorizedHTTPException.php';

    /**
     * Gère l'API d'OpenWeather
     * 
     * @author: Jean claude Dus <jc@dus.fr>
     * 
     */
    class OpenWeather {
        
        private $apiKey;

        public function __construct(string $api) 
        {
            $this->apiKey = $api;
        }

        /**
         * Récupère les prévisions sur plusieurs jours
         *
         * @param string $city
         *
         * @return array
         */
        public function getForecasts(string $city): ?array 
        {
 
            $data = $this->callAPI("forecast?q={$city}&appid={$this->apiKey}");

            $dataReturn = [];

            foreach($data['list'] as $city) {
                $dataReturn[] = [
                    'temp' => $city['main']['temp'],
                    'description' => $city['weather'][0]['description'],
                    'date' => new DateTime("@" . $city['dt'])
                ];
            }

            return $dataReturn;
        }

        /**
         * Récupère les informations météorologiques du jour
         *
         * @param  string $city Ville (ex: "Nice,fr")
         *
         * @return array[]
         */
        public function getToday(string $city): ?array
        {

            // try {
                $data = $this->callAPI("weather?q={$city}");
            // } catch (Exception $e)  {
            //     // die($e->getMessage());
            //     return [
            //         'temp' => 0,
            //         'description' => 'météo indisponible',
            //         'date' => new DateTime()
            //     ];
            // }

            return [
                    'temp' => $data['main']['temp'],
                    'description' => $data['weather'][0]['description'],
                    'date' => new DateTime()
                ];
        }

        /**
         * Appelle l'API Openweather
         *
         * @param  string $endpoint Action à appeler (Ex: weather, forecasts...)
         * 
         * @throws CurlException Curl a rencontré une erreur
         * @throws UnauthorizedHTTPException Erreur au niveau de la clé d'API
         *
         *
         * @return array
         */
        private function callAPI(string $endpoint): ?array {
        
            $ressource = curl_init("https://api.openweathermap.org/data/2.5/{$endpoint}&lang=fr&units=metric&appid={$this->apiKey}");

            curl_setopt_array($ressource, [
                CURLOPT_CAINFO => 'cert.cer', // Donne le certificat permettant de vérifier avec le certificat récupérer manuellement, que le site est sécurisé
                CURLOPT_RETURNTRANSFER => true, // Permet de dire à PHP "ne m'affiche pas directement les infos, mais retourne les moi dans une variable"
                CURLOPT_TIMEOUT => 1
            ]);
            $data = curl_exec($ressource);

            if ($data === false) {
                // curl_error renvoit une erreur que quand le retour de curl_exec vaut false
                throw new CurlException($ressource);
                // $error = curl_error($ressource);
                // curl_close($ressource);
                // throw new Exception($error);
            }

            $code = curl_getinfo($ressource, CURLINFO_HTTP_CODE);
            if ($code !== 200) {
                curl_close($ressource);
                if ($code === 401) {
                    $data = json_decode($data,true);
                    throw new UnauthorizedHTTPException($data['message'], 401);
                }
                throw new HTTPException($data, $code);
                // throw new Exception($data);
            }

            curl_close($ressource);
            return json_decode($data, true);

        }
    }

?>