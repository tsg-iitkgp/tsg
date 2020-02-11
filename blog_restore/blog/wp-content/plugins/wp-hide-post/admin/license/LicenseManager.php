<?php

class SCB_LicenseManager
{

    private $items;
    private static $instance;
    public static function getInstance()
    {
        if (is_null(self::$instance))
        {
            self::$instance = new static();
        }
        return self::$instance;
    }
    public function add($id, $store, $item_type, $item_name, $encrypter = null, $options = array())
    {
        if (isset($this->items[$id]))
        {
            return $this->items[$id];
        }
        if (is_null($encrypter))
        {
            $encrypter = new WP_Hide_Post_Encryption();
        }
        $this->items[$id] = new SCB_LicenseItem($store?$store:'http://scriptburn.com', $item_type, $item_name, $encrypter, $options);
        return $this->items[$id];
    }
    public function items()
    {
        return $this->items;
    }
    public function item($id)
    {
        if (isset($this->items[$id]))
        {
            return $this->items[$id];
        }
    }

    public function base_url()
    {
        return home_url();
    }
    public function sendGetRequest($url)
    {
        $response = wp_remote_get($url);
        if (is_array($response))
        {

            return (object) array('status' => 1, 'body' => $response['body']);
        }
        else
        {
            return (object) array('status' => 1, 'message' => $response->get_error_message());

        }
    }
}
