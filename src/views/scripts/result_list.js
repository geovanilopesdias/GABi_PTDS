function loadExternalScript(url) {
    const script = document.createElement('script');
    script.src = url;
    script.async = true; // Load asynchronously to avoid blocking
    document.head.appendChild(script);
}

// Use the function to load your script
loadExternalScript('https://www.kryogenix.org/code/browser/sorttable/sorttable.js');
