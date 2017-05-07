# Nested Roles

Every enterprise software has a built-in support for organizational charts, in order to represent hierarchies and roles 
inside a company. A common and convenient way to persist tree-like structures in relational databases is the 
"Nested Set" model (see https://en.wikipedia.org/wiki/Nested_set_model  for further details).

## API

The endpoint is `<project_folder>/app/api.php?node_id=7&language=italian` can be called via HTTP GET.

These are the params:

- node_id: (integer, required) : the unique ID of the selected node.
- language: (enum, required) : language identifier. Possible values: "english", "italian".
- search_keyword: (string, optional) : a search term used to filter results. If provided, restricts the results to 
"all children nodes under  node_id  whose  nodeName  in the given  language contains  search_keyword  
(case insensitive)".
- page_num: page_num (integer, optional) : the 0-based identifier of the page to retrieve.
If not provided, defaults to “0”.
- page_size: (integer, optional) : the size of the page to retrieve, ranging from 0 to 1000. If not provided, defaults to “100”.

## Environment

* PHP *ver 5.6.30*
* Apache
* MySql

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


