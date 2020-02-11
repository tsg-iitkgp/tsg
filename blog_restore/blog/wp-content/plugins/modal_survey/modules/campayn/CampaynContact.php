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
 */
class CampaynContact {
    
    /** @var int */
    public $id;
    
    /** @var string */
    public $email;
        
    /** @var string */
    public $firstName;       
    
    /** @var string */
    public $lastName;    

    /** @var string */
    public $title;
    
    /** @var string */
    public $company;
    
    /** @var string */
    public $street;    
        
    /** @var string */
    public $country;    
            
    /** @var string */
    public $city;    

    /** @var string */
    public $state;
    
    /** @var string */
    public $zip;
    
    /** @var \DateTime */
    public $birthday;    
        
    /** @var string */
    public $tags;               
    
    /** @var string */
    public $imageUrl;
    
    
    /**     
     * @param array $data
     */
    public function __construct($data = null) {
        if (isset($data['id']))
            $this->id = (int)$data['id'];
        if (isset($data['email']))
            $this->email = $data['email'];
        if (isset($data['first_name']))
            $this->firstName = $data['first_name'];
        if (isset($data['last_name']))
            $this->lastName = $data['last_name'];
        if (isset($data['title']))
            $this->title = $data['title'];        
        if (isset($data['company']))
            $this->company = $data['company'];           
        if (isset($data['address']))
            $this->street = $data['address'];
        if (isset($data['country']))
            $this->country = $data['country'];
        if (isset($data['city']))
            $this->city = $data['city'];  
        if (isset($data['state']))
            $this->state = $data['state']; 
        if (isset($data['zip']))
            $this->zip = $data['zip'];
        if (isset($data['birthday']))
            $this->birthday = \DateTime::createFromFormat('Y-m-d', $data['birthday']); 
        if (isset($data['tags']))
            $this->tags = $data['tags'];          
        if (isset($data['image_url']))
            $this->imageUrl = $data['image_url'];        
    }
    
    
    /**     
     * @return array
     */
    public function toArray() {
        $a = array();                
            
        if (!empty($this->id))
            $a['id'] = $this->id;
        if (!empty($this->email))
            $a['email'] = $this->email;        
        if (!empty($this->firstName))
            $a['first_name'] = $this->firstName;  
        if (!empty($this->lastName))
            $a['last_name'] = $this->lastName;  
        if (!empty($this->title))
            $a['title'] = $this->title;  
        if (!empty($this->company))
            $a['company'] = $this->company;  
        if (!empty($this->street))
            $a['address'] = $this->street;  
        if (!empty($this->country))
            $a['country'] = $this->country;                  
        if (!empty($this->city))
            $a['city'] = $this->city;  
        if (!empty($this->state))
            $a['state'] = $this->state;  
        if (!empty($this->zip))
            $a['zip'] = $this->zip;  
        if (!empty($this->birthday))
            $a['birthday'] = $this->birthday->format('Y-m-d');  
        if (!empty($this->tags))
            $a['tags'] = $this->tags;  
        if (!empty($this->imageUrl))
            $a['image_url'] = $this->imageUrl;  
        
        return $a;
    }    
    
}
