
class RLoader {

    constructor(rLoaderId) {
        this._rLoader = document.getElementById(rLoaderId);
        this._isDirty = !this._rLoader.getAttribute("loaded");
        if(this._isDirty) this._init();
    }

    initialAnimation() {
        let firstAnim = this._rLoader.style.opacity === "";

        if(firstAnim) return new Promise(function(resolve) {

            removeNotification(".connect-side");
            
            document.querySelector("#wtnz-connect .connect-side").classList.add("anima");
            document.getElementById("bg").classList.add("show");
            let cc = document.getElementById("connectContainer");
    
            waitTransitionEnd(cc, function() { 
                cc.classList.add("anima");
            })
            .then(this._fadeIn.bind(this))
            .then(resolve);
    
        }.bind(this));
    
        /* dummy promise */
        return Promise.resolve(null);
    }

    _getButtons() {
        return this._rLoader.querySelectorAll("button[href]");
    }

    _getForm() {
        return this._rLoader.querySelector("form");
    }

    _fadeIn() {
        return waitTransitionEnd(this._rLoader, function() {
            this._rLoader.style.pointerEvents = "";
            this._rLoader.style.opacity = 1;
        }.bind(this));
    }

    _fadeOut() {
        return waitTransitionEnd(this._rLoader, function() {
            this._rLoader.style.pointerEvents = "none";
            this._rLoader.style.opacity = 0;
        }.bind(this));
    }

    _goXMLR(method, url, POSTParams) {

        let awaitAll = [
            _XMLHttpPromise(method, url, POSTParams), 
            this._fadeOut()
        ];
        
        return Promise.all(awaitAll).then(function(results) {
            this._fillWithContent(results[0]);
        }.bind(this));

    };

    _fillWithContent(content) {
        
        if(content) {
            this._rLoader.innerHTML = content;
            this._init();
        }

        this._fadeIn();

    }


    _init() {
    
        //buttons
        let aBtns = this._getButtons();
        if(aBtns.length) aBtns.forEach(function(e) {
            let url = e.getAttribute("href");
            e.onclick = function(event) {
                event.preventDefault();
                this._goXMLR("GET", url);
            }.bind(this);
            e.removeAttribute("href");
        }.bind(this));
    
        //forms
        let aForm = this._getForm();
        if(aForm) aForm.onsubmit = function(event) {
            event.preventDefault();
            this._goXMLR(
                aForm.getAttribute("method"), 
                aForm.getAttribute("action"),
                new FormData(aForm)
            );
        }.bind(this);

        this._rLoader.setAttribute("loaded", true);
    }

}

