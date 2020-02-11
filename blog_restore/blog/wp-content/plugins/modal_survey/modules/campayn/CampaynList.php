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
class CampaynList {
    
    /** @var int */
    public $id;
    
    /** @var string */
    public $name;
        
    /** @var int */
    public $countEmail;       
    
    /** @var int */
    public $countContact;
    
    /** @var string */
    public $tags;
    
    
    /**     
     * @param array $data
     */
    public function __construct($data = null) {
        if (isset($data['id']))
            $this->id = (int)$data['id'];
        if (isset($data['list_name']))
            $this->name = $data['list_name'];
        if (isset($data['count_email']))
            $this->countEmail = (int)$data['count_email'];
        if (isset($data['contact_count']))
            $this->countContact = (int)$data['contact_count'];
        if (isset($data['tags']))
            $this->tags = $data['tags'];        
    }
    
    
    /**     
     * @return array
     */
    public function toArray() {
        $a = array();                
            
        if (!empty($this->id))
            $a['id'] = $this->id;
        if (!empty($this->name))
            $a['list_name'] = $this->name;        
        if (isset($this->countEmail))
            $a['count_email'] = $this->countEmail;  
        if (isset($this->countContact))
            $a['contact_count'] = $this->countContact;  
        if (!empty($this->tags))
            $a['tags'] = $this->tags;        
        
        return $a;
    }        
    
}
