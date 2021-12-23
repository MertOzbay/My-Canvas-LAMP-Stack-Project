CREATE DATABASE my_canvas;

USE my_canvas;

CREATE TABLE student
(
    studentID CHAR(10) NOT NULL,
    loginID VARCHAR(20) NOT NULL,
    fName VARCHAR(20),
    lName VARCHAR(20),
    CONSTRAINT studentPK PRIMARY KEY(studentID)
);

CREATE TABLE course
(
    cName VARCHAR(50) NOT NULL,
    cNo VARCHAR(10) NOT NULL,
    semester VARCHAR(6) NOT NULL,
    year INT NOT NULL,
    instructorID CHAR(10) NOT NULL,
    CONSTRAINT coursePK PRIMARY KEY(cName, cNo, semester, year),
    CONSTRAINT courseFK FOREIGN KEY(instructorID) REFERENCES student(studentID)
);

CREATE TABLE assignment
(
    cName VARCHAR(50) NOT NULL,
    cNo VARCHAR(10) NOT NULL,
    semester VARCHAR(6) NOT NULL,
    year INT NOT NULL,
    dueDate DATE,
    assignment_text TEXT,
    totalPoints INT,
    CONSTRAINT assignmentPK PRIMARY KEY(cName, cNo, semester, year, dueDate),
    CONSTRAINT assignmentFK FOREIGN KEY(cName, cNo, semester, year) REFERENCES course(cName, cNo, semester, year)
);

CREATE TABLE takes
(
    studentID CHAR(10) NOT NULL,
    cName VARCHAR(50) NOT NULL,
    cNo VARCHAR(10) NOT NULL,
    semester VARCHAR(6) NOT NULL,
    year INT NOT NULL,
    letter VARCHAR(5),
    CONSTRAINT takesPK PRIMARY KEY(studentID, cName, cNo, semester, year),
    CONSTRAINT takesFK1 FOREIGN KEY(studentID) REFERENCES student(studentID),
    CONSTRAINT takesFK2 FOREIGN KEY(cName, cNo, semester, year) REFERENCES course(cName, cNo, semester, year)
);

CREATE TABLE tas
(
    TAID CHAR(10) NOT NULL,
    cName VARCHAR(50) NOT NULL,
    cNo VARCHAR(10) NOT NULL,
    semester VARCHAR(6) NOT NULL,
    year INT NOT NULL,
    CONSTRAINT tasPK PRIMARY KEY(TAID, cName, cNo, semester, year),
    CONSTRAINT tasFK1 FOREIGN KEY(TAID) REFERENCES student(studentID),
    CONSTRAINT tasFK2 FOREIGN KEY(cName, cNo, semester, year) REFERENCES course(cName, cNo, semester, year)
);

CREATE TABLE post
(
    postID INT NOT NULL AUTO_INCREMENT,
    title TEXT,
    post_text TEXT,
    postDate TIMESTAMP,
    authorID CHAR(10),
    cName VARCHAR(50) NOT NULL,
    cNo VARCHAR(10) NOT NULL,
    semester VARCHAR(6) NOT NULL,
    year INT NOT NULL,
    CONSTRAINT postPK PRIMARY KEY(postID),
    CONSTRAINT postFK1 FOREIGN KEY(authorID) REFERENCES student(studentID),
    CONSTRAINT postFK2 FOREIGN KEY(cName, cNo, semester, year) REFERENCES course(cName, cNo, semester, year)
);

CREATE TABLE replies
(
    postID INT NOT NULL,
    replyID INT NOT NULL,
    CONSTRAINT repliesPK PRIMARY KEY(postID, replyID),
    CONSTRAINT repliesFK1 FOREIGN KEY(postID) REFERENCES post(postID),
    CONSTRAINT repliesFK2 FOREIGN KEY(replyID) REFERENCES post(postID)
);

CREATE TABLE grades
(
  cName VARCHAR(50) NOT NULL,
  cNo VARCHAR(10) NOT NULL,
  semester VARCHAR(6) NOT NULL,
  year INT NOT NULL,
  dueDate DATE,
    studentID CHAR(10) NOT NULL,
    score INT,
    graderID CHAR(10) NOT NULL,
    CONSTRAINT gradesPK PRIMARY KEY(cName, cNo, semester, year, dueDate, studentID),
    CONSTRAINT gradesFK1 FOREIGN KEY(studentID) REFERENCES student(studentID),
    CONSTRAINT gradesFK2 FOREIGN KEY(graderID) REFERENCES student(studentID),
    CONSTRAINT gradesFK3 FOREIGN KEY(cName, cNo, semester, year, dueDate) REFERENCES assignment(cName, cNo, semester, year, dueDate)
);

CREATE TABLE tag
(
    postID INT NOT NULL,
    tag VARCHAR(50) NOT NULL,
    CONSTRAINT tagPK PRIMARY KEY(postID, tag),
    CONSTRAINT tagFK FOREIGN KEY(postID) REFERENCES post(postID)
);
