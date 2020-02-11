<?php

class SCB_LicenseItem
{

    private $item_name        = "";
    private $safe_item_name   = "";
    private $item_file_name   = "";
    private $random           = "";
    private $item_type        = "";
    private $decode_signature = "SCB_DECODED";
    private $store            = "";
    private $license_data     = array();
    private $key              = false;
    private $encrypter;
    protected $options;
    public $license_file;
    private $extended_info;
    public function __construct($store, $item_type, $item_name, $encrypter = null, $options = array())
    {
        $this->store          = $store;
        $this->item_name      = $item_name;
        $this->safe_item_name = strtolower(preg_replace("/[^\da-z][-]/i", '', $this->item_name));
        $this->item_type      = $item_type;
        $this->random         = $this->RandomString(4);
        $this->encrypter      = $encrypter;
        $valid_items          = array('plugin', 'module', 'theme');
        $this->options        = $options;
        if (isset($this->options['license_file']))
        {
            $this->license_file = $this->options['license_file'];
        }
        elseif (isset($this->options['license_folder']) && file_exists($this->options['license_folder']))
        {
            $this->license_file = $this->options['license_folder'] . "/" . (@$this->options['license_file'] ? $this->options['license_file'] : 'license');
        }

        if (!in_array($item_type, $valid_items))
        {
            if (!$is_custom)
            {
                throw new \Exception('Invalid item Type');
                return false;
            }
        }
        if (isset($this->options['file']))
        {
            $theme_item = new SCB_Item_Helper($this->store, $this->options['file'], $this->get_license_key(), $this->item_type, $this->is_valid());
        }

    }
    public function options($name = null)
    {
        if (!is_null($name))
        {
            if (isset($this->options[$name]))
            {
                return $this->options[$name];
            }
            else
            {
                return '';
            }
        }
        else
        {
            return $this->options;
        }
    }
    public function store_url()
    {
        return $this->store;
    }
    public function name()
    {
        return $this->item_name;
    }
    public function type()
    {
        return $this->item_type;
    }
    public static function add($store, $item_type, $item_name)
    {
        return new static($store, $item_type, $item_name);
    }
    public function RandomString($length)
    {
        $original_string = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
        $original_string = implode("", $original_string);
        return substr(str_shuffle($original_string), 0, $length);
    }
    public function prefix()
    {
        return "license." . $this->item_type . "_" . $this->safe_item_name . "_license_";
    }
    public function get_license_key()
    {
        if (isset($this->options['get_license_key']) && is_callable($this->options['get_license_key']))
        {
            return call_user_func($this->options['get_license_key'], array('this' => $this));
        }

        $key_name = $this->prefix() . 'key';
        return $this->read_license_data($key_name);

    }
    public function set_license_key($key)
    {
        if (isset($this->options['set_license_key']) && is_callable($this->options['set_license_key']))
        {
            return call_user_func($this->options['set_license_key'], array('this' => $this, 'key' => $key));
        }

        $key_name = $this->prefix() . 'key';
        $this->write_license_data($key_name, $key);
    }

    public function validate_license_file()
    {
        if (!$this->license_file)
        {

            throw new \Exception('unable to find license');

        }

        if (!file_exists($this->license_file))
        {
            $this->write_license_file('', array(), $age = 0, false);
        }

        $cnt = file_get_contents($this->license_file);

        if (!$cnt)
        {
            throw new \Exception('unable to read license');
        }
        if (substr($cnt, 0, 3) != 'SCB')
        {
            throw new \Exception('Invalid license file');
        }
        return substr($cnt, 3);
    }
    public function read_license_file($key)
    {
        $cnt = $this->validate_license_file();

        $cnt = base64_decode($cnt);

        $cnt = @unserialize($cnt);

        if (!is_array($cnt))
        {
            throw new \Exception('Invalid license file data');
        }
        if (!(isset($cnt[$key])))
        {
            return false;
        }
        $key_data = $cnt[$key];
        if (is_string($key_data['data']))
        {
            $key_data_1 = @json_decode($key_data['data']);
            if ($key_data_1)
            {
                $key_data['data'] = $key_data_1;
            }
        }
        if (is_string($key_data['data']))
        {
            $key_data_1 = @unserialize($key_data['data']);
            if ($key_data_1)
            {
                $key_data['data'] = $key_data_1;
            }
        }
        if (!(isset($key_data['expire']) && isset($key_data['data'])))
        {
            throw new \Exception('Invalid license file data (1)');
        }
        if ($key_data['expire'] != 0)
        {
            if (time() > $key_data['expire'])
            {
                unset($cnt[$key]);
                return false;
            }
        }
        return $key_data['data'];
    }

