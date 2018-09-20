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

 //desc sorting of obj
function descSortObj(objToSort) {
    
    //prepare
    let keys = Object.keys(objToSort);

    //array count for sorting
    let toArrayForm = keys.reduce(function(total, currentVal) {
        total.push({
            name : currentVal,
            value : objToSort[currentVal].size || objToSort[currentVal]
        })
        return total;
    }, []);

    //descending sort
    return toArrayForm.sort(function (a, b) {
        return b.value - a.value;
    });
}

//detect transition handler
function whichTransitionEndEvent() {
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

function whichTransitionStartEvent() {
	var el = document.createElement('fakeelement');
	var transitions = {
		'transition': 'transitionstart',
		'OTransition': 'oTransitionStart',
		'MozTransition': 'transitionstart',
		'WebkitTransition': 'webkitTransitionStart'
	}

	for (var t in transitions) {
		if (el.style[t] !== undefined) {
			return transitions[t];
		}
	}
}


function whichAnimationEvent() {
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
    let duration = moment.duration(dateNow.diff(dateThen));
    return duration.humanize() + ' ago';
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
