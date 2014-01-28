<?php
/**
 * @package     Database-Wrapper
 * @subpackage  Interfaces
 * @author      Codeschubser <blog@codeschubser.de>
 * @version     $Id: iface.DatabaseActions.php,v 0.0.1 28.01.2014 09:11:15 mitopp Exp $;
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
interface DatabaseActions
{
    public function affected_rows();
    public function count();
    public function execute( $sStatement, array $aValues = array() );
    public function last_id();
}