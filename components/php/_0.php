<?php
/**	_0 - main 0 framework class/interface, encapsulates common functions
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
def('d_0_',		dirname(dirname(dirname(__FILE__))).'/');
def('p_0_',		Filesystem::relativePath(d_0_,true,true).'/');
def('u_0_',		_::SERVER('PHP_SELF'));
def('SITE_TITLE',	"0");
def('MINIFIED',		true);
/*-AUTORUN--------------------------------------------------------------------*/
_0::process();
/*----------------------------------------------------------------------------*/
class _0
{
	/*--------------------------------------------------------------------*\
	|* CONSTANTS                                                          *|
	\*--------------------------------------------------------------------*/
	/* NAV TYPES */
	const DIV	= 'div';
	const hTABLE	= 'horizontal table';
	const vTABLE	= 'vertical table';
	
	/*--------------------------------------------------------------------*\
	|* PROCESS                                                            *|
	\*--------------------------------------------------------------------*/
	public static function process()
	{
		$pg = _::REQUEST('Pg',null);
		if ($pg === null) return;
		$o = _::A(_::GLOBALS('pages'),$pg);
		if ($o === null || !is_object($o)) return;
		$c = $o->display();
		return (_::AJAX() || _::POST('ajax')) ? die($c) : $c;
	}
	
	/*--------------------------------------------------------------------*\
	|* PAGE                                                               *|
	\*--------------------------------------------------------------------*/
	public static function page($c='')
	{
		return XHTML::pg(
			XHTML::head(SITE_TITLE,self::js(true).self::css(true)).
			XHTML::body($c.self::analyticsJS()),
			XHTML::TRANSITIONAL//XHTML::STRICT
		).'<!-- '.executionTime().' -->';
	}
	
	/*--------------------------------------------------------------------*\
	|* PAGE COMPONENTS                                                    *|
	\*--------------------------------------------------------------------*/
	public static function main()
	{
		$t = self::template();
		$c = Pg::hiddenContent(_::GLOBALS('pages',array())).
			($t ? $t :	XHTML::div(self::nav(),'nav').
					XHTML::div('','content'));
		return self::page($c);
	}
	public static function nav($type=self::DIV,$delimiter='')
	{
		$c = "";
		$links = array();
		$pages = _::GLOBALS('pages',array());
		foreach ($pages as $i => $pg) $links[$i] = $pg->link();
		if ($type === self::DIV)
			$c = implode($delimiter,$links);
		else if ($type === self::hTABLE)
			$c = "<tr><td>".implode("</td><td>",$links)."</td></tr>";
		else if ($type === self::vTABLE)
			$c = "<tr><td>".implode("</td></tr><tr><td>",$links)."</td></tr>";
		return $c;
	}
	public static function sig()
	{
		return	'&copy; '.date('Y').' '.
			_::C('SITE_OWNER','Oliver C. D. Dodd').
			'<br /><a href="http://01001111.net">01001111</a>';
	}
	
	/*--------------------------------------------------------------------*\
	|* TEMPLATE?                                                          *|
	\*--------------------------------------------------------------------*/
	public static function template()
	{
		return Filesystem::includeContents("template.php");
	}
	
	/*--------------------------------------------------------------------*\
	|* AUTOLOAD                                                           *|
	\*--------------------------------------------------------------------*/
	function autoload($c)
	{
		if (	($f = _01001111::autoload($c)) ||
			($f = Filesystem::find("$c.php",d_0_)))
			include_once $f;
		return $f ? $f : false;
	}
	
	/*--------------------------------------------------------------------*\
	|* JAVASCRIPT/CSS                                                     *|
	\*--------------------------------------------------------------------*/
	public static function js($tags=false)
	{
		$files = MINIFIED ? p_0_.'components/js/0min.js'  : array(
			pLIB.'js/prototype.js',
			pLIB.'js/effects.js',
			pLIB.'js/window.js',
			p01001111.'js/+.js',
			p01001111.'js/dom+.js',
			p_0_.'components/js/0.js'
		);
		return $tags ? XHTML::javascripts($files) : $files;
	}
	public static function css($tags=false)
	{
		$files = MINIFIED ? p_0_.'components/css/0min.css'  : array(
			p01001111.'css/01001111.css',
			//p_0_.'components/css/0.css',
			pLIB.'css/themes/default.css',
			pLIB.'css/themes/alphacube.css');
		return $tags ? XHTML::stylesheets($files) : $files;
	}
	
	/*--------------------------------------------------------------------*\
	|* ANALYTICS + TRACKING CODES                                         *|
	\*--------------------------------------------------------------------*/
	public static function analyticsJS()
	{
		$js = '';
		if (_::C('GOOGLE_ANALYTICS')) $js .= '
		<script type="text/javascript">
			var gaJsHost = (("https:" == document.location.protocol)
				? "https://ssl."
				: "http://www.");
			document.write(unescape("%3Cscript src=\'"+gaJsHost+ "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));
		</script>
		<script type="text/javascript">
			var pageTracker = _gat._getTracker("'._::C('GOOGLE_ANALYTICS').'");
			pageTracker._initData();
			pageTracker._trackPageview();
		</script>';
		return $js;
	}
}
?>