<?php
/** 	Pg Class - create and configure a page for the 0 framework.
 *
 *	Copyright (c) 2008 Oliver C Dodd
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
class Pg
{
	/*--------------------------------------------------------------------*\
	|* CONSTANTS                                                          *|
	\*--------------------------------------------------------------------*/
	/* types */
	const DOWNLOAD		= 'download';
	const FILE		= 'file';
	const HIDDENDIV		= 'hiddenDiv';
	const JAVASCRIPT	= 'javascript';
	const LINK		= 'link';
	const PARASITE		= 'parasite';
	const PHP		= 'php';
	const REMOTE		= 'remote';
	const TEXT		= 'string';
	
	/*--------------------------------------------------------------------*\
	|* VARIABLES                                                          *|
	\*--------------------------------------------------------------------*/
	public $name;
	public $type;
	public $value;
	public $linkAttrs;
	public $defaultPage;
	public $hiddenContentID;
	
	/*--------------------------------------------------------------------*\
	|* CONSTRUCTOR                                                        *|
	\*--------------------------------------------------------------------*/
	public function __construct($name,$type,$value='',$linkAttrs=array(),
		$defaultPage=false,$hiddenInline=false)
	{
		$this->name		= $name;
		$this->type		= $type;
		$this->value		= $value;
		$this->linkAttrs	= $linkAttrs;
		$this->defaultPage	= $defaultPage;
		$this->hiddenInline	= $hiddenInline;
		$this->hiddenContentID	= sha1($this->name);
	}
	
	/*--------------------------------------------------------------------*\
	|* DISPLAY                                                            *|
	\*--------------------------------------------------------------------*/
	public function display()
	{
		$v = $this->value;
		switch($this->type) {
			case self::DOWNLOAD	: return "";
			case self::FILE		: return Filesystem::load($v);
			case self::LINK		: return "";
			case self::JAVASCRIPT	: return "";
			case self::PARASITE	: return Parasite::display($v);
			case self::PHP		: return Filesystem::includeContents($v);
			case self::REMOTE	: return HTTP::get($v);
			case self::TEXT		: return $v;
			default			: return "";
		}
	}
	
	/*--------------------------------------------------------------------*\
	|* LINK                                                               *|
	\*--------------------------------------------------------------------*/
	public function link()
	{
		$n = $this->name;
		$v = $this->value;
		$a = $this->linkAttrs;
		if ($this->hiddenInline)
			return XHTML::jl("_0.use('$this->hiddenContentID');",$n,$a);
		switch($this->type) {
			case self::DOWNLOAD 	: return XHTML::a($v,$n,$a);
			case self::LINK 	: return XHTML::lnw($v,$n,$a);
			case self::JAVASCRIPT	: return XHTML::jl($v,$n,$a);
			case self::FILE		: 
			case self::PARASITE	: 
			case self::PHP		: 
			case self::REMOTE	: 
			case self::TEXT		: 
			default		: return XHTML::jl("_0.get('$n');",$n,$a);
		}
	}
	
	/*--------------------------------------------------------------------*\
	|* DEFAULT CONTENT                                                    *|
	\*--------------------------------------------------------------------*/
	public static function defaultContent()
	{
		$pg = null;
		foreach (_::GLOBALS('pages') as $p)
			if ((get_class($p) === __CLASS__)&&($p->defaultPage)) {
				$pg = $p;
				continue;
			}
		return $pg ? $pg->display() : "";
	}
	
	/*--------------------------------------------------------------------*\
	|* INDEX BY NAME                                                      *|
	\*--------------------------------------------------------------------*/
	public static function indexByName($pages)
	{
		$a = array();
		foreach ($pages as $pg) $a[_::O($pg,'name')] = $pg;
		return $a;
	}
	
	/*--------------------------------------------------------------------*\
	|* HIDDEN CONTENT                                                     *|
	\*--------------------------------------------------------------------*/
	public static function hiddenContent($pages)
	{
		if (!$pages || !is_array($pages))
			return;
		$hc = "";
		foreach ($pages as $pg) if ($pg->hiddenInline)
			$hc .= "<div id='$pg->hiddenContentID' style='display:none'>
					{$pg->display()}
				</div>";
		return $hc;
	}
}
?>