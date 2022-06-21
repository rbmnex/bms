function searchRoad() {
    var formData = {};
    var valid = true;

    $.each($('#passageSearchFrm').serializeArray(), function(i, field) {
        formData[field.name] = field.value;
    });

    if (isEmpty(formData['name']) && isEmpty(formData['code'])) {
        $("#routeCodeTxt1").attr('placeholder', 'please fill one of these field');
        $("#routeCodeTxt1").addClass("is-invalid");
        valid = false;
    } else {
        $("#routeCodeTxt1").removeClass("is-invalid");
        $("#routeCodeTxt1").attr('placeholder', 'Route Code');
        valid = true;
    }

    if (valid) {
        $.post("/search/road", formData, function(data) {
            if (Array.isArray(data) && data.length) {
                $("#tbodyPassage").empty();
                data.forEach(element => {
                    $("#tbodyPassage").append("<tr>");
                    $("#tbodyPassage").append('<td class="border-0"><div class="">' +
                        '<input class="form-check-input" type="radio" name="idRoadRdo" value="' + element.id + '" checked>' +
                        '</div></td>');
                    $("#tbodyPassage").append('<td class="border-0"><div class="d-flex align-items-center">' + element.code +
                        '</div></td>');
                    $("#tbodyPassage").append('<td class="border-0"><div class="d-flex align-items-center">' + element.name + '</div></td>');
                    $("#tbodyPassage").append('<td class="border-0"><div class="d-flex align-items-center">' + element.number + '</div></td>');
                    $("#tbodyPassage").append('<td class="border-0"><div class="d-flex align-items-center">' + element.type + '</div></td>');
                    $("#tbodyPassage").append('<td class="border-0"><div class="d-flex align-items-center">' + element.km + '.' + element.meter + '</div></td>');
                    $("#tbodyPassage").append('<td class="border-0"><div class="d-flex align-items-center">' + ((element.primary == "1") ? 'Yes' : 'No') + '</div></td>');
                    $("#tbodyPassage").append('<td class="border-0"><div class="d-flex align-items-center">' + ((element.ou == 'OB') ? 'Under Bridge' : 'Over Bridge') + '</div></td>');
                    $("#tbodyPassage").append("</tr>");
                });
            } else {
                $("#tbodyPassage").empty();
                $("#tbodyPassage").append("<tr>");
                $("#tbodyPassage").append('<td class="border-0"></td>');
                $("#tbodyPassage").append('<td class="border-0"></td>');
                $("#tbodyPassage").append('<td class="border-0"></td>');
                $("#tbodyPassage").append('<td class="border-0"></td>');
                $("#tbodyPassage").append('<td class="border-0"></td>');
                $("#tbodyPassage").append('<td class="border-0"></td>');
                $("#tbodyPassage").append('<td class="border-0"></td>');
                $("#tbodyPassage").append('<td class="border-0"></td>');
                $("#tbodyPassage").append("</tr>");
            }
        });
    }
}

function selectedRoad() {

    var selector = $('input[name="idRoadRdo"]:checked').parent().parent();
    var ids = $('input[name="idRoadRdo"]:checked').val();
    var id = ids.split("-");
    $('#passageIdHdn').val(id[0]);
    $('#routeCodeTxt').val(selector.find('td:eq(1)').find('div').html());
    $('#routeNameTxt').val(selector.find('td:eq(2)').find('div').html());
    $('#passageNoTxt').val(selector.find('td:eq(3)').find('div').html());
    $('#passageTypeTxt').val(selector.find('td:eq(4)').find('div').html());
    var checked = (selector.find('td:eq(6)').find('div').html() == 'Yes') ? true : false;
    $('#passagePrimaryChk').prop('checked', checked);;
    $('#passageOUTxt').val(selector.find('td:eq(7)').find('div').html());
    $('#kilometerTxt').val(selector.find('td:eq(5)').find('div').html());

    if (id[2] != '0') {
        $('#stateSlt').val(id[2]).attr("selected", "selected");
        loadDistrict();
        setTimeout(function() {
            $('#districtSlt').val(id[1]).attr("selected", "selected");
        }, 1000);
    } else {
        $('#stateSlt').val("").attr("selected", "selected");
        $('#districtSlt').empty();
        $('#districtSlt').append('<option value="" selected>Please select</option>');
        $('#districtSlt').prop('disabled', true);
    }
}

function selectedUser() {
    var selector = $('input[name="idUserRdo"]:checked').parent().parent();
    var id = $('input[name="idUserRdo"]:checked').val();
    $('#user_id').val(id);
    $('#nameTxt').val(selector.find('td:eq(1)').find('div').html());
    $('#positionTxt').val(selector.find('td:eq(2)').find('div').html());
    //$('#departmentTxt').val(selector.find('td:eq(3)').find('div').html());
    $('#phoneTxt').val(selector.find('td:eq(5)').find('div').html());
    $('#officeTxt').val(selector.find('td:eq(4)').find('div').html());
    $('#emailTxt').val(selector.find('td:eq(6)').find('div').html());
}

function calArea() {
    var lenght = $('#totalLengthTxt').val();
    var width = $('#overallWidthTxt').val();

    if (lenght && width) {
        $('#areaTxt').val(parseFloat(lenght) * parseFloat(width));
    }
}

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#imgBridge').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]); // convert to base64 string
    }
}

function toogleRamp() {
    var val = $('#assetSlt').val();
    if (val == 8 || val == "8") {
        $('#rampSlt').attr('disabled', false);
    } else {
        $('#rampSlt').attr('disabled', true);
        $('#rampSlt').val("").attr("selected", "selected");
    }
}
