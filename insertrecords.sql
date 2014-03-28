/*Insert sample patient,doctor, and radiologist*/
INSERT INTO persons VALUES(2, 'pat', 'pat', 'pat', 'pat', 'pat');
INSERT INTO users VALUES('pat','pat','p', 2, sysdate);

INSERT INTO persons VALUES(3, 'doc', 'doc', 'doc', 'doc', 'doc');
INSERT INTO users VALUES('doc', 'doc', 'd', 3, sysdate);

INSERT INTO persons VALUES(4, 'rad', 'rad', 'rad', 'rad', 'rad');
INSERT INTO users VALUES('rad', 'rad', 'r', 4, sysdate);


/*Insert sample records for testing */
INSERT INTO radiology_record VALUES 
(1,
2,
3,
4,
'cancer',
SYSDATE,
SYSDATE,
'fine',
'no tumours');

INSERT INTO radiology_record VALUES 
(2,
2,
3,
4,
'cancer2',
SYSDATE,
SYSDATE,
'fine2',
'no tumours2');