    public function write_license_file($key, $data, $age = 0, $validate = true)
    {
        $cnt = $validate ? $this->validate_license_file() : false;

        $cnt = base64_decode($cnt);
        $cnt = @json_decode($cnt);
        if (!is_array($cnt))
        {
            $cnt = array();
        }
        if ($data === false && isset($cnt[$key]))
        {
            unset($cnt[$key]);
        }
        else
        {
            $cnt[$key] = array('expire' => $age != 0 ? time() + $age : 0, 'data' => $data);
        }
        $cnt = "SCB" . base64_encode(serialize($cnt));

        file_put_contents($this->license_file, $cnt);
    }
    public function read_license_data($key = "")
    {

        if (isset($this->options['read_license_data']) && is_callable($this->options['read_license_data']))
        {
            return call_user_func($this->options['read_license_data'], array('key' => $key));
        }
        elseif (isset($this->options['license_storage']) && $this->options['license_storage'] == 'file')
        {
            return $this->read_license_file($key);
        }
        else
        {
            return get_transient($key);
        }
    }
    public function write_license_data($key, $data, $age = 0)
    {
        if (isset($this->options['write_license_data']) && is_callable($this->options['write_license_data']))
        {
            return call_user_func($this->options['write_license_data'], array('key' => $key, 'data' => $data, 'age' => $age));
        }
        elseif (isset($this->options['license_storage']) && $this->options['license_storage'] == 'file')
        {
            return $this->write_license_file($key, $data, $age);
        }
        else
        {
            set_transient($key, $data, $age);
        }
    }
    public function delete_license_data($key)
    {
        if (isset($this->options['delete_license_data']) && is_callable($this->options['delete_license_data']))
        {
            return call_user_func($this->options['delete_license_data'], array('key' => $key));
        }
        elseif (isset($this->options['license_storage']) && $this->options['license_storage'] == 'file')
        {
            return $this->write_license_file($key, false);
        }
        else
        {
            delete_transient($data_name);
        }
    }

    public function get_license_data($key = "")
    {

        $backtrace = debug_backtrace();
        $calle     = $backtrace[1]['function'];
        $data_name = $this->prefix() . 'data';

        if (!isset($this->license_data[$data_name]))
        {
            $data = $this->read_license_data($data_name);
            if ($data === false || is_null($data))
            {
                $this->debug('license', __FUNCTION__ . "-" . __LINE__, 'not found data setting-' . $data_name . "-" . $calle);

            }

            if (($data === false || is_null($data)) && $_SERVER['REQUEST_METHOD'] == 'GET')
            {
                $this->debug('license', __FUNCTION__ . "-" . __LINE__, 'license ttl expired');
                $this->check_license(true);

                $data = $this->read_license_data($data_name);
                //print_r($data);
                // die('x');
            }

            /*
            else
            {
            $data = $this->decodeIt($data);
            $expires_in = (int) (@$data['expires_in']);
            if ($expires_in <= time())
            {
            // $this->debug('license', __FUNCTION__ . "-" . __LINE__, 'license ttl expired cheating');
            $this->check_license(true);
            $data = get_transient($data_name);

            }
            }
             */

            $this->license_data[$data_name] = is_array($data) ? $data : $this->decodeIt($data);
            $this->debug('license', __FUNCTION__ . "-" . __LINE__, $this->license_data[$data_name]);

        }
        if ($key && isset($this->license_data[$data_name][$key]))
        {
            return $this->license_data[$data_name][$key];
        }
        else
        {
            return $this->license_data[$data_name];
        }

    }

