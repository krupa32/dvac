all: smssender

smssender: sender.c
	gcc sender.c -o smssender -lmysqlclient

clean:
	rm -f smssender
