function searchRoute() {
    var formData = {};
    var valid = true;

    $.each($('#routeFrm').serializeArray(), function(i, field) {
        formData[field.name] = field.value;
    });

    if (isEmpty(formData['name']) && isEmpty(formData['code'])) {
        $("#routeCodeTxt1").attr('placeholder', 'please fill one of these field');
        $("#routeCodeTxt1").addClass("is-invalid");
        valid = false;
    } else {
        $("#routeCodeTxt1").removeClass("is-invalid");
        valid = true;
    }

    if (valid) {
        $.post("/search/route", formData, function(data) {
            if (Array.isArray(data) && data.length) {
                $("#tbodyR").empty();
                data.forEach(element => {
                    $("#tbodyR").append("<tr>");
                    $("#tbodyR").append('<td class="border-0"><div class="">' +
                        '<input class="form-check-input" type="radio" name="idRouteRdo" value="' + element.id + '" checked>' +
                        '</div></td>');
                    $("#tbodyR").append('<td class="border-0"><div class="d-flex align-items-center">' + element.code +
                        '</div></td>');
                    $("#tbodyR").append('<td class="border-0"><div class="d-flex align-items-center">' + element.name + '</div></td>');
                    $("#tbodyR").append('<td class="border-0"><div class="d-flex align-items-center">' + element.type + '</div></td>');
                    $("#tbodyR").append("</tr>");
                });

                $('#tableRoute').DataTable();
            } else {
                $("#tbodyR").empty();
                $("#tbodyR").append("<tr>");
                $("#tbodyR").append('<td class="border-0"></td>');
                $("#tbodyR").append('<td class="border-0"></td>');
                $("#tbodyR").append('<td class="border-0"></td>');
                $("#tbodyR").append('<td class="border-0"></td>');
                $("#tbodyR").append("</tr>");
            }
        });
    }
}

function selectedItem() {
    var id = $('input[name="routeid"]:checked').val();
    var td = $('input[name="routeid"]:checked').parent();
    $("#routeCodeTxt1").val(td.next().find("div").html());
    $("#routeNameTxt1").val(td.next().next().find("div").html());
    $("#routeIdhdn").val(id);
}