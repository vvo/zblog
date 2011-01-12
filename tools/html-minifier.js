require('./html-minifier/src/htmlparser.js');
//console.log(process.openStdin());
var     stdin = process.openStdin(),
        minifier = require('./html-minifier/src/htmlminifier.js'),
        options = {
//                removeComments: true,
//                removeCommentsFromCDATA: true,
                removeCDATASectionsFromCDATA: true,
				lint: false,
                //collapseWhitespace: true,
                collapseBooleanAttributes: true,
                removeAttributeQuotes: true,
                removeRedundantAttributes: true,
                removeEmptyAttributes: true,
                removeOptionalTags: true,
                removeScriptTypeAttributes: true,
                removeStyleLinkTypeAttributes: true
        },
        toMinify = '';


stdin.on('data', function (chunk) {
        toMinify+=chunk;
});

stdin.on('end', function() {
        process.stdout.write(minifier.minify(toMinify, options));
});

stdin.setEncoding('utf8');
