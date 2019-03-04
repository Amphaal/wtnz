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

function _waitEventEnd(eventTypeToListen, waiter, action) {

    return new Promise(function(resolve) {
        let ff = function(e) {
            waiter.removeEventListener(eventTypeToListen, ff);
            resolve(waiter);
        }
        waiter.addEventListener(eventTypeToListen, ff);
        action();
    });
}

function waitAnimationEnd(waiter, action) {
    return _waitEventEnd(_whichAnimationEndEvent(), waiter, action);
}

function waitTransitionEnd(waiter, action) {
    return _waitEventEnd(_whichTransitionEndEvent(), waiter, action);
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

function searchBand_foundRange(displayedName, slugFc, searchIndex) {
    let begin = searchIndex;
    let slugCurrent = slugify(displayedName, true);
    slugCurrent = slugCurrent.substring(searchIndex);
    searchIndex = 0;
    fcIndex = 0;
    
    for(searchIndex; fcIndex < slugFc.length; searchIndex++) {
        if(slugCurrent[searchIndex] == slugFc[fcIndex]) {
            fcIndex++;
        }
    }

    return [begin, begin + searchIndex];
}