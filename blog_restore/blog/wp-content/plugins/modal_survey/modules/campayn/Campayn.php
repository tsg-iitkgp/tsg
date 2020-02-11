<?php

/*
Copyright © 2014, Miroslav Merinsky (merinskym@dlaex.cz) 
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, 
are permitted provided that the following conditions are met:

1. Redistributions of source code must retain the above copyright notice, 
   this list of conditions and the following disclaimer.
 
2. Redistributions in binary form must reproduce the above copyright notice, 
   this list of conditions and the following disclaimer in the documentation 
   and/or other materials provided with the distribution.
 
THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" 
AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, 
THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR 
PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS 
BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, 
OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, 
PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; 
OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, 
WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE 
OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, 
EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

/**
 * @author Miroslav Merinsky <merinskym@dlaex.cz>
 * @version 1.1
 * @license New BSD Licence
 */
class Campayn {     
    
    // HTTP status codes
    const HTTP_OK = 200;
    
    /** @var string */
    private $apiKey;
    
    /** @var string */
    private $protocol = 'http';
    
    /** @var string */
    private $domain;
    
    /** @var string */
    private $version = '1';


    /**     
     * @param string $apiKey
     * @param array $option
     * @throws \Exception
     */
    public function __construct($apiKey, array $option) {        
        if (!isset($apiKey))
            throw new \InvalidArgumentException('Invalid value has been entered.');
        
        $this->apiKey = $apiKey;
        
        if (isset($option['protocol'])) {
            if (!in_array($option['protocol'], array('http', 'https')))
                throw new \InvalidArgumentException('Invalid protocol type option has been entered.');
            
            $this->protocol = $option['protocol'];
        }
        
        if (isset($option['version']))
            $this->version = $option['version'];        
        
        if (!isset($option['domain']))
            throw new \InvalidArgumentException('Option domain is required.');
        
        $this->domain = $option['domain'];           
    }
    
    
    /**     
     * @param string $username
     * @param string $password
     * @param array $option
     * @return string
     */
    public static function getAPIKey($username, $password, array $option) {    
        if (!isset($username) || !isset($password) || !isset($option['domain']))
            throw new \InvalidArgumentException('Invalid value has been entered.');        
        if (isset($option['protocol'])) {
            if (!in_array($option['protocol'], array('http', 'https')))
                throw new \InvalidArgumentException('Invalid protocol type option has been entered.');                                     
        }
        
        $option['protocol'] = (isset($option['protocol'])) ? $option['protocol'] : 'http';
        $option['version'] = (isset($option['version'])) ? $option['version'] : '1';
                
        $uri = $option['protocol'] .'://' .$option['domain'] .'/api/v' .$option['version'] .'/login/basic.json';
        
        try {
            $response = \Httpful\Request::get($uri)
                ->addHeader('Authorization', 'TRUEREST '
                    .'username='.mb_encode_mimeheader($username)
                    .'&password='.mb_encode_mimeheader($password))
                ->send();

        } catch (\Exception $e) {
            throw new CampaynException('Invalid error of HTTP GET request.', 0, $e);
            
        }
        
        if ($response->code != self::HTTP_OK)
            throw new CampaynException('Invalid return code of HTTP request. #'.$response->code, $response->code);        
        
        $response = json_decode($response->body);
        
        if (!isset($response->apikey))
            throw new CampaynException('API key not found. Invalid response.');
        
        return $response->apikey;
    }
    
    
    /**     
     * @param int $listId
     * @param CampaynContact $data
     */
    public function addContact($listId, CampaynContact $data) {
        if (!isset($listId) || (! $data instanceof CampaynContact) || (empty($data->email)))
		{
            //throw new \InvalidArgumentException('Invalid value has been entered');
			$this->error = "Invalid value has been entered.";
			return $this;
        }
        $r = $this->post("/lists/$listId/contacts.json", $data->toArray());
        if (!isset($r['success']))
		{
            //throw new CampaynException('Could not add contact.');
			$this->error = "Could not add contact.";
			return $this;
		}
		else 
		{
			$this->success = "true";
			return $this;
		}
    }
    
    
    /**          
     * @return array
     */
    public function getLists() {       
        $r = $this->get('/lists.json');
        $rNew = array();        
        if (is_array($r)) {
            foreach ($r as $item) {
                $rNew[] = new CampaynList((array)$item);
            }
        }
        return $rNew;
    }
    
    
    /**    
     * @param int $id
     * @return CampaynContact
     */
    public function getContact($id) {
        if (!isset($id))
            throw new \InvalidArgumentException('Invalid value has been entered');
        
        return new CampaynContact((array)$this->get("/contacts/${id}.json"));
    }
    
    
    /**     
     * @param int $listId
     * @param string $filter
     * @return array
     */
    public function getContacts($listId, $filter = null) {
        if (!isset($listId))
            throw new \InvalidArgumentException('Invalid value has been entered');
        
        if (isset($filter))
            $r = $this->get("/lists/$listId/contacts.json", array(
                'filter[contact]' => $filter,
            ));        
        else
            $r = $this->get("/lists/$listId/contacts.json");  
        
        $rNew = array();        
        if (is_array($r)) {
            foreach ($r as $item) {
                $rNew[] = new CampaynContact((array)$item);
            }
        }
        
        return $rNew;        
    }
    
    
    /**      
     * @param string $url
     * @return array
     */
    private function get($url) {
        try {
            $response = \Httpful\Request::get($this->getUri($url))
                    ->addHeader('Authorization', 'TRUEREST apikey='.$this->apiKey)
                    ->send();
        
        } catch (\Exception $e) {
            throw new CampaynException('Invalid error of HTTP GET request.', 0, $e);
            
        }                
        
        if ($response->code != self::HTTP_OK)
            throw new CampaynException('Invalid return code of HTTP request. #'.$response->code, $response->code);
			$rsp = (array)$response->body;
//			$rsp = (array)json_decode($response->body);
        return $rsp;
    }
    
    
    /**     
     * @param string $url
     * @param array $data
     * @return bool
     */
    private function post($url, array $data) {
        try {
            $response = \Httpful\Request::post($this->getUri($url))
                    ->addHeader('Authorization', 'TRUEREST apikey='.$this->apiKey)
                    ->body(json_encode($data))
                    ->send(); 
            
        } catch (\Exception $e) {
            throw new CampaynException('Invalid error of HTTP POST request.', 0, $e);
            
        }
        
        if ($response->code != self::HTTP_OK)
            throw new CampaynException('Invalid return code of HTTP request. #'.$response->code, $response->code);        
        
//        return json_decode($response->body);
        return (array)$response->body;
    }
    
    
    /**
     * @param string $url
     * @param array $params
     * @return string
     */
    private function getUri($url, $params = null) {
        $param = array();  
        
        if (is_array($params)) {
            foreach ($params as $key => $item) {
                $param[] = urlencode($key) .'=' . urlencode($item);
            }
        }
        
        $uri = $this->protocol .'://' .$this->domain .'.campayn.com/api/v' .$this->version .$url;
        if (count($param) > 0)
            $uri .= '?' . implode('&', $param);
        
        return $uri;
    }
    
}
