/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

$(document).ready(function () {
    $(".first_image").on("click", function (e) {
        e.preventDefault();
        let slot = $(this).data("slot");
        $('input[name="image_position"]').val(slot);
    });

    $("#add-sponsor-form").on("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        console.log(formData);
        $.ajax({
            url: "/seller/add-sponsor-req",
            method: "get",
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                console.log(res);
            },
        });
    });
});
