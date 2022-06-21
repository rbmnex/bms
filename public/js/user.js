function toogleValue(ele) {
    var arr = [];
    var val = $(ele).val();


    if ($(ele).is(':checked')) {
        arr.push(val);
    } else {
        arr = arr.filter(function(value, index, arr) {
            return value != val;
        });
    }
    console.log(arr);
    $('#rolelistid').val(arr);
}