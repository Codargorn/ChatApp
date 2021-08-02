create table notices.notes
(
    id         int(11) unsigned auto_increment
        primary key,
    user_id    int(11) unsigned not null,
    offset     int(11) unsigned not null,
    title      varchar(100)     not null,
    text       longtext         null,
    created_at datetime         not null,
    constraint notices_user_id_offset_uindex
        unique (user_id, offset)
);

create index notices_user_id_index
    on notices.notes (user_id);

create table notices.users
(
    id       int auto_increment
        primary key,
    email    varchar(100) not null,
    password varchar(255) not null
);