    public function set_license_data($data = "")
    {

        $data_name = $this->prefix() . 'data';
        unset($this->license_data[$data_name]);
        //$this->debug('license', __FUNCTION__ . "-" . __LINE__,$data_name );
        if (!$data)
        {
            $this->delete_license_data($data_name);
            return;
        }
        $this->write_license_data($data_name, $data, 60 * 60 * 24);
        $this->debug('license', __FUNCTION__ . "-" . __LINE__, "$data_name--" . $data);

    }

    public function send_request($request, $method = "get")
    {

        if (!empty($_SESSION['license_request'][$this->item_name]['last']))
        {
            $time = (int) $_SESSION['license_request'][$request][$this->item_name]['last'];
            if ($time - time() > 0)
            {
                throw new \Exception("Please Wait 10 secs to perform this opretion again ");
            }
        }
        $_SESSION['license_request'][$request][$this->item_name]['last'] = time() + 10;
        // $this->debug('license', __FUNCTION__ . "-" . __LINE__, $request);
        //$this->debug('license', __FUNCTION__ . "-" . __LINE__, $plugin_data);
        $api_params = array(
            'edd_action' => $request,
            'license'    => trim($this->get_license_key()),
            'item_name'  => urlencode($this->item_name), // the name of our product in EDD
            'url'        => scb_license_manager()->base_url(),
        );

        if ($method == 'get')
        {
            $api_params['rand'] = $this->RandomString(5);
            $url                = $this->store . "?" . http_build_query($api_params);

            //   p_d( $url );
            //$this->debug('license', __FUNCTION__ . "-" . __LINE__, $url);
            $response = scb_license_manager()->sendGetRequest($url . http_build_query($api_params));
            
        }
        else
        {
            $url      = $this->store;
            $response = scb_license_manager()->sendPostRequest($url, array('form_params' => $api_params, 'http_errors' => true));
        }

        $backtrace = debug_backtrace();
        $calle     = $backtrace[1]['function'];

        $this->debug('license', __FUNCTION__ . "-" . __LINE__ . "-" . __LINE__, $calle . "-" . $url);
        if (!$response->status)
        {
            throw new \Exception('License Server communication error');
        }
        $license_data = (string) $response->body;

        $check = '"' . $this->decode_signature;

        if (substr($license_data, 0, strlen($check)) == $check)
        {
            $license_data = substr($license_data, 1);

            if (substr($license_data, strlen($license_data) - 1) == '"')
            {
                $license_data = substr($license_data, 0, strlen($license_data) - 1);
            }

        }

        if (!is_object(@json_decode($license_data)))
        {
            //throw new \Exception('License checksum error');
        }

        // $this->debug('license', __FUNCTION__ . "-" . __LINE__ . "-" . __LINE__, "NoDecoded:" . print_r(($license_data), true));

        //$this->debug('license', __FUNCTION__ . "-" . __LINE__ . "-" . __LINE__, "Decoded:" . print_r($this->decodeIt($license_data), true));
        if (!$license_data)
        {
            throw new \Exception('License verification faile');
        }
        return $license_data;

    }
    public function activate_license()
    {
        try
        {
            $response = $this->send_request('activate_license');

            if ($response)
            {
                $this->set_license_data($response);
                return true;
            }
        }
        catch (\Exception $e)
        {
            return false;

        }

        return false;

    }
    public function deactivate_license()
    {
        try
        {
            $response = $this->send_request('deactivate_license');
            if ($response)
            {
                $this->set_license_data($response);
                return true;
            }
        }
        catch (\Exception $e)
        {
            return false;

        }
        return false;
    }
    public function check_license($fresh = false)
    {
        try
        {
            $backtrace = debug_backtrace();
            $calle     = $backtrace[1]['function'];

            $this->debug('license', __FUNCTION__ . "-" . __LINE__ . "-" . __LINE__, $calle);

            if ($fresh)
            {
                if (!$response = $this->send_request('check_license'))
                {
                    return false;
                }
            }
            else
            {
                return true;
            }
            $this->set_license_data($response);
        }
        catch (\Exception $e)
        {
            return false;

        }

        return true;
    }

