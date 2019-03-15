
///
/// resize handling
///

function _rF(description, innerFunction) {
 return {
        description : description, 
        innerFunction : innerFunction
    };
}

//resize functions
function bindResizeFunctions() {
    resizeFunctions.width.push(
        _rF("resizeFeed", resizeFeed().applyNewHeight),
        _rF("resizeShout", resizeShout().applyNewHeight),
        _rF("alignConnectSideElements", alignConnectSideElements)
    );
    
    resizeFunctions.any.push(
        _rF("headerToggle", headerToggle)
    );

    Object.keys(_discoverFilter).forEach(function(id) {
        resizeFunctions.any.push(
            _rF("applyManualSizesFilterUIs[" + id + "]", function() { 
                return applyManualSizesFilterUIs(id);
            })
        );
    });
};

var sourceHeight = window.innerHeight;
var sourceWidth = window.innerWidth;
var resizeFunctions = {
    height : [],
    width : [],
    any : []
};

//event listener with throttle
window.addEventListener('resize', 
    debounce(resizeManualHeightsAndWidths, 250)
);

window.addEventListener('orientationchange', function() {
    window.scrollTo({left : 0});
});

function resizeManualHeightsAndWidths() {
    
    //prepare
    let newHeight = window.innerHeight;
    let newWidth = window.innerWidth;
    
    let execFunc = function(date, logTrackId) {
        return function(functorObj) {
            console.log("["+ date +"] " + logTrackId + " : " + functorObj.description);
            if(functorObj.innerFunction) functorObj.innerFunction();
        };
    };

    let date = new Date();

    //height or width...
    resizeFunctions.any.forEach(
        execFunc(date, "any")
    );

    //height...
    if(newHeight != sourceHeight) {
        sourceHeight = newHeight;
        resizeFunctions.height.forEach(
            execFunc(date, "height")
        );
    }

    //width...
    if(newWidth != sourceWidth) {
        sourceWidth = newWidth;
        resizeFunctions.width.forEach(
            execFunc(date, "width")
        );
    }

}

