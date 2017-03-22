appverse-html5-notifications
=======================

## More Information

* **About this project**: <http://appverse.github.com/appverse-web>
* **About licenses & groups**: <http://appverse.github.com>
* **About The Appverse Project**: <http://appverse.org>

### Download

[GitHub page](https://appverse.gftlabs.com/git/scm/appverse/appverse-html5-notifications.git)

## Running (For using Appverse notifications in your Appverse HTML5 project)

#### Inside of your app:
* Run `bower install appverse.notifications`
* Add the following to your index.html
```html
<!-- build:js scripts/scripts.js -->
  <script src="bower_components/classie/classie.js"></script>
  <script src="bower_components/appverse.notifications/dist/notification-fx.js"></script> <!-- by www.codrops.com -->
  <script src="bower_components/appverse.notifications/dist/appverse.notifications.js"></script>
<!-- endbuild -->
```

```scss
<!-- build:css styles/vendor.css -->
  <link rel="stylesheet" type="text/css" href="bower_components/appverse.notifications/dist/notification-bar.css">
<!-- endbuild -->
```
## Credits

Appverse Notifications is a helper component to use easily the notificationFx.js librariy by codrops (<http://www.codrops.com>)  and they produced a [Notification Styles Inspiration](https://github.com/codrops/NotificationStyles) sample application you should check.

## License

    Copyright (c) 2012 GFT Appverse, S.L., Sociedad Unipersonal.

     This Source  Code Form  is subject to the  terms of  the Appverse Public License
     Version 2.0  ("APL v2.0").  If a copy of  the APL  was not  distributed with this
     file, You can obtain one at <http://appverse.org/legal/appverse-license/>.

     Redistribution and use in  source and binary forms, with or without modification,
     are permitted provided that the  conditions  of the  AppVerse Public License v2.0
     are met.

     THIS SOFTWARE IS PROVIDED BY THE  COPYRIGHT HOLDERS  AND CONTRIBUTORS "AS IS" AND
     ANY EXPRESS  OR IMPLIED WARRANTIES, INCLUDING, BUT  NOT LIMITED TO,   THE IMPLIED
     WARRANTIES   OF  MERCHANTABILITY   AND   FITNESS   FOR A PARTICULAR  PURPOSE  ARE
     DISCLAIMED. EXCEPT IN CASE OF WILLFUL MISCONDUCT OR GROSS NEGLIGENCE, IN NO EVENT
     SHALL THE  COPYRIGHT OWNER  OR  CONTRIBUTORS  BE LIABLE FOR ANY DIRECT, INDIRECT,
     INCIDENTAL,  SPECIAL,   EXEMPLARY,  OR CONSEQUENTIAL DAMAGES  (INCLUDING, BUT NOT
     LIMITED TO,  PROCUREMENT OF SUBSTITUTE  GOODS OR SERVICES;  LOSS OF USE, DATA, OR
     PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
     WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT(INCLUDING NEGLIGENCE OR OTHERWISE)
     ARISING  IN  ANY WAY OUT  OF THE USE  OF THIS  SOFTWARE,  EVEN  IF ADVISED OF THE
     POSSIBILITY OF SUCH DAMAGE.

Libraries in the src/vendors directory use their own license. In this case all are licensed under MIT license.

    Licensed under the MIT license. http://www.opensource.org/licenses/mit-license.php
