<?php
/**
 *	0 - A simple framework for rapidly developing personal websites.
 *
 *	Copyright (c) 2006-2008 Oliver C Dodd
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a 
 *  copy of this software and associated documentation files (the "Software"),
 *  to deal in the Software without restriction, including without limitation
 *  the rights to use, copy, modify, merge, publish, distribute, sublicense,
 *  and/or sell copies of the Software, and to permit persons to whom the 
 *  Software is furnished to do so, subject to the following conditions:
 *  
 *  The above copyright notice and this permission notice shall be included in
 *  all copies or substantial portions of the Software.
 *  
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL 
 *  THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 *  FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 *  DEALINGS IN THE SOFTWARE.
 */
/*----------------------------------------------------------------------------*\
|* CURRENT VERSION / LAST UPDATE                                              *|
\*----------------------------------------------------------------------------*/
define('v_0_',		"1.0.2"); //20081111
/*----------------------------------------------------------------------------*\
|* ABSOLUTE PATH                                                              *|
\*----------------------------------------------------------------------------*/
define('d_0_',		dirname(__FILE__).'/');
/*----------------------------------------------------------------------------*\
|* 01001111                                                                   *|
\*----------------------------------------------------------------------------*/
@include_once(d_0_.'lib/01001111/include.php');
@include_once(d_0_.'../lib/01001111/include.php');
/*----------------------------------------------------------------------------*\
|* AUTOLOAD                                                                   *|
\*----------------------------------------------------------------------------*/
if (!function_exists('__autoload')) {
	function __autoload($c)
	{
		if (	($f = _01001111::autoload($c)) ||
			($f = _::C('INCLUDE_PARASITE')
				? Filesystem::find("$c.php",INCLUDE_PARASITE)
				: false) ||
			($f = Filesystem::find("$c.php",d_0_)))
			include_once $f;
		return $f ? $f : false;
	}
}
/*----------------------------------------------------------------------------*\
|* RELATIVE PATH / URL                                                        *|
\*----------------------------------------------------------------------------*/
def('p_0_',		Filesystem::relativePath(__FILE__,true,true).'/');
def('u_0_',		_::SERVER('PHP_SELF'));
/*----------------------------------------------------------------------------*\
|* CONFIG                                                                     *|
\*----------------------------------------------------------------------------*/
@include_once('config.php');
$GLOBALS['pages'] = isset($pages) ? Pg::indexByName($pages) : array();
/*----------------------------------------------------------------------------*\
|* 0 COMPONENTS                                                               *|
\*----------------------------------------------------------------------------*/
include_once(d_0_.'components/php/_0.php');
include_once(d_0_.'components/php/Pg.php');
include_once(d_0_.'components/php/TabbedPane.php');
/*----------------------------------------------------------------------------*\
|* PARASITE?                                                                  *|
\*----------------------------------------------------------------------------*/
if (_::C('INCLUDE_PARASITE'))
	@include_once(INCLUDE_PARASITE.'/include.php');
?>
