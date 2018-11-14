<?php

namespace AppBundle\Security;

use Predis\Client;

class TokenStorage {

    const KEY_SUFFIX = "-token";

    /**
     * @var Client
     */
    private $redisClient;

    /**
     * 
     * @param Client $redisClient
     */
    public function __construct(Client $redisClient) {

        $this->redisClient = $redisClient;
    }

    /**
     * Funcion para almacenar el token acual del usaurio
     * @param string $username
     * @param string $token
     */
    public function storeToken(string $username, string $token){
         
        $this->redisClient->set($username.self::KEY_SUFFIX, $token);
        
        $this->redisClient->expire($username.self::KEY_SUFFIX, 3600);
    }
    
    /**
     * Funcion para invalidar el token
     * @param string $username
     */
    public function invalidateToken(string $username){
        
        $this->redisClient->del($username.self::KEY_SUFFIX);
    }
    
    /**
     * Funcion para comprobar si el token actual es valido
     * @param string $username
     * @param string $token
     * @return bool
     */
    public function isTokenValid(string $username, string $token): bool {
        
        //dump($this->redisClient->get($username.self::KEY_SUFFIX)); die;
        return $this->redisClient->get($username.self::KEY_SUFFIX) === $token;
    }
    
}
