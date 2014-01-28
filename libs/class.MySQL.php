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
    protected $_bConnected;
    protected $_sStatement;

    private $__oPDO;

    public function __construct( $sHost, $sUser, $sPass, $sDatabase, $sCharset, array $aOptions = array() )
    {
        $this->_bConnected  = false;
        $this->_sStatement  = false;
        $this->__oPDO       = null;

        try
        {
            $sDSN = 'mysql:host=' . $sHost . ';dbname=' . $sDatabase . ';charset=' . $sCharset;

            $this->__oPDO = new PDO( $sDSN, $sUser, $sPass, $aOptions );
            $this->__oPDO->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $this->__oPDO->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC );

            $this->_bConnected = true;
        }
        catch ( Exception $ex )
        {
            die( $ex->getMessage() );
        }
    }

    public function __destruct()
    {
        $this->__oPDO       = null;
        $this->_bConnected  = false;
        $this->_sStatement  = false;
    }
    /**
     * Concatenate from clause for select statement
     * and return the object for chaining
     *
     * @final
     * @access  public
     * @since   0.0.1
     * @param   mixed   $sObject
     * @return  MySQL
     * @uses    __mask()    Masking columns
     */
    final public function from( $sObject )
    {
        if ( $this->_sStatement !== false )
        {
            $this->_sStatement .= ' FROM ';
            if ( is_string( $sObject ) )
                $this->_sStatement .= $this->__mask( $sObject );
            else if ( is_array( $sObject ) )
            {
                foreach( $sObject AS $sColumn => $sAlias )
                    $this->_sStatement .= $this->__mask( $sColumn ) . ' AS ' . $this->__mask( $sAlias ) . ',';
                $this->_sStatement = substr( $this->_sStatement, 0, -1 );
            }
        }
        return $this;
    }

    final public function group( $mGroups )
    {

    }

    final public function limit( $iLength )
    {

    }

    final public function offset( $iOffset )
    {

    }

    final public function order( $mOrder )
    {

    }
    /**
     * Concatenate a select statement and return
     * the object for statement chaining.
     *
     * @final
     * @access  public
     * @since   0.0.1
     * @param   mixed   $mColumns
     * @return  MySQL
     * @uses    __mask()    Masking columns
     */
    final public function select( $mColumns = null )
    {
        $this->_sStatement = 'SELECT ';
        if ( is_null( $mColumns ) )
            $this->_sStatement .= '*';
        else if ( is_string( $mColumns ) )
            $this->_sStatement .= $this->__mask( $mColumns );
        else if ( is_array( $mColumns ) )
        {
            foreach( $mColumns AS $sColumn => $sAlias )
                $this->_sStatement .= $this->__mask( $sColumn ) . ' AS ' . $this->__mask( $sAlias ) . ',';
            $this->_sStatement = substr( $this->_sStatement, 0, -1 );
        }
        return $this;
    }

    final public function where( $sColumn, $sOperator, $mValue )
    {

    }

    final public function statement()
    {
        return $this->_sStatement;
    }



    final private function __mask( $sColumn )
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