#Kyle Ross and Steven La
#CMPUT 391 project

#Creates and table and initial data for project

#Is executed by running command: make install u="username" p="password

install: 
	cd database; \
	sed -i '3s/.*/"$$"conn = oci_connect("$(u)","$(p)");/' dbconnect.php; \
	sed -i '3s/"$$"/$$/' dbconnect.php; \
	cd ..; \
	sqlplus $(u)/$(p) @install.sql;
