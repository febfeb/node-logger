# Node Logger
A Node.js based simple log application. 

NodeLogger use the advantages of node.js and socket.io. It also can be used to trace/debug our application without waiting out application to end/terminate.

This tool is very useful when your application doesn't allow you to echo-ing a single character, or you have to keep your application run without output interference.


To start this service, you can type this in terminal :

```
$ yarn
$ node index.js
```

Then, please open your browser at ```http://localhost:1337```.

You can use my simple php class inside ```php-sample``` folder. Code usage :

```
NodeLogger::sendLog($yourVariable);
```

Code usage inside php loop :

```
for($i=0;$i<100;$i++){
	echo "\$i value : ".$i;
	NodeLogger::sendLog($i);
}
```