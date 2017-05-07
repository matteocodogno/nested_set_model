# Nested Roles

Every enterprise software has a built-in support for organizational charts, in order to represent hierarchies and roles 
inside a company. A common and convenient way to persist tree-like structures in relational databases is the 
"Nested Set" model (see https://en.wikipedia.org/wiki/Nested_set_model  for further details).

## Environment

### Mysql - docker

```
docker run --name db_nested_role \
    -e MYSQL_ROOT_PASSWORD=secret \
    -e MYSQL_DATABASE=nested \
    -e MYSQL_USER=nested \
    -e MYSQL_PASSWORD=secret \
    -p 6603:3306 \
    -v /tmp/mysql:/var/lib/mysql \
    -v /tmp:/tmp \
    -d mysql/mysql-server
```

*Create Tables and seed db*

Copy the `data.sql` and `table.sql` files to the shared /tmp folder.

`docker exec db_nested_role /bin/sh -c 'mysql nested -u root -psecret < /tmp/tables.sql'`

`docker exec db_nested_role /bin/sh -c 'mysql nested -u root -psecret < /tmp/data.sql'`


