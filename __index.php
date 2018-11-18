<?php
$uri = '';      
// Get cURL resource
$curl = curl_init();
// Set some options - we are passing in a useragent too here

// date 
$today = date('Y-m-d');
$lastWeek = date('Y-m-d',strtotime("-1 week +1 day"));
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://api.github.com/search/repositories?sort=stars&order=desc&q=stars',
    CURLOPT_USERAGENT => 'Simple API Request'
));
// Send the request & save response to $resp
$resp = json_decode(curl_exec($curl));
// Close request to clear up some resources
curl_close($curl);

foreach($resp->items as $repo) {
  echo $repo->name.', '.$repo->watchers_count.', '.$repo->stargazers_count.'<br/>';
}
var_dump($resp);