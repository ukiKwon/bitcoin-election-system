
/*c opyright (c) 2000 by Yoon Kyung Koo.  All Rights Reserved */
/***
   NAME
     test_server
   PURPOSE
     a test server program which shows an example of communicating between C and Java apps.
   NOTES
     
   HISTORY
     yoonforh 2000-02-12 13:38:23 : Created.
***/

#include <stdio.h>
#include <stdlib.h>
#include <sys/types.h>
#include <string.h>
#include <sys/time.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <arpa/inet.h>
#include <unistd.h>
#include <errno.h>

#define DEFAULT_PORT 5000 

typedef int BOOL;
#define FALSE 0
#define TRUE 1

typedef struct {
    char bytes[100];
    char c;
    BOOL val;
    int str_length; /* length of string */
    char * string; /* string bytes */
} Object;

/*
 * read exactly n bytes
 */
int readn(fd, ptr, size)
        int fd;
        char *ptr;
        int size;
{
        int nread=0, nleft=size;

        while (nleft > 0) {
                nread=read(fd, ptr, nleft);
                fprintf(stderr, "read %d/%d bytes\n", nread, nleft);
                if (nread < 0)
                        return nread;
                else if (nread==0) /* no more data to read */
                        return (size-nleft);
                nleft -= nread;
                ptr += nread;
        }
        return (size - nleft);
}

/*
 * write exactly n bytes 
 */
int writen(fd, ptr, length)
        int fd;
        char *ptr;
        int length;
{
        int nleft, nwritten;

        nleft = length;
        while (nleft > 0) {
                nwritten = write(fd, ptr, nleft);
                if (nwritten <= 0)
                        return nwritten;
                nleft -= nwritten;
                ptr += nwritten;
        }
        return (length - nleft);
}

/*
 * make a test data
 */
void fill_in_test_data(Object * obj)
{
        int i;
        for (i = 100; i > 0; i--)
                obj->bytes[100 - i] = i;
        obj->c = 'Z';
        obj->val = TRUE;
        obj->string = "�~E~L�~J��~J� string�~^~E�~K~H�~K�.";
        obj->str_length = strlen(obj->string);
}

/*
 * print Object data
 */
void print_data(Object * obj)
{
        int i;
    printf("bytes  :\n");
    for (i = 0; i < 100; i++) {
                if (i%10 == 0)
                        printf("\n");
                printf("%d ", (int) obj->bytes[i]);
    }
        printf("\n");
    printf("c      : %c\n", obj->c);
    printf("val    : %s\n", (obj->val ? "true" : "false"));
    /*    printf("len    : %d\n", obj->str_length); */
    printf("string : %s\n", obj->string);

}

/*
 * handling client (socket session)
 */
