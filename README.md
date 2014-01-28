database-wrapper
================

Chained PDO database wrapper

Features
-----------------
Select statements
- - - - - - - - -
```bash
// selectors
$obj->select()->from( 'table' );                                                // SELECT * FROM `table`
$obj->select( 'name' )->from( 'table' );
$obj->select( 'id,name' )->from( 'table' );
$obj->select( array( 'name' => 'fullname' ) )->from( 'table' );
$obj->select( 'max:id' )->from( 'table' );
$obj->select( array( 'min:id' => 'min' ) )->from( 'table' );
// from
$obj->select()->from( 'database.table' );
$obj->select()->from( 'table1,table2' );
$obj->select()->from( array( 'table' => 'users' ) );
// where
$obj->select()->from( 'table' )->where( 'id', '=', 1 );
$obj->select()->from( 'table' )->where( 'id', '>=', 1 );
$obj->select()->from( 'table' )->where( 'id', 'in', array( 1,2,3,4 ) );
$obj->select()->from( 'table' )->where( 'id', 'between', array( 1,5 ) );
$obj->select()->from( 'table' )->where( 'id', 'is not', null );
$obj->select()->from( 'table' )->where( 'name', 'like', '%foo%' );
```