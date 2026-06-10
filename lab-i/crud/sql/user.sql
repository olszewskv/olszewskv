create table if not exists user
(
    id      integer not null
        constraint user_pk
            primary key autoincrement,
    name text not null,
    email text not null,
    age integer not null
);