int work(sd)
        int sd;
{
    Object object, object2;
        int i, len, str_len, nread, nwritten;

    //printf("address of object : 0x%x\n", &object);
    //printf("offset to bytes   : %d\n", (int) &object.bytes - (int) &object);
    //printf("offset to c       : %d\n", (int) &object.c - (int) &object);
    //printf("offset to val     : %d\n", (int) &object.val - (int) &object);
    //printf("offset to str_l   : %d\n", (int) &object.str_length - (int) &object);
    //printf("offset to string  : %d\n", (int) &object.string - (int) &object);
    //printf("whole size of the Object class : %d\n", sizeof(Object));

        /*
         * first, read an Object data from socket client
         */
        memset(&object, 0, sizeof(Object));

    /* (size of the whole object) - (size of Object::string) */
    len = sizeof(Object) - sizeof (char *);

    nread = readn(sd, (char *)&object, len);
    if (nread < len) {
                fprintf(stderr, "read failed. result = %d/%d\n", nread, len);
                if (nread < 0)
                        fprintf(stderr, "read error : %s\n", strerror(errno));
                return -1;
    }

    /* always fix byte order */
    object.val = ntohl(object.val);
    object.str_length = ntohl(object.str_length);

    len = object.str_length;
    object.string = (char *) malloc(len + 1);

    nread = readn(sd, object.string, len);
    if (nread < len) {
                fprintf(stderr, "read failed. result = %d/%d\n", nread, len);
                if (nread < 0)
                        fprintf(stderr, "read error : %s\n", strerror(errno));
                return -1;
    }

    object.string[object.str_length] = 0; /* append null */

        print_data(&object);

        free(object.string);
        printf("-------- read ends --------\n");

        /*
         * now, write an Object data to socket client
         */
        memset(&object2, 0, sizeof(Object));

        /* first compose a test Object data */
        fill_in_test_data(&object2);

    /* (size of the whole object) - (size of Object::string) */
    len = sizeof(Object) - sizeof (char *);
        str_len = object2.str_length;

        print_data(&object2);

    /* always fix byte order */
    object2.val = htonl(object2.val);
    object2.str_length = htonl(object2.str_length);

    nwritten = writen(sd, (char *) &object2, len);
    if (nwritten < len) {
                fprintf(stderr, "write failed. result = %d/%d\n", nwritten, len);
                if (nwritten < 0)
                        fprintf(stderr, "write error : %s\n", strerror(errno));
                return -1;
    }

    nwritten = writen(sd, (char *) object2.string, str_len);
    if (nwritten < str_len) {
                fprintf(stderr, "write failed. result = %d/%d\n", nwritten, str_len);
                if (nwritten < 0)
                        fprintf(stderr, "write error : %s\n", strerror(errno));
                return -1;
    }

        printf("-------- write ends --------\n");
        return 0;
}
int main(int argc, char** argv)
{
        int sd, client_sd;  /* socket descriptor */
        struct sockaddr_in addr, client_addr; /* internet address */
        struct in_addr client_info;
        char buffer[256]; /* general purpose buffer */
        int client_addr_len, child_pid;
        int connected = 0;
        int port = DEFAULT_PORT;
        int opt;

        if (argc > 1) {
                port = atoi(argv[1]);
                if (port <= 0)
                        port = DEFAULT_PORT;
        }

        sd = socket(AF_INET, SOCK_STREAM, 0);
        if (sd < 0)
        {
                perror("socket");
                exit(1);
        }

        opt = 1;
        if (setsockopt(sd, SOL_SOCKET, SO_REUSEADDR, (char *)&opt, sizeof(int))<0)
                fprintf(stderr, "setsockopt(): errorno=%d, message=%s\n", errno, strerror(errno));

        addr.sin_family=AF_INET;
        addr.sin_port = htons(port);
        addr.sin_addr.s_addr = htonl(INADDR_ANY);

        if (bind(sd, (const struct sockaddr *) &addr, sizeof(addr)) != 0)
        {
                perror("bind");
                exit(1);
        }

        if (listen(sd, 5)!=0)
        {
                perror("listen");
                exit(1);
        }

        for (; ;) {
                int result, i;
                fd_set read_set;

                FD_ZERO(&read_set);
                FD_SET(sd, &read_set);
                if (connected) {
                        printf("ADD client_sd[%d] to fd_set\n", client_sd);
                        FD_SET(client_sd, &read_set);
                }

                result=select (10, &read_set, NULL, NULL, NULL);
                printf("result=%d, sd=%d\n", result,sd );
                if (FD_ISSET(sd,&read_set) && (!connected)) {
                        client_addr_len=sizeof(client_addr);

                        if ((client_sd=accept(sd, (struct sockaddr *)&client_addr, &client_addr_len)) < 0)
                        {
                                perror("accept");
                                exit(1);
                        }
                        printf("ACCEPT client_sd=%d\n", client_sd);

                        memcpy(&client_info, &client_addr.sin_addr.s_addr, 4);

                        fprintf(stdout, "accept OK : client IP addr = %s, port = %d\n",
                                        inet_ntoa(client_info), ntohs(client_addr.sin_port));
                        connected = 1;
                }
                else if (FD_ISSET(client_sd,&read_set)) {
                        if (work(client_sd) < 0) {
                                close(client_sd);
                                connected = 0;
                        }
                        continue;
                }

#if 0
                if ( (child_pid = fork() )<0 )
                {
                        perror("fork");
                        exit(1);
                }
                else if (child_pid == 0) /* child process */
                {
                        if (close(sd)!=0)  /* close original socket */
                        {
                                perror("close");
                                exit(1);
                        }
                        work(client_sd);  /* main work */
                        exit(0);
                }       

                if (close(client_sd)!=0)  /* parent process */
                {
                        perror("close");
                        exit(1);
                }
#endif
        } /* for ends */
}
                                
