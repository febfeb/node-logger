var app = require('express')();
var bodyParser = require("body-parser");
var http = require('http').Server(app);
var io = require('socket.io')(http);
var moment = require('moment');
var port = 1337;

app.use(bodyParser.json({ limit: '50mb' }));
app.use(bodyParser.urlencoded({ limit: '50mb', extended: false }));

app.get('/', function (req, res) {
    res.sendFile(__dirname + '/files/index.html');
});
app.get('/jquery.js', function (req, res) {
    res.sendFile(__dirname + '/files/jquery.js');
});

app.post('/ws', function (req, res) {
    var message = req.body.message;
    emit(message);
    res.send("OK");
});

http.listen(port, function () {
    console.log('listening on *:' + port);
});

io.on('connection', function (socket) {
    console.log('a user connected');

    socket.on('chat message', function (msg) {
        emit(msg);
    });

    socket.on('disconnect', function () {
        console.log('user disconnected');
    });
});

function emit(message) {
    var output = {
        waktu: moment().format("MMMM Do YYYY, h:mm:ss a"),
        message: message
    };
    io.emit('chat message', output);
}