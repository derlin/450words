create table words(
    userid varchar(100) not null,
    day date NOT NULL,
    word text,

    constraint words_pk primary key (userid, day),
    constraint words_fk foreign key (userid) references user(userid) on delete cascade
);
