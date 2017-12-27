$(document).ready(function () {
    setInterval(
        function () {
            $.ajax({
                url: TYPO3.settings.PrTeamspeak.ajaxUrl,
                success: function (result) {
                    $('.teamspeak-channel-list').html(result);
                }
            });
        },
        (TYPO3.settings.PrTeamspeak.ajaxRefreshTime * 1000)
    );
});