
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