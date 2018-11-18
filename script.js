function fetchRepos(timePeriod,lang) {
var req = new XMLHttpRequest();
  req.open("GET", "request.php?since="+timePeriod+"&lang="+lang);
  req.send();
  req.onreadystatechange = function () {
    if (req.readyState == 4 && req.status == 200) {
      var res = JSON.parse(this.responseText);				
      console.log(res);
      buildList(res)
    } else {
      //error();
    }
  }
}
function buildList(res) {
  var container = document.querySelector('.repo-list')
  container.innerHTML = '';
  res.response.repositories.forEach(function(repo){    
    container.innerHTML += '<div>'+repo.name+' '+repo.repo+'<br/>Watchers: '+repo.statistics.stargazers+' Forks: '+repo.statistics.forks+' Stars Today: '+repo.statistics.stars+'</div>';
  });
}