
///
/// resize handling
///

//resize functions
function bindResizeFunctions() {
    resizeFunctions.width.push(function() {return resizeFeed().applyNewHeight()});
    resizeFunctions.width.push(function() {return resizeShout().applyNewHeight()});
    resizeFunctions.width.push(function() {return alignConnectSideElements()});
    
    resizeFunctions.any.push(function() {
        return headerToggle();
    });
    Object.keys(_discoverFilter).forEach(function(id) {
        resizeFunctions.any.push(applyManualSizesFilterUIs(id));
    })
}


var timeoutResize = false;
var delayResize = 100;
var sourceHeight = window.innerHeight;
var sourceWidth = window.innerWidth;
var resizeFunctions = {
    height : [],
    width : [],
    any : []
};

//event listener with throtte
window.addEventListener('resize', function(event) {
    clearTimeout(timeoutResize);
    timeoutResize = setTimeout(resizeManualHeightsAndWidths, delayResize);
});

window.addEventListener('orientationchange', function() {
    window.scrollTo({left : 0});
})



function resizeManualHeightsAndWidths() {
    
    //prepare
    let newHeight = window.innerHeight;
    let newWidth = window.innerWidth;
    
    //height or width...
    resizeFunctions.any.forEach(function(func) {
        func();
    });

    //height...
    if(newHeight != sourceHeight) {
        sourceHeight = newHeight;
        resizeFunctions.height.forEach(function(func) {
            func();
        });
    }

    //width...
    if(newWidth != sourceWidth) {
        sourceWidth = newWidth;
        resizeFunctions.width.forEach(function(func) {
            func();
        });
    }

}

