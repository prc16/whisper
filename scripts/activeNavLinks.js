  // Get the current URL
  var currentUrl = window.location.href;

  // Get the links in the navbar
  var links = document.querySelectorAll('nav a');

  // Loop through each link and check if its href matches the current URL
  links.forEach(function(link) {
    if (link.href === currentUrl) {
      link.classList.add('active'); // Add the 'active' class
    }
  });