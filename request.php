<?php
// set header for proper JSON response
  header('Content-Type: application/json');

// Collect params, so we know which page we want to scrape
  foreach($_GET as $key => $value) {
    $params[$key] = $value;
  }

// set up the page we want to scrape
  $uri = 'https://github.com/trending/'.$params['lang'].'?since='.$params['since'];      

// get the page
  $load = file_get_contents($uri);

// initialize repositories object, this will store the list of parsed repositories
  $repositories = [];

// names of statistics we want to assign
  $statKeys = ['stargazers','forks','stars'];  
  $totals = []; // array for holding total amounts
  foreach($statKeys as $key) {
    $totals[$key] = [];
  }

// parse and load the page as an HTML document 
  $doc = new DOMDocument();
  @$doc->loadHTML('<meta http-equiv="content-type" content="text/html; charset=utf-8">'.$load);

// grab the list of trending repositories from the page 
  $repos = $doc->getElementsByTagName('ol');

// initialize the array for holding the elements
  $repoList = [];

// loop through all elements we found and push them into the array
  foreach ($repos->item(0)->getElementsByTagName('li') as $repo) {
    array_push($repoList,cleanLinebreaks($repo->nodeValue)); // we use a helper function to get rid of line breaks we don't need                   
  }
  foreach($repoList as $repo) {
    // parse author and repo names from strings
      $authorAndRepoName = explode('/',$repo);  
      $author = $authorAndRepoName[0];
      $repoName = explode(' ',$authorAndRepoName[1])[1];
    
    // parse statistics from strings
      $arr = explode(' ',$repo);

    // array to hold statistics for this repo
      $stats = []; 
    
    // Loop through each entry and look for the statistics
      foreach($arr as $entry) {
        // remove commas
        $stat = str_replace(',','',$entry);  
        // use helper function to determine if an entry contains numbers
          if(isNumber($stat)) array_push($stats,$stat);
      }
    
    // verify if the stats array only contains numbers, filter out unwanted values
      $stats = array_filter($stats, 'is_numeric');
    
    // we know the values we need will always be the last 3 in the given string, so we remove all entries before that
      $stats = array_slice($stats, -3, 3);
    
    // give named keys to statistics, also push statistics into totals array for later calculation
      $statistics = [];
      for ($i=0; $i<count($statKeys);$i++) {
        if(isset($stats[$i])) {
          $statistics[$statKeys[$i]] = $stats[$i];
          array_push($totals[$statKeys[$i]],$stats[$i]);
        }        
      }
          
    // push results into repository array
      array_push($repositories, [
        'name' => $author,
        'repo' => $repoName,
        'statistics' => $statistics
      ]);
    }

// Calculate totals
  foreach($totals as $key => $value) {
    $totals[$key] = floor(array_sum($value));
  }

// Prepare JSON response 
  $response = [
    "status" => 200,
    "response" => [
      "totals" => $totals,
      "repositories" => $repositories
    ]
  ];

// JSON encode response and make it look pretty
  $out = json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);

// Echo response
echo $out;


// HELPER FUNCTIONS
function isNumber($string) {  
  if(1 === preg_match('~[0-9]~', $string)){
    return TRUE;
  }
}

function cleanLinebreaks($string) {
  $string = trim(preg_replace('/\s+/', ' ', $string));
  return $string;
}