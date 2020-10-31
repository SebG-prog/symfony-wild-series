const addToWatchlist = (event) => {

// Get the link object you click in the DOM
   let watchlistIcon = event.target;
   let link = watchlistIcon.dataset.href;
   console.log(watchlistIcon, link)
// Send an HTTP request with fetch to the URI defined in the href
   fetch(link)
        .then(res => res.json())
        .then((res) => {
           if (res.isInWatchlist) {
               watchlistIcon.classList.remove('far'); 
               watchlistIcon.classList.add('fas');
            } else {
                watchlistIcon.classList.remove('fas'); 
                watchlistIcon.classList.add('far'); 
            }
       });
}

const test = document.getElementById("watchlist").addEventListener('click', addToWatchlist);

console.log('Hello watchlist');