<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<p><b><?=GetMessage("SIMPLECOMP2_EXAM2_AUTHORS_AND_NEWS")?></b></p>
<pre>
<?
if (count($arResult['AUTHORS'])) { ?>
<!--    <ul>-->
        <?php foreach ($arResult['AUTHORS'] as $key => $value) {?>
<!--            <li>-->
                <?='Автор [id: ' .$key . '], [login: ' . $value['LOGIN'] . ']';?>
                <?php if (count($value['NEWS'])) {?>
<!--                    <ul>-->
                        <?php foreach ($value['NEWS'] as $arNews) {?>
<!--                            <li>-->
                               - <?=$arNews["NAME"]?>
<!--                            </li>-->
                        <?php }?>
<!--                    </ul>-->
                <?php }?>
<!--            </li>-->
        <?php }?>
<!--    </ul>-->
<?php
}

//echo '<pre>';
//print_r($arResult['AUTHORS']);
//echo '</pre>';