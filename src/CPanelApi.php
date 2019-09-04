<?php
/**
 * CPanelApi -dev
 *
 * @author: Luciano Closs
 * This will work with cPanel API 2.0
 */
 
namespace LCloss\CPanelApi;

class CPanelAPI {
    protected $url = '';
    protected $port = 0;
    protected $user = '';
    protected $token = '';

    public function init($url, $port, $user, $token)
    {
        $this->url = $url;
        $this->port = $port;
        $this->user = $user;
        $this->token = $token;
    }

    public function addSubDomain($subdomain, $rootdomain, $dir) 
    {
        $module = "SubDomain";
        $function = "addsubdomain";
        $parameters = array(
            'domain'        => $subdomain,
            'rootdomain'    => $rootdomain,
            'canoff'        => 0,
            'dir'           => $dir,
            'disallowdot'   => 0
        );
        return $this->call($module, $function, $parameters);
    }

    public function deleteSubDomain($subdomain, $rootdomain)
    {
        $module = "SubDomain";
        $function = "delsubdomain";
        $parameters = array(
            'domain'       => $subdomain . '.' . $rootdomain
        );
        return $this->call($module, $function, $parameters);
    }

    public function listSubdomains()
    {
        $module = "SubDomain";
        $function = "listsubdomains";
        $parameters = array(
            'return_https_redirect_status'  => 0
        );
        return $this->call($module, $function, $parameters);
    }

    public function call($module, $function, $args = array())
    {
        $parameters = '';
        if ( count($args) > 0 ) {
            foreach( $args as $key => $value ) {
                $parameters .= '&' . $key . '=' . $value;
            }
        }

        $url = $this->url . ':' . $this->port . '/cpsess' . $this->token . '/json-api/cpanel?cpanel_jsonapi_user=' . urlencode( $this->user )
                          . '&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=' . $module . '&cpanel_jsonapi_func=' . $function 
                          . $parameters;

        $headers = array(
            "Authorization: cpanel " . $this->user . ':' . $this->token,
            "cache-control: no-cache"
        );

        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_PORT => $this->port,
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_POSTFIELDS => "",
          CURLOPT_HTTPHEADER => $headers,
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            return array(
                'status'    => 'error',
                'data'      => $err
            );
        } else {
            return array(
                'status'    => 'success',
                'data'      => $response
            );
        }
    }
}