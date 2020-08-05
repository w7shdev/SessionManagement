# SessionManagement
Using web sockets to mange session for Sabre REST API 


### Note : 
This is an experimental idea of handling the session using web sockets as a real time communication medium to the server. 

## usage
1. clone the repo or downloaded 
2. run ```composer install``` 
3. run ```php bin/Server.php```

open the site in your browser. 

### SQL sample

__databate table is similar to this__ 
```SQL

CREATE TABLE `session` (
  `session_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `session_token` text NOT NULL,
  `expire_in` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB D
```
*You can use this query to create a view* 

```SQL 
SELECT
    `session_id` AS `session_id`,
    `session_token` AS `session_token`,
    `expire_in` AS `expire_in`,
    `created_at` AS `created_at`
FROM
    `session`
WHERE
    (DATEDIFF(SYSDATE(), created_at) < 7 )
```