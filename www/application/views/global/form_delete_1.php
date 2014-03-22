<? // echo Form::open($controller_link.'delete/'.$global_item->id)?>
<?=Form::open(Request::current()->uri(),array('onsubmit'=>'return false;'));?>
	<fieldset>
		<h2><?=$global_params['delete']['caption']?></h2>
		<p><?=$global_params['delete']['answer']?></p>
        <?=Form::hidden('confirm', 'yes');?>
	<div
		class="senddata-token btn btn-primary mr15px"
		data-link="/<?=Request::current()->uri()?>"
		data-input='#message-text form'
	><?=$global_params['delete']['button']?></div>

    <? if ($LOADTYPE == LT_SHOWMESSAGE) { ?>
        <div class="closemessage btn">Отмена</div>
    <? } elseif($LOADTYPE == LT_LOADCONTENT) { ?>
        <div class="closecontent btn" style="position: static">Отмена</div>
    <? } ?>
	<div class="clear"></div>
	</fieldset>
<?=Form::close()?>