    public function getKey()
    {
        if ($this->key)
        {
            return $this->key;
        }
        $k    = array("cc6042d5c823eb37ce499cf85083ca03", "1234567890");
        $keys = array();

        if (!is_array($k))
        {
            $keys[] = $k;
        }
        else
        {
            $keys = $k;
        }

        $this->key = $keys;
        return $this->key;
    }
    public function encodeIt($data)
    {
        $key = $this->getKey();
    }

    public function checkSignature(&$return)
    {
        if (is_object($return) || is_array($return))
        {
            //error_log(__LINE__ . "-checkSignature");
            return false;
        }
        if (!is_string($return))
        {
            //error_log(__LINE__ . "-checkSignature");

            return false;
        }
        $sig = substr(trim($return), 0, strlen($this->decode_signature));
        //error_log(__LINE__ . "-checkSignature -" . ($sig . "!=" . $this->decode_signature));

        if ($sig != $this->decode_signature)
        {
            $arr = json_decode($return, true);
            if (is_array($arr))
            {
                //error_log(__LINE__ . "-checkSignature");
                $return = $arr;
                return false;
            }
            else
            {
                return false;
            }
        }
        else
        {
            $part = substr($return, strlen($this->decode_signature));

            $this->debug('license', __FUNCTION__ . "-" . __LINE__, "part $part");

            $return = $part;
            return true;
        }
    }
    public function decrypt($data, $key)
    {
        if (is_object($this->encrypter))
        {

            return $this->encrypter->decrypt($data, $key);
        }
        else
        {
            return $data;
        }

    }
    public function decodeIt($data)
    {
        $backtrace  = debug_backtrace();
        $calle      = $backtrace[1]['function'];
        $encryption = $this->encrypter;
        $keys       = $this->getKey();
        $sig        = $this->checkSignature($data);
        $this->debug('license', __FUNCTION__ . "-" . __LINE__, "Called by $calle");

        if (!$sig)
        {
            $this->debug('license', __FUNCTION__ . "-" . __LINE__, "Invalid sig");

            return ($data);
        }
        //

        $data = base64_decode($data);
        $arr  = unserialize($data);

        if (!isset($arr['hash']) || !isset($arr['data']))
        {
            $this->debug('license', __FUNCTION__ . "-" . __LINE__, 'no hash');
            //$this->debug('license', __FUNCTION__ . "-" . __LINE__, $arr);
            return false;
        }
        $decrypted = "";
        foreach ($keys as $key)
        {
            $decrypt = $this->decrypt(base64_decode($arr['data']), $key);
            //$decrypt = $encryption->decrypt( base64_decode($arr['data']), $key);

            if ($this->item_name == 'scriptburn-whois')
            {
                //  p_n($key );
                //  p_n($decrypt );
            }
            $this->debug('license', __FUNCTION__ . "-" . __LINE__, 'decoded ' . $decrypt);

            if ($arr['hash'] === md5($decrypt))
            {
                $decrypted = $decrypt;
                break;
            }
        }
        if ($this->item_name == 'scriptburn-whois')
        {
            // p_d('x');
        }
        if (!$decrypted)
        {
            $this->debug('license', __FUNCTION__ . "-" . __LINE__, 'no md5 ');
            return false;
        }

        $arr = @(@unserialize(@base64_decode($decrypted)));
        //$this->debug('license', __FUNCTION__ . "-" . __LINE__, 'final');
        //$this->debug('license', __FUNCTION__ . "-" . __LINE__, $arr);

        if (!is_array($arr))
        {
            $this->debug('license', __FUNCTION__ . "-" . __LINE__, 'no array');
            return false;
        }

        return $arr;

    }
    public function is_valid()
    {

        if ($this->get_license_data('license') == 'valid')
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function extendedInfo($data = "")
    {
        if ($this->extended_info)
        {
            return $this->extended_info;
        }
        try
        {
            $arr['missing']               = "License Key does not exist";
            $arr['license_not_activable'] = "Trying to activate bundle license for  WP Movies";
            $arr['revoked']               = "License key revoked ";
            $arr['no_activations_left']   = "Maximum no of license activations used";
            $arr['expired']               = "This license has expired for  WP Movies";
            $arr['key_mismatch']          = "License keys don't match ";
            $arr['invalid_item_id']       = 'Invalid item Id ';
            $arr['item_name_mismatch']    = "Item names don't match";
            $arr['license_not_activable'] = "License can not be activated";

            $arr_license['site_inactive'] = "License for this domain is not active";
            $arr_license['disabled']      = " License key disabled";
            $arr_license['inactive']      = "This license is not active";

            $arr_license['disabled'] = " License key disabled";
            $arr_license['valid']    = "License is active and valid";
            $arr_license['invalid']  = "License is invalid";

            $arr_license['expired']            = "License is Expired";
            $arr_license['item_name_mismatch'] = "Item names don't match for this License";
            $arr_license['invalid_item_id']    = "Invalid item Id for this License";
            $arr_license['deactivated']        = "License has been deactivated ";
            $arr_license['unknown']            = "Unknown license Status";

            $data = $data ? $data : $this->get_license_data();

            if (!(is_array($data) || is_object($data)))
            {
                $data = array();
            }
            if (is_object($data))
            {
                $data = (array) $data;
            }

            if (!isset($data['license']))
            {
                $data['license'] = 'unknown';
            }
            $data['license_info'] = isset($arr_license[$data['license']]) ? $arr_license[$data['license']] : $data['license'];
            $data['error_info']   = isset($data['error']) ? isset($arr[$data['error']]) ? $arr[@$data['error']] : @$data['error'] : '';

            $msg[]               = isset($data['license']) ? (isset($arr_license[$data['license']]) ? $arr_license[$data['license']] : "Unknown license Status-{$data['license']} ") : "Unknown license Status";
            $msg[]               = isset($data['error']) ? (isset($arr_license[$data['error']]) ? $arr_license[$data['error']] : "Unknown license Error-{$data['error']} ") : "";
            $data['message']     = $msg[0] ? $msg[0] . ($msg[1] ? " $msg[1]" : '') : ($msg[1] ? $msg[1] : '');
            $this->extended_info = $data;
            return $this->extended_info;

        }
        catch (\Exception $e)
        {
            $data['license']      = 'unknown';
            $data['license_info'] = isset($arr_license[$data['license']]) ? $arr_license[$data['license']] : $data['license'];
            $data['message']      = $e->getMessage();
            $this->extended_info  = $data;
            return $this->extended_info;
        }
    }
    public function debug($section, $name, $msg)
    {
        return;
        if ($this->item_type != 'plugin')
        {
            // return;
        }
        static $first;
        if (!$first)
        {
            $first = time();
            p_l("$first=============debug started===================");

        }
        p_l("$first [Debug for [$section] item [$name]-> " . (is_array($msg) || is_object($msg) ? print_r($msg, true) : $msg) . "\n");

        //file_put_contents(dirname(__FILE__)."/testlog.txt","[Debug for [$section] item [$name]-> " . (is_array($msg) || is_object($msg) ? print_r($msg, true) : $msg) . "\n");
    }

}
