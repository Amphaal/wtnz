function rLoaderAnimation() {

    let rloader = document.getElementById("xmlRLoader");
    let firstAnim = rloader.style.maxHeight == "";
    
    if(firstAnim) return new Promise(function(resolve) {

        removeNotification(".connect-side");

        waitTransitionEnd(rloader, function() {
            rloader.style.maxHeight = rloader.scrollHeight + "px";
        }).then(function() {
            waitAnimationEnd(rloader, function() {
                rloader.classList.add("bounceIn");
                document.getElementById("connectContainer").classList.add("anima");
                document.querySelector("#wtnz-connect .connect-side").classList.add("anima");
                document.getElementById("bg").classList.add("show");
            }).then(function(){
                rloader.style.opacity = 1;
                resolve();
            });
        });
    });

    /* dummy promise */
    return new Promise(function(resolve) {resolve();});
}



function initRLoader() {
    let rloader = document.getElementById("xmlRLoader");
    rloader.style.pointerEvents = "";

    let aBtns = rloader.querySelectorAll("button[href]");
    let aForm = rloader.querySelector("form");

    let goXHTML = function(method, url) {
        if(!method) method = "GET";
        rloader.style.pointerEvents = "none";
        let xmlR = new XMLHttpRequest();
        xmlR.open(method, url, true);
        xmlR.onloadend = function(resp) {
            let content = resp.currentTarget.responseText;
            rloader.innerHTML = content;
            initRLoader();
        };
        xmlR.send(null);
    };


    if(aBtns.length) aBtns.forEach(function(e) {
        let url = e.getAttribute("href");
        e.onclick = function(event) {
            event.preventDefault();
            goXHTML("GET", url);
        };
        e.removeAttribute("href");
    });

    if(aForm) aForm.onsubmit = function(event) {
        event.preventDefault();
        goXHTML(aForm.getAttribute("method"), aForm.getAttribute("action"));
    };
}
