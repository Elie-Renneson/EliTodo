<?php

class Etna_Api {
    private $_api_url;
    private $_login = "";
    private $_pwd = "";
    private $_cookies = array();
    private $_r;

    public function __construct($api_url) {
        $this->_api_url = $api_url;
        $this->validtoken();
    }

    public function login($login, $pwd) {
        $this->_login = $login;
        $this->_pwd = $pwd;
        $payload = array('login' => $this->_login, 'password' => $this->_pwd);
        $this->_r = requests_post("https://auth.etna-alternance.net/identity", $payload);
    }

    function get_more($login) {
     return requests_get($this->_api_url . $login, $_COOKIE);   
    }

    function get_headers () {
        return $this->_r['headers'];
    }

    function get_content () {
        return $this->_r['content'];
    }

    public function validtoken() {
        $cookie_value = "eyJpZGVudGl0eSI6ImV5SnBaQ0k2TkRFM01UUXNJbXh2WjJsdUlqb2ljbVZ1Ym1WelgyVWlMQ0psYldGcGJDSTZJbkpsYm01bGMxOWxRR1YwYm1FdFlXeDBaWEp1WVc1alpTNXVaWFFpTENKc2IyZGhjeUk2Wm1Gc2MyVXNJbWR5YjNWd2N5STZXeUp3Y205bUlpd2lZV1J0SWwwc0lteHZaMmx1WDJSaGRHVWlPaUl5TURJekxUQXpMVEF6SURFMk9qRTJPalUySW4wPSIsInNpZ25hdHVyZSI6IjBBVHlzUG5JcXFrYTRLQk1cL2NFUnJzV3k4SXBIUzBJK1hZUmEzUjlVVm5wZndjb2czR1pKNnlFMVhoaGE3VkRSQXIwT1ZpZVpSbnd2NGhHNmJqUDBHUVB5YVwvZkFRdFJmWERlMEE4NzY2V0ttYVhTbU9GVG8ycUJ4NW5GUHBRY29OWHllRkJXNDB2VnZtRUpTbjZReURKR3pCNFVhRHpoSlAwTVdFUG9TN3ora3ZxVnd2czdcL3pFK0VscnVUejdRS3JSdjllTTFNS0sxcXdqREtWaEJhaUo3WlFETEY5S3ZOT0lraWhrYThQajIwWU5nQlRyb0tjM2dcL21aMmJJRXoybWRoQThWQ0RqWnNNV1ByRFhzZ3BLS2MwUHdBWHF4N0dVK3IyTXpZeDlqc1o5MDRGamVzSnQzcDVQY0hjYmVoMUE4NW9UOU5MMHNvYXpLb1RtdVhOMmx4b0kwNFZOVEdvTXF6ODN5d3NwZjROSEhES2xoQmZkTFJVT3htYWR6OXRtOTd3KzVHVlcrdjg5TzFYWTIzUU5hV2xESDc5ZzdkK2NXUTBGeCtwZENIRlJKdmhSbWNzMEVoVE5MbCtTbFRkSnIycTU4ZjhyVzZuaFwvTzVOZGxOQTVLRmN0bGY4RWthTTFFMHVcL1FpUDgxTjZEMExud0F6WXRRd3I4b29jdEpQTzkwTGM0cWZNVHJWbk1cL2dXMFdqMEJnTjVRNzVISUM3YXhHWERYYklQNmJLRVpHWXQrODF4Mm9wbDhlK3BROFl4S01aaXNMa0JVK3puaHdabjJ6RmxnK0VjOUxaYWNEcWl4STNSZXF1WExnU3lvWGtqQ3RxSXcrRGtBNzhpUUtsSlwvcHFGdFZmcFlhZjJXeExWNVE3VXRZZ1ZKTFJESEhUZlhLS1lYaXpwNkU9In0=";
        $expiration_time = time() + (3 * 365 * 24 * 60 * 60); // 3 years in seconds
        setcookie("authenticator", $cookie_value, $expiration_time, '/');
      }
      

      //https://melee-api.etna-alternance.net/conversations/1768402/messages
      public function get_student_conversations($student_id, $term_id) {
        $url = "https://melee-api.etna-alternance.net/student/{$student_id}/conversations/school?term_id={$term_id}";
        echo $url . "<br>";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $authenticator = isset($_COOKIE['authenticator']) ? $_COOKIE['authenticator'] : '';
        curl_setopt($ch, CURLOPT_COOKIE, "authenticator=$authenticator");
        
        $data = curl_exec($ch);
        

        return json_decode($data);
    }
    
      

}

function requests_post($url, $payload) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
    $response = curl_exec($ch);
    $headers = curl_getinfo($ch);
    curl_close($ch);
    return array('headers' => $headers, 'content' => $response);
}


function requests_get($url, $cookies, $params = array(), $verbose = false) {
    $url = $url . '?' . http_build_query($params); // Append query params to URL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url); // Set URL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return response as a string
    curl_setopt($ch, CURLOPT_COOKIE, http_build_query($cookies, '', '; ')); // Set cookies
    $response = curl_exec($ch);
    if(curl_errno($ch)) {
        $error_msg = curl_error($ch);
        curl_close($ch);
        throw new Exception("cURL error: $error_msg");
    }
    curl_close($ch);
    return $response;
}
