# Run


```
export DB_DATABASE=master
export DB_PASSWORD=XXXXXXXX
export DB_HOSTNAME=192.168.0.10
export DB_USERNAME=SA
make up 
```

and visit http://localhost:8080/mssql

## Raw query locally

```
SELECT @@Version as SQL_VERSION;

Microsoft SQL Server vNext (CTP2.0) - 14.0.500.272 (X64) 
	Apr 13 2017 11:44:40 
	Copyright (C) 2017 Microsoft Corporation. All rights reserved.
	Developer Edition (64-bit) on Linux (Ubuntu 16.04.2 LTS)
```