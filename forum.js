$('#new_post').prop("disabled", true);
// textareaでキーアップイベント発生→テキストのレングス0以上なら非活性解除
$('#text').on("keyup", function() {
    if ($(this).val().length > 0) {
        $('#new_post').prop("disabled", false);
    } else {
        $('#new_post').prop("disabled", true);
    }
});
