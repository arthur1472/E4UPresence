{{--<script async src="https://telegram.org/js/telegram-widget.js?7" data-telegram-login="E4UPresenceBot" data-size="large" data-auth-url="http://e4upresence.test/callback/telegram" data-request-access="write"></script>--}}
@section('main')
<script async src="https://telegram.org/js/telegram-widget.js?7" data-telegram-login="E4UPresenceBot" data-size="large" data-onauth="onTelegramAuth(user)" data-request-access="write"></script>
<script type="text/javascript">
    function onTelegramAuth(user) {
        alert('Logged in as ' + user.first_name + ' ' + user.last_name + ' (' + user.id + (user.username ? ', @' + user.username : '') + ')');
    }
</script>
    @endsection
