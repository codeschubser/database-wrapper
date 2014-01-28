<?php
/**
 * @package     Database-Wrapper
 * @subpackage  Interfaces
 * @author      Codeschubser <blog@codeschubser.de>
 * @version     $Id: iface.QueryBuilder.php,v 0.0.1 28.01.2014 09:07:56 mitopp Exp $;
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
interface QueryBuilder
{
    public function select( $mColumns = null );
    public function from( $sObject );
    public function where( $sColumn, $sOperator, $mValue );
    public function limit( $mLimit );
    public function group( $mGroups );
    public function order( $mOrder );

    public function join();
    public function join_left();
    public function join_right();
    public function having();
    public function where_and( $sColumn, $sOperator, $mValue );
    public function where_or( $sColumn, $sOperator, $mValue );

    public function update( $sObject );
    public function set( array $aValues );

    public function insert( $sObject );
    public function replace( $sObject );
    public function values( array $aValues );

    public function delete();
}