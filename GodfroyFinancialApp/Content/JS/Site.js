$(document).ready(() => {
    $(".deleteItemButton").click(function () {
        var result = confirm("Are you sure you want to delete this item? This action is irreversible");
        return result;
    });
});