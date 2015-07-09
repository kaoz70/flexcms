/*global system, FB */

// This is called with the results from from FB.getLoginStatus().
function statusChangeCallback(response) {
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    console.log(response);
    if (response.status === 'connected') {

        // Logged into your app and Facebook.
        FB.api('/me', function (response) {
            $('.status').html('<div>Ud. est&aacute; como:</div><div><img src="http://graph.facebook.com/' + response.id + '/picture"></div><div>' + response.name + '</div>');
            $('.fb_nombres').val(response.first_name);
            $('.fb_apellidos').val(response.last_name);
            $('.fb_email').val(response.email);
            system.fb_login = true;
        });

    } else if (response.status === 'not_authorized') {
        // The person is logged into Facebook, but not your app.
        $('.status').html('Por favor autorice esta aplicaci&oacute;n para leer sus datos.');
    } else {
        // The person is not logged into Facebook, so we're not sure if
        // they are logged into this app or not.
        $('.status').html('No est&aacute; logueado en Facebook.');
    }
}

// This function is called when someone finishes with the Login
// Button.  See the onlogin handler attached to it in the sample
// code below.
function checkLoginState() {
    FB.getLoginStatus(function (response) {
        statusChangeCallback(response);
    });
}

function init_facebook_login(app_id){
    window.fbAsyncInit = function () {
        FB.init({
            appId: app_id,
            xfbml: true,
            cookie: true,
            version: 'v2.3'
        });
        checkLoginState();

        FB.Event.subscribe('auth.logout', function (response) {
            $('.status').html('No est&aacute; logueado en Facebook.');
            $('.fb_nombres').val('');
            $('.fb_apellidos').val('');
            $('.fb_email').val('');
            system.fb_login = false;
        });

    };
}

(function (d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {
        return;
    }
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
