database-wrapper
================

Chained PDO database wrapper

Features
-----------------
Select statements
- - - - - - - - -
```bash
$obj->select()->from( 'table' );
// SELECT * FROM `table`
$obj->select( 'name' )->from( 'table' );
// SELECT `name` FROM `table`
$obj->select( 'id,name' )->from( 'table' );
// SELECT `id`,`name` FROM `table`
$obj->select( array( 'name' => 'fullname' ) )->from( 'table' );
// SELECT `name` AS `fullname` FROM `table`
$obj->select( 'max:id' )->from( 'table' );
// SELECT MAX(`id`) FROM `table`
$obj->select( array( 'min:id' => 'min' ) )->from( 'table' );
// SELECT MIN(`id`) AS `min` FROM `table`
$obj->select()->from( 'database.table' );
// SELECT * FROM `database`.`table`
$obj->select()->from( 'table1,table2' );
// SELECT * FROM `table1`,`table2`
$obj->select()->from( array( 'table' => 'users' ) );
// SELECT * FROM `table` AS `users`
$obj->select()->from( 'table' )->where( 'id', '=', 1 );
// SELECT * FROM `table` WHERE `id` = '1'
$obj->select()->from( 'table' )->where( 'id', '>=', 1 );
// SELECT * FROM `table` WHERE `id` >= '1'
$obj->select()->from( 'table' )->where( 'id', 'in', array( 1,2,3,4 ) );
// SELECT * FROM `table` WHERE `id` IN ('1','2','3','4')
$obj->select()->from( 'table' )->where( 'id', 'between', array( 1,5 ) );
// SELECT * FROM `table` WHERE `id` BETWEEN '1' AND '5'
$obj->select()->from( 'table' )->where( 'id', 'is not', null );
// SELECT * FROM `table` WHERE `id` IS NOT NULL
$obj->select()->from( 'table' )->where( 'name', 'like', '%foo%' );
// SELECT * FROM `table` WHERE `name` LIKE '%foo%'
```
