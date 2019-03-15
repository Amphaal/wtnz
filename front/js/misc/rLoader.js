function rLoaderAnimation() {

    let rloader = document.getElementById("xmlRLoader");
    let firstAnim = rloader.style.maxHeight == "";
    
    if(firstAnim) return new Promise(function(resolve) {

        removeNotification(".connect-side");
        
        document.querySelector("#wtnz-connect .connect-side").classList.add("anima");
        document.getElementById("bg").classList.add("show");
        let cc = document.getElementById("connectContainer");

        waitTransitionEnd(cc, function() { 
            cc.classList.add("anima");
        }).then(function() {
            expandRLoader(rloader);
            resolve();
        });

    });

    /* dummy promise */
    return Promise.resolve(null);
}


function expandRLoader(rloader) {
    rloader.style.maxHeight = "100%";
    rloader.style.maxWidth = "100%";
}

function fillRLoader(rloader) {
    return function (response) {
        
        let content = response.currentTarget.responseText;

        if(content) {
            rloader.innerHTML = content;
            initRLoader();
            expandRLoader(rloader);
        } else {
            rloader.style.pointerEvents = "";
        }
    };
}

function initRLoader() {
    let rloader = document.getElementById("xmlRLoader");
    rloader.style.pointerEvents = "";

    let aBtns = rloader.querySelectorAll("a[href]:not([no-xhttp])");
    let aForm = rloader.querySelector("form");

    let goXHTML = function(method, url, POSTParams) {
        if(!method) method = "GET";
        rloader.style.pointerEvents = "none";
        let xmlR = new XMLHttpRequest();
        xmlR.open(method, url, true);
        xmlR.onloadend = fillRLoader(rloader);
        xmlR.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xmlR.send(POSTParams);
    };

    //buttons
    if(aBtns.length) aBtns.forEach(function(e) {
        let url = e.getAttribute("href");
        e.onclick = function(event) {
            event.preventDefault();
            goXHTML("GET", url);
        };
        e.removeAttribute("href");
    });

    //forms
    if(aForm) aForm.onsubmit = function(event) {
        event.preventDefault();
        goXHTML(
            aForm.getAttribute("method"), 
            aForm.getAttribute("action"),
            new FormData(aForm)
        );
    };
}
