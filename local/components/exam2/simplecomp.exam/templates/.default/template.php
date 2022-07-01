<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<p><b><?=GetMessage("SIMPLECOMP_EXAM2_CAT_TITLE")?></b></p>
<?php if (count($arResult['NEWS']) > 0) {?>
    <ul>
        <?php foreach($arResult['NEWS'] as $arNews){?>
            <li>
                <b>
                    <?=$arNews['NAME'];?>
                </b>
                - <?=$arNews['ACTIVE_FROM'];?>
                (<?=implode(', ', $arNews['SECTIONS']);?>)
            </li>

            <?if (count($arNews['PRODUCTS']) > 0) {?>
                <ul>
                    <?php foreach ($arNews['PRODUCTS'] as $arProduct) {?>
                        <li>
                           <?=$arProduct['NAME'];?> -
                           <?=$arProduct['PROPERTY_ARTNUMBER_VALUE'];?> -
                           <?=$arProduct['PROPERTY_MATERIAL_VALUE'];?> -
                           <?=$arProduct['PROPERTY_PRICE_VALUE'];?>
                        </li>
                    <?}?>
                </ul>
            <?}?>
        <?}?>
    </ul>
<?php
}?>