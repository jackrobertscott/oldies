app.filter('timestampToISO', function() {
    return function(input) {
        input = new Date(input).toISOString();
        return input;
    };
});