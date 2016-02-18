var http = require('http');

// from vendor/json-schema/JSON-Schema-Test-Suite/bin/jsonschema_suite remotes
var remotes = {
    "folder/folderInteger.json": {
        "type": "integer"
    }, 
    "integer.json": {
        "type": "integer"
    }, 
    "subSchemas.json": {
        "integer": {
            "type": "integer"
        }, 
        "refToInteger": {
            "$ref": "#/integer"
        }
    }
};

port = process.argv[2] ? process.argv[2] : 1234;


http.createServer( (req, res) => {

	path = req.url.substr(1);
	data = remotes[path];

	if (data === undefined) {
		res.writeHead(404, {'Content-Type': 'text/plain'});
		res.end('');
	} else {
		res.writeHead(200, {'Content-Type': 'application/json'});
		res.end(JSON.stringify(data));		
	}


}).listen(port);

console.log('Server running at http://127.0.0.1:' + port);