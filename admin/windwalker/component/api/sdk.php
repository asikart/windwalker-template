<?php
 
// no direct access
defined('_JEXEC') or die;

class AKApiSDK extends JObject
{
    protected $username = "" ;
    
    protected $password = "" ;
    
    public $host    = '' ;
    
    public $uri     = '' ;
    
    public $uri_cache   = array() ;
    
    public $result      = array() ;
    
    protected $relogin  = false ;
    
    protected $session_key          = '';
    
    protected $session_cache_path   = '' ;
    
    protected $session_cache_file   = '' ;
    
    /**
     * function __construct
     * @param 
     */
    public function __construct($host, $ssl = false)
    {
        $this->uri = new JURI() ;
        $this->setHost($host);
        $this->useSSL($ssl);
        
        // Get Session Key
        $this->session_cache_path = JPATH_ROOT.'/cache/AKApiSDK' ;
        $this->session_cache_file = $this->session_cache_path.'/session_key' ;
        
        $this->session_key = $this->getSessionKey() ;
    }
    
    /**
     * function getInstance
     * @param 
     */
    public static function getInstance($host, $ssl = false)
    {
        static $instances ;
        
        $hash = AKHelper::_('system.uuid', $host, 3);
        
        if(!empty($instances[$hash])) {
            return $instances ;
        }
        
        $instances[$hash] = new AKApiSDK($host);
        
        return $instances[$hash] ;
    }
    
    /**
     * function setHost
     * @param $host
     */
    public function setHost($host)
    {
        $uri = new JURI($host);
        $this->host = $host = $uri->getHost().$uri->getPath();
        
        $this->uri->setHost($host);
    }
    
    /**
     * function getHost
     * @param $host
     */
    public function getHost($host)
    {
        return $this->host;
    }
    
    /**
     * function useSSL
     * @param $ssl
     */
    public function useSSL($ssl)
    {
        if($ssl) {
            $this->uri->setScheme('https');
        }else{
            $this->uri->setScheme('http');
        }
    }
    
    /**
     * function execute
     * @param $uri
     */
    public function execute($path, $query = '', $method = 'get', $type = 'object')
    {
        // Set Session Key
        $query = (array)$query ;
        $query['session_key'] = $this->session_key ;
        
        // Do Execute
        $result = $this->doExecute($path, $query, $method, $type) ;
        
        // If not login or session expire, relogin.
        if( !$result ) {
            
            if($this->relogin) {
                
                // Do Login
                $login_result = $this->login();
                
                if( !$login_result ) {
                    return false ;
                }
                
                // Reset session
                $query['session_key'] = $this->session_key = $login_result->session_key;
                
                // Write in cache file
                JFile::write( $this->session_cache_file, $this->session_key );
                
                // Debug ------------
                AKHelper::_('system.mark', 'New session_key: '. $this->session_key, 'WindWalker') ;
                // ------------------
                
                // Resend Request
                $result = $this->doExecute($path, $query, $method, $type) ;
            }
        }
        
        return $result ;
    }
    
    /**
     * function doExecute
     * @param 
     */
    public function doExecute($path, $query = '', $method = 'get', $type = 'object', $ignore_cache = false)
    {
        // Set URI Path
        $uri = $this->uri ;
        $uri->setPath('/api/'.trim($path, '/'));
        $uri->setQuery(array());
        
        // Set Query
        if($method == 'post') {
            $query = $this->buildAPIQuery($query, false) ;
        }else{
            $query = $this->buildAPIQuery($query, false) ;
            $uri->setQuery($query);
        }
        
        // Set Cache
        $key = AKHelper::_('system.uuid', (string)$uri, 3) ;
        if( isset( $this->result[$key] ) && !$ignore_cache) {
            return $this->handleResult($this->result[$key], $type) ;
        }
        
        // Send Request By CURL
        $result = AKHelper::_('curl.getPage', (string)$uri, $method, $query) ;
        
        
        // Debug ------------
        $this->i++ ;
        AKHelper::_('system.mark', "Send {$this->i}: ".(string)$uri, 'WindWalker') ;
        // ------------------
        
        if(!$result) {
            $this->setError(AKHelper::_('curl.getError'));
            return false ;
        }
        
        $this->result[$key] = $result ;
        
        return $this->handleResult($this->result[$key], $type) ;
    }
    
