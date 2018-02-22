jQuery(function($) {
    $('[name="contactSender"]').on('click', function(event) {

        var foo = {
                "name": "Please enter Name!",
                "email": "Please enter Email!",
                "message": "Please enter Message!"
            },
            error = false;

        $.each(foo, function(i, v) {
            console.log(i);
            var element = $("[name='" + i + "']"),
                value = element.val();

            if (value.length == 0 || (i == 'email' && !validateEmail(value))) {

                $('[name="' + i + '"]').css({ 'border': '1px solid #ff0000' });
                error = true;
            }
        });

        if (error) {
            event.preventDefault();
            $('#status').show();
        }
    });

    function validateEmail(email) {
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }
});
