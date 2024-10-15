function loadHeader() {
    const isMobile = window.innerWidth < 1000;
    const urlParams = new URLSearchParams(window.location.search);
    
    if (isMobile && !urlParams.has('mobile')) {
        // Reload the page with the 'mobile' parameter set
        urlParams.set('mobile', '1');
        window.location.search = urlParams.toString();
    } else if (!isMobile && urlParams.has('mobile')) {
        // Reload the page without the 'mobile' parameter
        urlParams.delete('mobile');
        window.location.search = urlParams.toString();
    }
}

loadHeader();
window.addEventListener('resize', loadHeader);