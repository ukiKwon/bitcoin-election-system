# writer : uki408
# last_update : 2017-08-21

# contents
- This is a sample code to connect javaApp with server(with c language).

# How to test?
# In javaApp
- Make javaProject and java class named 'CConnSample.java' or paste this to java Project.You can name another but modify sources.(Now it has a name 'client.java' and its content has a class named 'client'). 

- A Project main needs a argument, 'hostname' and 'port'.After that, you just declare it like 'Client sample = new Client();'. 

# In C server
- just copy and paste server.c into your directory. Then compile it!!!
	gcc -o server server.c

# Error
# Connection fail?
- Starting connection, you have to check it by 'netstat -antcp' or else. It can be happend Firewalls or port are blocked. So There needs to beactions like port forwarding or firewalls permission.


