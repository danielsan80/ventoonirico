define([], function(){
    if (window.location.pathname.substring(0, 12) == '/app_dev.php') {
        return 'app_dev.php';
    }
    return '';
});
