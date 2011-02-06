<?php
/** 	Tabbed Pane.
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
def('pTABS',		'');
def('REQUEST_ID',	'tp');
class TabbedPane
{
	/*--------------------------------------------------------------------*\
	|* CONSTANTS                                                          *|
	\*--------------------------------------------------------------------*/
	static $lasKeyIndex = 0;
	
	/*--------------------------------------------------------------------*\
	|* VARIABLES                                                          *|
	\*--------------------------------------------------------------------*/
	public $id;
	public $label;
	public $file;
	public $keyIndex;
	
	private $className;
	
	/*--------------------------------------------------------------------*\
	|* CONSTRUCTOR                                                        *|
	\*--------------------------------------------------------------------*/
	public function __construct($id,$label,$file=null,$keyIndex=null)
	{
		$this->id		= $id;
		$this->label		= $label;
		$this->file		= $file === null ? pTABS."$id.php" : $file;
		$this->keyIndex		= $keyIndex === null
						? ++self::$lasKeyIndex
						: $keyIndex;
	}
	
	/*--------------------------------------------------------------------*\
	|* DISPLAY                                                            *|
	\*--------------------------------------------------------------------*/
	public function tab($group="")
	{
		$cl = get_class($this);
		return "<div	id='tab$this->id'
				class='$cl tab $group $this->id'
				onclick='$cl$group.change(\"$this->id\");'>
					$this->label
			</div>";
	}
	public function pane($group="")
	{
		$cl = get_class($this);
		$content = Filesystem::includeContents($this->file);
		return "<div	id='pane$this->id'
				class='$cl pane $group $this->id'
				style='display:none;'>
					$content
			</div>";
	}
	
	/*--------------------------------------------------------------------*\
	|* TABS/PANES                                                         *|
	\*--------------------------------------------------------------------*/
	public static function tabs($tabbedPanes,$group="")
	{
		$tabs = "";
		foreach ($tabbedPanes as $tp)
			$tabs .= $tp->tab($group);
		return $tabs;
	}
	public static function panes($tabbedPanes,$group="",$registerKeys=true)
	{
		$panes = "";
		$default = 	isset($_REQUEST[REQUEST_ID])&&
				isset($tabbedPanes[$_REQUEST[REQUEST_ID]])
					? $tabbedPanes[$_REQUEST[REQUEST_ID]]
					: current($tabbedPanes);
		foreach ($tabbedPanes as $tp)
			$panes .= $tp->pane($group);
		if ($registerKeys) {
			$registerKeys = array();
			foreach ($tabbedPanes as $t)
				$registerKeys[$t->keyIndex] = $t->id;
		}
		return $panes.self::js($default,$group,$registerKeys);
	}
	
	/*--------------------------------------------------------------------*\
	|* JAVASCRIPT                                                         *|
	\*--------------------------------------------------------------------*/
	protected static function js($default=null,$group="",$registerKeys=true)
	{
		$class = __CLASS__;
		$c = $class.$group;
		ob_start();
		?>
<script type="text/javascript">
	<?php echo $c; ?> = {
		/*-VARIABLES/CONSTANTS----------------------------------------*/
		className:	"<?php echo $class; ?>",
		group:		"<?php echo $group; ?>",
		tabs:		<?php echo json_encode($registerKeys); ?>,
		/*-CHANGE TAB-------------------------------------------------*/
		change: function(id)
		{
			this.stripActive();
			this.setActive(id);
			this.hidePanes();
			this.showPane(id);
			<?php if (_::C('GOOGLE_ANALYTICS')) echo "
			  try {	var t = _gat._getTracker('"._::C('GOOGLE_ANALYTICS')."');
			  	t._initData();
			  	t._trackEvent('tab', id);
			  } catch(e) {}";
			?>
		},
		/*-ACTIVE TAB-------------------------------------------------*/
		stripActive: function()
		{
			var e = $$("."+this.className+"."+this.group);
			for (var i = 0; i < e.length; i++)
				e[i].removeClassName("active");
		},
		setActive: function(id)
		{
			$("tab"+id).addClassName("active");
			$("pane"+id).addClassName("active");
		},
		/*-SHOW/HIDE--------------------------------------------------*/
		hidePanes: function()
		{
			var e = $$("."+this.className+".pane."+this.group);
			for (var i = 0; i < e.length; i++)
				e[i].hide();
		},
		showPane: function(id)
		{
			$("pane"+id).show();
		},
		/*-KEY SWITCH-------------------------------------------------*/
		keySwitch: function(e)
		{
			if (!e.altKey) return;
			var key = String.fromCharCode(
				(typeof e.which != "undefined")
					? e.which
					: e.keyCode);
			if (this.tabs[key] != undefined)
				this.change(this.tabs[key]);
		}
	};
	<?php if ($default !== null) { ?>
		<?php echo $c; ?>.change(<?php echo "'$default->id','$group','$class'"; ?>);
	<?php } ?>
	<?php if ($registerKeys) { ?>
		Event.observe(window,"keydown",<?php echo $c; ?>.keySwitch.bind(<?php echo $c; ?>));
		Event.observe(document.body,"keydown",<?php echo $c; ?>.keySwitch.bind(<?php echo $c; ?>));
	<?php } ?>
</script>
		<?php
		$js = ob_get_contents();
		ob_end_clean();
		return $js;
	}
}
?>