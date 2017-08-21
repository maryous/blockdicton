<!-- Block blockdicton -->
<div id="blockdicton_block_left" class="block">
    <h4>Dicton du jour, Bonjour :)</h4>
    <div class="block_content">
        <a href="{$BDictonLink}">
            <p class='text-center'>{$smarty.now|date_format: '%e %B %Y'} -  {$BDictonSaint}</p>
            <p class='text-center text-bold'>‘{$BDictonDicton}’</p>
            <p class='text-center text-italic'>{$BDictonConseil}</p>
        </a>
    </div>
</div>
<!-- /Block blockdicton -->