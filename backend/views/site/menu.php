<?php
/**
 * Created by PhpStorm.
 * User: OKS
 * Date: 11.05.2019
 * Time: 16:03
 */
?>
<menu id="nestable-menu">
    <button type="button" data-action="expand-all">Expand All</button>
    <button type="button" data-action="collapse-all">Collapse All</button>
</menu>

<div class="cf nestable-lists">

    <div class="dd" id="nestable">
        <ol class="dd-list">
            <li class="dd-item" data-id="1">
                <div class="dd-handle">Item 1</div>
            </li>
            <li class="dd-item" data-id="2">
                <div class="dd-handle">Item 2</div>
                <ol class="dd-list">
                    <li class="dd-item" data-id="3"><div class="dd-handle">Item 3</div></li>
                    <li class="dd-item" data-id="4"><div class="dd-handle">Item 4</div></li>
                    <li class="dd-item" data-id="5">
                        <div class="dd-handle">Item 5</div>
                        <ol class="dd-list">
                            <li class="dd-item" data-id="6"><div class="dd-handle">Item 6</div></li>
                            <li class="dd-item" data-id="7"><div class="dd-handle">Item 7</div></li>
                            <li class="dd-item" data-id="8"><div class="dd-handle">Item 8</div></li>
                        </ol>
                    </li>
                    <li class="dd-item" data-id="9"><div class="dd-handle">Item 9</div></li>
                    <li class="dd-item" data-id="10"><div class="dd-handle">Item 10</div></li>
                </ol>
            </li>
            <li class="dd-item" data-id="11">
                <div class="dd-handle">Item 11</div>
            </li>
            <li class="dd-item" data-id="12">
                <div class="dd-handle">Item 12</div>
            </li>
        </ol>
    </div>

</div>
<?php
$js = "$(document).ready(function()
    {
        var updateOutput = function(e)
        {
            var list   = e.length ? e : $(e.target),
                output = list.data('output');
            if (window.JSON) {
                output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
            } else {
                output.val('JSON browser support required for this demo.');
            }
        };
        // activate Nestable for list 1
        $('#nestable').nestable({
            group: 1
        })
            .on('change', updateOutput);
        // activate Nestable for list 2
        $('#nestable2').nestable({
            group: 1
        })
            .on('change', updateOutput);
        // output initial serialised data
        updateOutput($('#nestable').data('output', $('#nestable-output')));
        updateOutput($('#nestable2').data('output', $('#nestable2-output')));
        $('#nestable-menu').on('click', function(e)
        {
            var target = $(e.target),
                action = target.data('action');
            if (action === 'expand-all') {
                $('.dd').nestable('expandAll');
            }
            if (action === 'collapse-all') {
                $('.dd').nestable('collapseAll');
            }
        });
        $('#nestable3').nestable();
    })";
$this->registerJs($js, \yii\web\View::POS_END);
