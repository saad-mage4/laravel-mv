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
        console.log(slot);
        $.ajax({
            url: "/seller/add-sponsor-req",
            method: "get",
            data: { slot, slot },
            success: function (res) {
                console.log(res);
            },
        });
    });
});
