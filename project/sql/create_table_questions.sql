CREATE TABLE IF NOT EXISTS Questions
(
    id        int auto_increment,
    question  varchar(120) not null,

    modified  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP on update current_timestamp,
    created   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ,
    survey_id int,
    primary key (id),
    FOREIGN KEY (survey_id) REFERENCES Surveys (id)
)