    /**
     * function handleResult
     */
    public function handleResult($data, $type = 'object')
    {
        $result = json_decode($data) ;
        
        // is json format?
        if($result === null) {
            $this->setError('Return string not JSON format.');
            return false ;
        }
        
        // Detect Error message
        if( !isset($result->ApiResult) ){
            
            if( isset($result->ApiError->errorMsg) ) {
                $this->setError($result->ApiError->errorMsg);
                
                // If 404, need relogin.
                if($result->ApiError->errorNum == 404) {
                    $this->relogin = true ;
                }
                
                return false ;
            }else{
                $this->setError('API System return no result.');
                return false ;
            }
            
        }
        
        // Set return type.
        if($type == 'array') {
            $result = json_decode($data, true);
            return $result['ApiResult'] ;
        }elseif($type == 'raw'){
            return $data ;
        }else{
            return $result->ApiResult ;
        }
    }
    
    /**
     * function getSessionKey
     * @param 
     */
    public function getSessionKey()
    {
        // read session key from cache file
        $cache_path = $this->session_cache_path ;
        $cache_file = $this->session_cache_file ;
        
        if( !JFile::exists($cache_file) ) {
            $content = '' ;
            JFolder::create($cache_path);
            JFile::write($cache_file, $content) ;
        }
        
        $session_key = JFile::read($cache_file) ;
        
        // Debug
        AKHelper::_('system.mark', 'session_key: '. $session_key, 'WindWalker') ;
        
        if(!$session_key) {
            
            // Login
            $result = $this->login();
            
            if(!$result) {
                return false ;
            }
            
            if(!$result->success) {
                return false ;
            }
            
            // Write in cache file
            JFile::write( $cache_file, $result->session_key );
            $session_key = $result->session_key ;
        }
        
        return $session_key ;
    }
    
    /**
     * function login
     * @param 
     */
    public function login()
    {
        $uriQuery['username'] = $this->username;
        $uriQuery['password'] = $this->password;
        
        // Do execute
        $result = $this->doExecute('/user/login', $uriQuery, 'post') ;
        
        return $result ;
    }
    
    /**
     * function getURI
     * @param 
     */
    public function getURI()
    {
        return $this->uri ;
    }
    
    /**
     * function setVar
     * @param $key
     */
    public function setVar($name, $value)
    {
        $this->uri->setVar($name, $value);
    }
    
    /**
     * function getVar
     * @param $key
     */
    public function getVar($name, $default = null)
    {
        $this->uri->getVar($name, $default);
    }
    
    /**
     * function delVar
     * @param $key
     */
    public function delVar($name)
    {
        $this->uri->delVar($name);
    }
    
    /**
     * function setQuery
     * @param $queries
     */
    public function setQuery($queries)
    {
        $this->uri->setQuery($queries);
    }
    
    /**
     * function hash
     * @param $datas
     */
    public static function hash($datas)
    {
        $datas = (array) $datas ;
        $datas = implode('', $datas) ;
        
        return md5($datas);
    }
    
    /**
     * function buildAPIQuery
     * @param $array
     */
    public function buildAPIQuery($array, $string = true)
    {
        // Remove empty values
        foreach( $array as $key => &$val ):
            if($val === '' || JString::substr($key, 0, 1) == '_') {
                unset( $array[$key] );
            }
            
            if( is_array($val) || is_object($val) ) {
                
                $val = (array) $val ;
                
                foreach( $val as $key2 => &$val2 ):
                    
                    if( $val2 === ''  || JString::substr($key2, 0, 1) == '_') {
                        unset( $array[$key][$key2] ) ;
                    }
                    
                endforeach;
                
            }
            
        endforeach;
        
        if($string){
            $array = http_build_query($array);
        }
        
        return $array ;
    }
}