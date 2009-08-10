/**	0 Framework Javascript Class
 *
 *	Copyright (c) 2007-2008 Oliver C Dodd
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
_0 = {	/*-ALERT/CONFIRM------------------------------------------------------*/
	alert: function(msg)
	{
		Dialog.alert(msg,{
			windowParameters:{
				className:"alphacube",
				width:300},
			okLabel:"(+)"});
	},
	confirm: function(msg,onOK)
	{
		Dialog.confirm("<div class='ac-center'>"+msg+"</div>",{
			windowParameters:{
				className:"alphacube",
				width:200},
			okLabel:"(+)",
			cancelLabel:"(-)",
			cancel:function(win) { return false; },
			ok:function(win) { onOK(); return true; }});
	},
	info: function(msg,title,w,h)
	{
		Dialog.confirm("<div class='ac-center'>"+msg+"</div>",{
			className:"alphacube",
			title:title,
			width:200,
			height:h,
			resizable:true});
	},
	/*-AJAX---------------------------------------------------------------*/
	update: function(div,params)
	{
		new Ajax.Updater(div,"index.php",{
			method:"POST",
			evalScripts:true,
			parameters:params});
	},
	request: function(params,callback)
	{
		var r = new Ajax.Request("index.php",{
			method:"POST",
			parameters:params,
			onSuccess:callback});
		return false;
	},
	get: function(pg)
	{
		new Ajax.Updater("content","index.php",{
			method:"POST",
			evalScripts:true,
			parameters:"Pg="+escape(pg)});
	},
	use: function(contentID)
	{
		$("content").innerHTML = $(contentID).innerHTML;
		$("content").innerHTML.evalScripts();
	},
	
	/*-VALIDATION---------------------------------------------------------*/
	invalid: function(div)
	{
		return function(e,b)
		{
			if (!b&&div) Element.update(div,"INVALID");
			e.style.color = (b) ? "" : "#ff0000";
		};
	}
};
