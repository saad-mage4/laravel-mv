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

        $.ajax({
            url: "/seller/get-sponsor",
            method: "get",
            data: { position: position },
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



    const checkSpan_withdangerClass = $(document).find("span[class='text-danger']");

    function checkFields() {
        $(checkSpan_withdangerClass.parent('label').next(`input[type="text"], input[type="number"], select, textarea, .note-editable p`)).each(function () {
            const $input = $(this);
            let isEmpty = false;

            if ($input.val() === "" || ($input.is('select') && $input.val() === null)) {
                isEmpty = true;
            } else if ($input.hasClass('select2-hidden-accessible')) {
                const $select2Container = $input.next('.select2-container');
                if ($select2Container.length === 0) {
                    return;
                }
                if ($select2Container.find('.select2-selection__rendered').text() === "") {
                    isEmpty = true;
                }
            }

            if (isEmpty) {
                $input.addClass('red-bg');
            } else {
                $input.removeClass('red-bg');
            }
        });
    }


    checkFields();

    $('input[type="text"], input[type="number"], select').on('input change blur', checkFields);

    $(document).on('select2:open', function (e) {
        const $select2 = $(e.target);
        $select2.on('select2:select select2:unselect select2:close', checkFields);
    });


})


