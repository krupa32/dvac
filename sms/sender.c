/*
 * sender.c
 * SMS Sender application
 *
 * This application is invoked by cron periodically, according to the
 * interval specified in sms.crontab. It checks the 'smsqueue' table in
 * the db, and for each row in the table
 * 	- sends sms
 * 	- removes the record if successfully sent
 */
#include <stdio.h>
#include <unistd.h>
#include <string.h>
#include <sys/file.h>
#include <errno.h>
#include <termios.h>
#include <mysql/mysql.h>

#define SERIAL_INTERVAL		(100*1000)	/* 100 ms */

static int lock_fd;

/* This function sends the given command and waits for a response
 * for a maximum of 'timeout' millisec.
 * If the response contains string pointer by 'expected', it returns 0.
 * Else returns -1
 */
int send_command(int fd, char *cmnd, char *expected, int timeout)
{
	int i, ret = -1, len = 0, nbytes_read = 0, iterations;
	char buf[256], tmp[256], cmndbuf[256];

	/* convert timeout to msec */
	timeout *= 1000;

	/* SMS supports only 160 chars max */
	memset(tmp, 0, 256);
	strncpy(tmp, cmnd, 160);
	sprintf(cmndbuf, "%s\x1A", tmp);

	//printf("sending command [%d bytes]:\n%s\n", strlen(cmndbuf), cmndbuf);
	write(fd, cmndbuf, strlen(cmndbuf));

	for (i = 1; i <= timeout; i += SERIAL_INTERVAL) {

		usleep(SERIAL_INTERVAL);

		nbytes_read = read(fd, &buf[len], 256);
		len += nbytes_read;
		buf[len] = 0;

		if (expected && strstr(buf, expected)) {
			//printf("recv %d bytes in %d ms:\n[%s]\n", len, i/1000, buf);
			ret = 0;
			break;
		}
	}

	if (expected == NULL) /* nothing expected. treated as success */
		ret = 0;

	if (ret != 0)
		printf("error sending command:[%s]\nrecv:[%s]\n", cmnd, buf);

	return ret;
}


int send_sms(int fd, char *phone, char *sms)
{
	char buf[1024];

	printf("sending sms:%s,%s\n", phone, sms);

	sprintf(buf, "AT+CMGS=\"%s\"\r", phone);
	if (send_command(fd, buf, ">", 500) < 0)
		return -1;

	if (send_command(fd, sms, "OK", 5000) < 0)
		return -1;

	return 0;
}

int process_sms(int fd)
{
	MYSQL *db;
	MYSQL_RES *res;
	MYSQL_ROW row;
	char q[100];
	int n_sms_sent = 0;

	if ((db = mysql_init(NULL)) == NULL) {
		printf("error initializing\n");
		goto out1;
	}

	if (mysql_real_connect(db, "localhost", "root", "123456", "dvac", 0, NULL, 0) == NULL) {
		printf("error connecting to db\n");
		goto out2;
	}

	strcpy(q, "select id,phone,sms from smsqueue");
	if (mysql_query(db, q) != 0) {
		printf("error querying\n");
		goto out2;
	}

	res = mysql_store_result(db);
	if (!res) {
		printf("error storing results\n");
		goto out2;
	}

	if (mysql_num_rows(res) == 0) {
		printf("No pending sms to be sent\n");
		goto out2;
	}

	while (row = mysql_fetch_row(res)) {

		if (send_sms(fd, row[1], row[2]) < 0) {
			/* Some problem in sending sms. It could be due to
			 * invalid mobile number, busy network, anything.
			 * So we skip to the next sms in Q. The failed sms
			 * continues to sit in the DB.
			 */
			continue;
		}

		sprintf(q, "delete from smsqueue where id=%s", row[0]);
		if (mysql_query(db, q) != 0)
			printf("error removing record from smsqueue\n");

		n_sms_sent++;
	}

	mysql_free_result(res);

out2:
	mysql_close(db);
out1:
	return n_sms_sent;
}

int sms_sender_lock(void)
{
	printf("locking sender...\n");

	if ((lock_fd = open("/tmp/sender.lock", O_CREAT|O_WRONLY, 0666)) < 0) {
		printf("error opening/creating lock file\n");
		perror(NULL);
		return -1;
	}

	if (flock(lock_fd, LOCK_EX|LOCK_NB) < 0) {
		printf("error locking\n");
		close(lock_fd);
		return -1;
	}

	return 0;
}

void sms_sender_unlock(void)
{
	printf("unlocking sender...\n");
	flock(lock_fd, LOCK_UN);
	close(lock_fd);
}

int init_modem(int fd)
{
	struct termios opt;

	printf("initializing modem\n");

	/* set baud rate */
	tcgetattr(fd, &opt);
	cfsetispeed(&opt, B9600);
	cfmakeraw(&opt);
	tcsetattr(fd, TCSANOW, &opt);

	/* send a 'A' to initialize communication */
	if (send_command(fd, "A\r", NULL, 500) < 0)
		return -1;

	/* disable modem echo */
	if (send_command(fd, "ATE0\r", "OK", 500) < 0)
		return -1;

	/* send at command */
	if (send_command(fd, "AT\r", "OK", 500) < 0)
		return -1;

	/* enable text mode for sms */
	if (send_command(fd, "AT+CMGF=1\r", "OK", 500) < 0)
		return -1;

	return 0;
}

int main()
{
	int n, fd;

	/* lock the sender */
	if (sms_sender_lock() < 0) {
		/* another instance of the sender is already running.
		 * so safely exit.
		 */
		goto out1;
	}

	/* open serial port to communicate with modem */
	if ((fd = open("/dev/ttyUSB0", O_RDWR)) < 0) {
		printf("error opening serial port\n");
		goto out2;
	}

	/* initialize the modem */
	if (init_modem(fd) < 0) {
		printf("error initializing modem\n");
		goto out3;
	}

	while ((n = process_sms(fd)) != 0) {
		/* atleast one sms was sent.
		 * in the meantime, some other might have been added
		 * to the smsqueue. so do it again.
		 * if no sms was sent in the last run, then the queue
		 * is empty. so exit.
		 * note: cron will anyway call this again.
		 */
	}

out3:
	close(fd);

out2:
	/* unlock the sender */
	sms_sender_unlock();
out1:
	return 0;
}
