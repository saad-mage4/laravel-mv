/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

$(document).ready(function () {
    $(".slot-images").on("click", function (e) {
        e.preventDefault();
        let slot = $(this).data("slot");
        $("#add-sponsor-modal #prod-link").val("https://");
        $("#banner-img").attr("required", true);
        $('input[name="image_position"]').val(slot);
    });

    $("#banner-img").change(function (e) {
        let fileName =
            e.target.files.length > 0
                ? e.target.files[0].name
                : "Choose file...";
        $("label[for='banner-img']").text(fileName);
    });

    $(".viewSponsor").on("click", function (e) {
        e.preventDefault();
        let position = $(this).data("position");
        let isBooked = $(this).data("booked");

        $.ajax({
            url: "/seller/get-sponsor",
            method: "get",
            data: { position: position, isBooked: isBooked },
            success: function (res) {
                $("#add-sponsor-modal #prod-link").val(res.banner_redirect);
                $("#add-sponsor-modal #banner-img").removeAttr("required");
                $("#add-sponsor-modal input[name='image_position']").val(
                    res.banner_position
                );
                $("#add-sponsor-modal").modal("show");
            },
        });
    });

    // $("#add-sponsor-form").on("submit", function (e) {
    //     e.preventDefault();
    //     let formData = new FormData(this);
    //     console.log(formData);
    //     $.ajax({
    //         url: "/seller/add-sponsor-req",
    //         method: "post",
    //         data: formData,
    //         success: function (res) {
    //             console.log(res);
    //         },
    //         error: function (xhr, status, error) {
    //             console.error(xhr.responseText);
    //         },
    //         cache: false,
    //         processData: false,
    //         contentType: false,
    //     });
    // });
});
