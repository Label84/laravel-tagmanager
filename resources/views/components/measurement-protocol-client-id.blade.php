@if($isEnabled)
<!-- Google Tag Manager Client ID -->
<script>
function getGoogleAnalyticsClientId() {
    var cookie = {};
    document.cookie.split(';').forEach(function(el) {
        var splitCookie = el.split('=');
        var key = splitCookie[0].trim();
        var value = splitCookie[1];
        cookie[key] = value;
    });

    storeGoogleAnalyticsClientId(cookie["_ga"].substring(6));
}

function storeGoogleAnalyticsClientId(clientId) {
    var data = new FormData();
    data.append('client_id', clientId);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/tagmanager/store-measurement-protocol-client-id', true);
    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
    xhr.send(data);
}
</script>

@if(session(config('tagmanager.measurement_protocol_client_id_session_key')) === null)
<script>
getGoogleAnalyticsClientId();
</script>
@endif
<!-- End Google Tag Manager Client ID -->
@endif
