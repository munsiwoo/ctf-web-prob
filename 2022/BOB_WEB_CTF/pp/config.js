function appInit(){
    _get = parseParams(location.search.slice(1));
    htmlSanitizer = new goog.html.sanitizer.HtmlSanitizer();
}

function parseParams(queryString, decodeKeys, decodeValues){
    if (decodeKeys == null) decodeKeys = true;
    if (decodeValues == null) decodeValues = true;

    var vars = queryString.split(/[&;]/);
    var obj = {};
    if (!vars.length) return obj;

    vars.forEach(function(val){
        var index = val.indexOf('=') + 1;
        var value = index ? val.substr(index) : '';
        var keys = index ? val.substr(0, index - 1).match(/([^\]\[]+|(\B)(?=\]))/g) : [val];

        if (!keys) return;
        if (decodeValues) value = decodeURIComponent(value);
        keys.forEach(function(key, i){
            if (decodeKeys) key = decodeURIComponent(key);
            var current = obj[key];

            if (i < keys.length - 1) obj = obj[key] = current || {};
            else if (typeof(current) == 'array') current.push(value);
            else obj[key] = current != null ? [current, value] : value;
        });
    });
    
    return obj;
}