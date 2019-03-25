function _popup(event) {
    event.currentTarget.classList.toggle("hide");
}

function RGBToHex(rgb) {
    // Choose correct separator
    let sep = rgb.indexOf(",") > -1 ? "," : " ";
    // Turn "rgb(r,g,b)" into [r,g,b]
    rgb = rgb.substr(4).split(")")[0].split(sep);
  
    let r = (+rgb[0]).toString(16),
        g = (+rgb[1]).toString(16),
        b = (+rgb[2]).toString(16);
  
    if (r.length == 1)
      r = "0" + r;
    if (g.length == 1)
      g = "0" + g;
    if (b.length == 1)
      b = "0" + b;
  
    return "#" + r + g + b;
  }

function getSiblings(elem) {

	// Setup siblings array and get the first sibling
	var siblings = [];
	var sibling = elem.parentNode.firstChild;

	// Loop through each sibling and push to the array
	while (sibling) {
		if (sibling.nodeType === 1 && sibling !== elem) {
			siblings.push(sibling);
		}
		sibling = sibling.nextSibling
	}

	return siblings;

};

//ucwords()
function titleCase(str) {
    let splitStr = str.toLowerCase().split(' ');
    for (let i = 0; i < splitStr.length; i++) {
        // You do not need to check if i is larger than splitStr length, as your for does that for you
        // Assign it back to the array
        splitStr[i] = splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1);     
    }
    // Directly return the joined string
    return splitStr.join(' '); 
}

//detect transition handler
function _whichTransitionEndEvent() {
	var el = document.createElement('fakeelement');
	var transitions = {
		'transition': 'transitionend',
		'OTransition': 'oTransitionEnd',
		'MozTransition': 'transitionend',
		'WebkitTransition': 'webkitTransitionEnd'
	}

	for (var t in transitions) {
		if (el.style[t] !== undefined) {
			return transitions[t];
		}
	}
}

function _whichAnimationEndEvent() {
    var t,
        el = document.createElement("fakeelement");
    var animations = {
        "animation"      : "animationend",
        "OAnimation"     : "oAnimationEnd",
        "MozAnimation"   : "animationend",
        "WebkitAnimation": "webkitAnimationEnd"
    };
    for(t in animations) {
        if (el.style[t] !== undefined){
            return animations[t];
        }
    }
}

var _waiterStack = {};
function _waitEventEnd(eventTypeToListen, waiter, action) {

    if(!action) return Promise.resolve(null);

    return new Promise(function(resolve) {
    
        let newId = String(Date.now()) + '_' + String(Math.round(Math.random()*100)) + '_' + String(waiter.id);
        let eventsToExpect = window.getComputedStyle(waiter, null)["transition-property"].split(",");

        _waiterStack[newId] = {
            expectedEvents : eventsToExpect,
            eventEndHits : [],
            onEventEnd : function(e) {
                
                //event triggered on property
                _waiterStack[newId].eventEndHits.push(e.propertyName);
                
                //if count... disengage
                if(_waiterStack[newId].eventEndHits.length == _waiterStack[newId].expectedEvents.length) {
                    //console.log("out", waiter);
                    waiter.removeEventListener(eventTypeToListen, _waiterStack[newId].onEventEnd);
                    delete _waiterStack[newId];
                    resolve(waiter);
                }
            }
        };

        //console.log("in", waiter);
        waiter.addEventListener(eventTypeToListen, _waiterStack[newId].onEventEnd);
        
        action(waiter);
    });
}

function waitAnimationEnd(waiter, action) {
    return _waitEventEnd(_whichAnimationEndEvent(), waiter, action);
}

function waitTransitionEnd(waiter, action) {
    return _waitEventEnd(_whichTransitionEndEvent(), waiter, action);
}

function debounce(callback, delay){
    var timer;
    return function(){
        var args = arguments;
        var context = this;
        clearTimeout(timer);
        timer = setTimeout(function(){
            callback.apply(context, args);
        }, delay)
    }
}


function ezPOST(data) {
    let form = document.createElement("form");
    form.setAttribute("method", "POST");
    
    Object.keys(data).map(function(key) {
        let iElem = document.createElement('input');
        iElem.setAttribute("type", "hidden");
        iElem.setAttribute("name", key);
        iElem.setAttribute("value", data[key]);
        return iElem;
    }).forEach(function (item) { form.appendChild(item); });
    
    document.body.appendChild(form);
    form.submit();
}

function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

function compareDateFromNomHumanized(strISO8601, dateNow) {
    if(!dateNow) dateNow = new moment();
    let dateThen = moment(strISO8601);
    dateThen.locale(lang);
    return dateThen.from(dateNow);
}


function calculateSecondsElapsed(dateFrom) {
    let dateNow = new moment();
    let dateThen = moment(dateFrom);
    return moment.duration(dateNow.diff(dateThen)).asSeconds();
}

function getRootElementFontSize() {
    // Returns a number
    return parseFloat(
        // of the computed font-size, so in px
        getComputedStyle(
        // for the root <html> element
        document.documentElement
        ).fontSize
    );
}

function slugify(string, keepOriginalLength) {
    
    keepOriginalLength = keepOriginalLength ? "-" : '';

    const a = 'àáäâãåèéëêìíïîòóöôùúüûñçßÿœæŕśńṕẃǵǹḿǘẍźḧ·/_,:;';
    const b = 'aaaaaaeeeeiiiioooouuuuncsyoarsnpwgnmuxzh------';
    const p = new RegExp(a.split('').join('|'), 'g');
    return string.toString().toLowerCase()
        .replace(/\s+/g, '-') // Replace spaces with
        .replace(p, function(c) { 
            return b.charAt(a.indexOf(c));
        }) // Replace special characters
        .replace(/&/g, '-and-') // Replace & with ‘and’
        .replace(/[^\w\-]+/g, keepOriginalLength) // Remove all non-word characters
        .replace(/\-\-+/g, '-') // Replace multiple — with single -
        .replace(/^-+/, ''); // Trim — from start of text .replace(/-+$/, '') // Trim — from end of text
}



function _XMLHttpPromise(method, url, POSTParams) {
    
    return new Promise(function (resolve, reject) {
        
        let xhr = new XMLHttpRequest();
        if(!method) method = "GET";
        xhr.open(method, url);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        
        xhr.onload = function () {
            if (this.status >= 200 && this.status < 300) {
                resolve(xhr);
            } else {
                reject({
                    status: this.status,
                    statusText: xhr.statusText
                });
            }
        };
        
        xhr.onerror = function () {
            reject({
                status: this.status,
                statusText: xhr.statusText
            });
        };
        
        xhr.send(POSTParams);
    });

}

function changeLang(event) {
    let newLang = event.currentTarget.getAttribute("data-lang");
    ezPOST({
        set_lang : newLang
    });
}