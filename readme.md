# Github Trending
A web-scraper that reads the contents of www.github.com/trending and parses the results as a JSON resonse.

## The challenge 
> Create a RESTful API which provides one endpoint for now. This endpoint will fetch the list of trending open source repositories from github (e.g. https://github.com/trending) and sums up the amount of stars and watchers. Api client can provide a search term to filter by and a size parameter to limit the amount of analyzed repositories.

## Solving the problem
 The challenge specified that an API endpoint was to be created. Naturally, I lookd to the Github API for an easy solution... Sadly, it is currently not possible to fetch the ressources as laid out in the challenge through the Github API.

The next best result seemed to be to scrape the exisiting Github trends pages for content. Which is exactly what I ended up doing.

  - We build the website using the PHP DomDocument() library
  - We look for the list of trending repositories (luckily, they are all contained within one ordered list)
  - We extract the content from this list, and clean out all unwanted data
  - we create a JSON response object which includes the information as specified by the challenge

## The client
There is a miniature web-client with a script available that can make calls to the scraper via the index.html. Depending on the parameters passed on to the JS-function, different values can be queried for different programming languages and time period (as provided by Github).

## Shortcomings
Sadly, there was no time left to add a parameter to limit the query by size, or to make the client look prettiert, as the challenge had to be completed within 3 hours.