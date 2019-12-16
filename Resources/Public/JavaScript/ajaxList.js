/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
var teamSpeakMonitors;

(function() {
    teamSpeakMonitors = document.querySelectorAll('.teamspeak-channel-list');
    refreshMonitors();
    setInterval(refreshMonitors, 10000);
}());

function refreshMonitors() {
    for (var i = 0; i < teamSpeakMonitors.length; i++) {
        var xmlhttp = new XMLHttpRequest();
        var monitor = teamSpeakMonitors[i];
        xmlhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                writeMonitor(this, monitor);
            } else if (this.status === 500) {
                monitor.innerHTML = TYPO3.lang['frontend-exception'];
            }
        };
        xmlhttp.open('GET', '/index.php?eID=pr_teamspeak_list&ce=' + teamSpeakMonitors[0].dataset['ce'], true);
        xmlhttp.send();
    }
}

function writeMonitor(response, monitor) {
    var responseObject = JSON.parse(response.responseText);
    if (monitor.innerHTML !== responseObject.html) {
        // only refresh if HTML has changed
        monitor.innerHTML = responseObject.html;
    }
}
