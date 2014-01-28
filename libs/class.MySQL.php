<?php
/**
 * @package     Database-Wrapper
 * @subpackage  Classes
 * @author      Codeschubser <blog@codeschubser.de>
 * @version     $Id: class.MySQL.php,v 0.0.1 28.01.2014 09:14:17 mitopp Exp $;
 * @see         https://github.com/codeschubser/database-wrapper
 * @license     The MIT License (MIT)
 *
 *              Copyright (c) 2014 codeschubser
 *
 *              Permission is hereby granted, free of charge, to any person obtaining a copy of
 *              this software and associated documentation files (the "Software"), to deal in
 *              the Software without restriction, including without limitation the rights to
 *              use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 *              the Software, and to permit persons to whom the Software is furnished to do so,
 *              subject to the following conditions:
 *
 *              The above copyright notice and this permission notice shall be included in all
 *              copies or substantial portions of the Software.
 *
 *              THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *              IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 *              FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 *              COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 *              IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 *              CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
class MySQL implements QueryBuilder, DatabaseActions
{
    /**
     * Connection state
     *
     * @access  protected
     * @since   0.0.1
     * @var     boolean
     */
    protected $_bConnected;
    /**
     * Statement string
     *
     * @access  protected
     * @since   0.0.1
     * @var     string
     */
    protected $_sStatement;

    /**
     * PDO object
     *
     * @access  private
     * @since   0.0.1
     * @var     object\PDO
     */
    private $__oPDO;

    /**
     * CONSTRUCTOR
     *
     * Build a database connection
     *
     * @access  public
     * @since   0.0.1
     * @param   string  $sHost
     * @param   string  $sUser
     * @param   string  $sPass
     * @param   string  $sDatabase
     * @param   string  $sCharset   default: utf8
     * @param   array   $aOptions   default: empty array
     * @return  void
     */
    public function __construct( $sHost, $sUser, $sPass, $sDatabase, $sCharset = 'utf8', array $aOptions = array() )
    {
        $this->_bConnected  = false;
        $this->_sStatement  = null;
        $this->__oPDO       = null;
        // try pdo connection
        try
        {
            // build dsn for connection
            $sDSN = 'mysql:host=' . $sHost . ';dbname=' . $sDatabase . ';charset=' . $sCharset;
            // parent
            $this->__oPDO = new PDO( $sDSN, $sUser, $sPass, $aOptions );
            $this->__oPDO->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $this->__oPDO->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC );
            // connected
            $this->_bConnected = true;
        }
        catch ( Exception $ex )
        {
            die( $ex->getMessage() );
        }
    }
    /**
     * DESTRUCTOR
     *
     * Destroy object and reset values
     *
     * @access  public
     * @since   0.0.1
     * @return  void
     */
    public function __destruct()
    {
        $this->__oPDO       = null;
        $this->_bConnected  = false;
        $this->_sStatement  = null;
    }

    /**
     * Concatenate from clause for select statement
     * and return the object for chaining.
     *
     * @final
     * @access  public
     * @since   0.0.1
     * @param   mixed   $sObject
     * @return  MySQL|false
     * @uses    __mask()    Masking columns
     */
    final public function from( $sObject )
    {
        if ( ! is_null( $this->_sStatement ) )
        {
            $this->_sStatement .= ' FROM ';
            // single or list of database objects
            if ( is_string( $sObject ) )
                $this->_sStatement .= $this->__mask( $sObject );
            // array of aliases
            else if ( is_array( $sObject ) )
            {
                foreach( $sObject AS $sColumn => $sAlias )
                    $this->_sStatement .= $this->__mask( $sColumn ) . ' AS ' . $this->__mask( $sAlias ) . ',';
                $this->_sStatement = substr( $this->_sStatement, 0, -1 );
            }
            // chained
            return $this;
        }
        // statement is invalid
        return false;
    }
    /**
     * Concatenate the group clause.
     *
     * @final
     * @access  public
     * @since   0.0.1
     * @param   mixed   $mGroups
     * @return  MySQL|boolean
     * @uses    __mask()    Masking columns
     */
    final public function group( $mGroups )
    {
        if ( ! is_null( $this->_sStatement ) )
        {
            $this->_sStatement .= ' GROUP BY ';
            // single or list of database objects
            if ( is_string( $mGroups ) )
                $this->_sStatement .= $this->__mask( $mGroups );
            // array of groups
            else if ( is_array( $mGroups ) )
            {
                foreach( $mGroups AS $sColumn )
                    $this->_sStatement .= $this->__mask( $sColumn ) . ',';
                $this->_sStatement = substr( $this->_sStatement, 0, -1 );
            }
            // chained
            return $this;
        }
        // statement is invalid
        return false;
    }
    /**
     * Concatenate a limitation.
     *
     * @final
     * @access  public
     * @since   0.0.1
     * @param   mixed   $mLimit
     * @return  void|false
     */
    final public function limit( $mLimit )
    {
        if ( ! is_null( $this->_sStatement ) )
        {
            $this->_sStatement .= ' LIMIT ';
            // array of length and offset
            if ( is_array( $mLimit ) AND count( $mLimit ) === 2 )
            {
                $this->_sStatement .= (int)$mLimit[ 0 ] . ', ' . (int)$mLimit[ 1 ];
            }
            // single or list of limit
            else
                $this->_sStatement .= $mLimit;
        }
        // statement is invalid
        return false;
    }
    /**
     * Concatenate a order by statement and return
     * the object for chaining.
     *
     * @final
     * @access  public
     * @since   0.0.1
     * @param   mixed   $mOrder
     * @return  MySQL|boolean
     * @uses    __mask()    Masking columns
     */
    final public function order( $mOrder )
    {
        if ( ! is_null( $this->_sStatement ) )
        {
            $this->_sStatement .= ' ORDER BY ';
            // single or list of database objects
            if ( is_string( $mOrder ) )
                $this->_sStatement .= $this->__mask( $mOrder ) . ' ASC';
            // array of orders and directions
            else if ( is_array( $mOrder ) )
            {
                foreach( $mOrder AS $sColumn => $sDirection )
                    $this->_sStatement .= $this->__mask( $sColumn ) . ' ' . strtoupper( $sDirection ) . ',';
                $this->_sStatement = substr( $this->_sStatement, 0, -1 );
            }
            // chained
            return $this;
        }
        // statement is invalid
        return false;
    }
    /**
     * Concatenate a select statement and return
     * the object for statement chaining.
     *
     * @final
     * @access  public
     * @since   0.0.1
     * @param   mixed   $mColumns   default: null
     * @return  MySQL
     * @uses    __mask()    Masking columns
     */
    final public function select( $mColumns = null )
    {
        $this->_sStatement = 'SELECT ';
        // asterisk (select all)
        if ( is_null( $mColumns ) )
            $this->_sStatement .= '*';
        // one or a list of columns
        else if ( is_string( $mColumns ) )
        {
            if ( strpos( $mColumns, ':' ) !== false )
            {
                $aParts = explode( ':', $mColumns, 2 );
                $this->_sStatement .= strtoupper( $aParts[ 0 ] ) . '(' . $this->__mask( $aParts[ 1 ] ) . ')';
            }
            else
                $this->_sStatement .= $this->__mask( $mColumns );
        }
        // array with aliases
        else if ( is_array( $mColumns ) )
        {
            foreach( $mColumns AS $sColumn => $sAlias )
            {
                if ( strpos( $sColumn, ':' ) !== false )
                {
                    $aParts = explode( ':', $sColumn, 2 );
                    $this->_sStatement .= strtoupper( $aParts[ 0 ] ) . '(' . $this->__mask( $aParts[ 1 ] ) . ') AS ' . $this->__mask( $sAlias ) . ',';
                }
                else
                    $this->_sStatement .= $this->__mask( $sColumn ) . ' AS ' . $this->__mask( $sAlias ) . ',';
            }
            $this->_sStatement = substr( $this->_sStatement, 0, -1 );
        }
        // chained
        return $this;
    }
    /**
     * Concatenate the where clause for statement and
     * return object for chaining.
     *
     * @final
     * @access  public
     * @since   0.0.1
     * @param   string  $sColumn
     * @param   string  $sOperator
     * @param   mixed   $mValue
     * @return  MySQL|boolean
     * @uses    __mask()    Masking columns
     * @uses    __quote()   Quote values
     */
    final public function where( $sColumn, $sOperator, $mValue )
    {
        if ( ! is_null( $this->_sStatement ) )
        {
            $this->_sStatement .= ' WHERE ' . $this->__mask( $sColumn );
            $this->_sStatement .= ' ' . strtoupper( $sOperator ) . ' ';
            // string
            if ( is_string( $mValue ) )
                $this->_sStatement .= $this->__quote( $mValue );
            // array
            else if ( is_array( $mValue ) )
                if ( strtoupper( $sOperator ) == 'IN' )
                    $this->_sStatement .= "(" . implode( ',', array_map( array( $this, '__quote' ), $mValue ) ) . ")";
                else if ( strtoupper( $sOperator ) == 'BETWEEN' AND count( $mValue ) === 2 )
                    $this->_sStatement .= $this->__quote( $mValue[ 0 ] ) . ' AND ' . $this->__quote( $mValue[ 1 ] );
                else
                    $this->_sStatement .= '';
            // null
            else if ( is_null( $mValue ) )
                $this->_sStatement .= 'NULL';
            // other
            else
                $this->_sStatement .= $this->__quote( $mValue, PDO::PARAM_INT );
            // chained
            return $this;
        }
        return false;
    }
    /**
     * Return the statement.
     *
     * @final
     * @access  public
     * @since   0.0.1
     * @return  string
     */
    final public function statement()
    {
        return $this->_sStatement;
    }

    final public function delete()
    {

    }

    final public function execute( $sStatement, array $aValues = array() )
    {

    }

    final public function having()
    {

    }

    final public function insert( $sObject )
    {

    }

    final public function join()
    {

    }

    final public function join_left()
    {

    }

    final public function join_right()
    {

    }

    final public function replace( $sObject )
    {

    }

    final public function set( array $aValues )
    {

    }

    final public function update( $sObject )
    {

    }

    final public function values( array $aValues )
    {

    }

    final public function where_and( $sColumn, $sOperator, $mValue )
    {

    }

    final public function where_or( $sColumn, $sOperator, $mValue )
    {

    }

    final public function affected_rows()
    {

    }

    final public function count()
    {

    }

    final public function last_id()
    {

    }




    /**
     * Quote a value.
     *
     * @access  private
     * @since   0.0.1
     * @param   string  $sValue
     * @param   integer $iParam default: 2 string
     * @return  string
     */
    private function __quote( $sValue, $iParam = PDO::PARAM_STR )
    {
        return $this->__oPDO->quote( $sValue, $iParam );
    }
    /**
     * Mask columns for statement.
     *
     * @access  private
     * @since   0.0.1
     * @param   string  $sColumn
     * @return  string
     */
    private function __mask( $sColumn )
    {
        $sColumn = trim( $sColumn );

        if ( $sColumn === '*' )
            return $sColumn;
        elseif ( strpos( $sColumn, '.' ) !== false )
        {
            $aParts = explode( '.', $sColumn );
            foreach ( $aParts as & $sPart )
                if ( $sPart !== '*' )
                    $sPart = '`' . $sPart . '`';

            return implode( '.', $aParts );
        }
        elseif ( strpos( $sColumn, ',' ) !== false )
        {
            $aParts = explode( ',', $sColumn );
            foreach ( $aParts as & $sPart )
                if ( $sPart !== '*' )
                    $sPart = '`' . $sPart . '`';

            return implode( ',', $aParts );
        }
        else
        {
            return '`' . $sColumn . '`';
        }
    }
}