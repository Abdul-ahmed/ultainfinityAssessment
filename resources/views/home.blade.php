<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Welcome Home</h1>



    
    <script async src="https://telegram.org/js/telegram-widget.js?22" data-telegram-login="ultainfinityAssessmentBot" data-size="large" data-onauth="onTelegramAuth(user)" data-request-access="write"></script>
    
    <script src="https://code.jquery.com/jquery-3.6.2.js"></script>
    <script type="text/javascript">
        function onTelegramAuth(user) {
            // alert('Logged in as ' + user.first_name + ' ' + user.last_name + ' (' + user.id + (user.username ? ', @' + user.username : '') + ')');
            $.ajax({
                type: "POST",
                url: "{{ route('login') }}",
                data: {
                    first_name: user.first_name,
                    last_name: user.last_name,
                    telegram_id: user.id,
                    username: user.username,
                },
                dataType: "json",
                success: function(response) {
                    alert(response.message);
                }
            });
        }
    </script>
    
</body>
</html>