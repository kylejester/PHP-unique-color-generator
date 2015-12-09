window.onload = function () {
    var clipboard = new Clipboard('.copy-target');
    clipboard.on('error', function(e) {
        showTooltip(e.trigger, fallbackMessage(e.action));
    });
};
