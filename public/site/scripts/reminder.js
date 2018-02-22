$(document).ready(function() {
    $('[name="telSender"]').on('click', function(event) {

        var foo = {
                "snumele": "Introduceti numele!",
                "stelefon": "Introduceti telefonul!",
            },
            error = false;

        $.each(foo, function(i, v) {
            console.log(i);
            var element = $("[name='" + i + "']"),
                value = element.val();

            if (value.length == 0 || (i == 'stelefon' && !validatePhone(value))) {

                $('[name="' + i + '"]').css({ 'border': '1px solid #ff0000' });
                error = true;
            }
        });

        if (error) {
            event.preventDefault();
            $('#status').show();
        }
    });

    function validatePhone(txtPhone) {
        var filter = /^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/;
        if (filter.test(txtPhone)) {
            return true;
        } else {
            return false;
        }
    }

    $('[name="reminderSender"]').on('click', function(event) {

        var foo = {
                "tipul": "Introduceti tipul|",
                "dataexp": "Introduceti data!",
                "remail": "Introduceti adresa email!",
                "rtelefon": "Introduceti telefonul!",
            },
            error = false;

        $.each(foo, function(i, v) {
            console.log(i);
            var element = $("[name='" + i + "']"),
                value = element.val();

            if (value.length == 0 || (i == 'remail' && !validateEmail(value)) || (i == 'rtelefon' && !validatePhone(value))) {

                $('[name="' + i + '"]').css({ 'border': '1px solid #ff0000' });
                error = true;
            }
        });

        if (error) {
            event.preventDefault();
            $('#status2').show();
        }
    });

    function validateEmail(email) {
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

});
