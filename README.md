# 450 words

## A little backstory: 750 words

A few years ago, I stumbled upon [750words](http://750words.com/), a great website with a simple idea. With their own words:

> It's about learning a new habit: Writing. Every. Day.

In fact, this site is just a numeric support for what is called _morning pages_: 

> Morning Pages are three pages of longhand, stream of consciousness writing,
done first thing in the morning. *There is no wrong way to do Morning Pages*–
they are not high art. They are not even “writing.” They are about
anything and everything that crosses your mind– and they are for your eyes
only. 
> Morning Pages provoke, clarify, comfort, cajole, prioritize and
synchronize the day at hand. Do not over-think Morning Pages: just put
three pages of anything on the page...and then do three more pages tomorrow.

So, As I said, I stumbled upon the site and instantly fell in love with it: writing 750 words a day is difficult at first, but the more you practice the easier it gets. My only sorrow: while _they do not tell you this at first_, you only get one month free. Afterwards, you need to pay __$5/month__ to keep writing. It might be cheap, but I really didn't like the surprise... So I decided to make my own !

## What 450words is all about

__450words__ is a local (and stripped) version of 750words. It allows you to write your morning pages easily from your browser. You can either deploy it on a server or on your local machine, so you have full control over your data.

Also, you noticed that I downgraded from 750 to 450 words. Why ? Because 3 pages are hard to achieve when you don't have much going on in your life... I often had a hard time getting the green dot, so I decided to target a smaller amount of words. Of course, you are free to go above this limit if you want.

## Screenshots

Current day page, ready for your thoughts: 

![screenshot 1: index](http://i.imgur.com/md9z7Rf.png)

Browsing history:

![screenshot 2: history](http://i.imgur.com/Rju5szc.png)

## Requirements 

The only thing you need is an Apache-PHP-MySQL instance or Docker, some storage space and a bit of motivation to get started.

## Installation

__Using a local Apache/PHP/MySQL setup__

Here, you need an Apache server with PHP and MySQL enabled and a MySQL database. Google is your friend if you don't already have it.

1. clone this repository and copy the `src` directory in `/var/www` (or your local apache folder).
2. edit `apache2/site-enabled/default-000.conf` and add a virtual host entry (see `docker/apache-config.conf` for an example)
3. create a `words` database in your MySQL instance. You can use the script in `docker/db-init-words-db.sql`:
        
        $ mysql
        > create database words;
        > exit
        
        $ mysqldump -u user -p words < docker/db-init/words-db.sql

4. edit `src/utls/db.php` and replace the lines 

        $dbhost = getenv('MYSQL_URL');
        $dbuser = getenv('MYSQL_USERNAME');
        $dbpass = getenv('MYSQL_PASSWORD');

    with informations about your mysql instance. For example:

        $dbhost = 'localhost';
        $dbuser = '450words-user';
        $dbpass = 'secure-password';


5. restart your apache server. You should be all set !

__Using Docker__

1. `cd` into `docker`: `cd docker`
2. create the Apache image: `./build-image.sh` or simply `docker build -t derlin/apache-php .`
3. launch 450words: `docker-compose up -d`
4. navigate to http://localhost and ensure it is working
5. stop the container: `docker-compose stop`
6. open `docker-compose.yaml` and comment the line below. If you don't do it, the mysql database will be recreated everytime you restart the container and your awesome writing won't persist:
        
         - ./db-init:/docker-entrypoint-initdb.d

To create a user, navigate to http://localhost/signup.php. You are all set !

Note that the mysql database is persisted in `docker/db-data` (a _volume_, see the `docker-compose.yaml` file), so you won't lose your data when you stop docker (as long as you commented the line as stated in point 6!).  

_Dump the content of the database_: to backup your database content using Docker, first ensure the mysql container is running (`
docker-compose up`
), then type:

        docker exec -it docker_db_1 mysqldump -u docker --password=docker words > words.sql 
        
--------------------- 
_Lucy Linder, summer 2015_
