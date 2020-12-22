CREATE TABLE Survey
(
	id		int auto_increment, #internal identifier, used in query params to open a particular survey
	title		varchar(30) not null unique, #unique name for survey/quiz
	description	TEXT, #text about survey/quiz
	visibility	int, #numerical flag that determines who can see survey
			     #(0 for Draft, only author can see)
			     #(1 for Private, only people with direct link access can see)
			     #(2 for Public, anyone can see it and it shows in searches)
	created		TIMESTAMP default CURRENT_TIMESTAMP, #timestamp on when the survey was first created
	modified	TIMESTAMP default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP, #timestamp of when it was edited
	user_id	 	int, #A FK to the user who created this survey/quiz, this is the author
	primary key (id),
	FOREIGN KEY (user_id) REFERENCES Users (id)
)
