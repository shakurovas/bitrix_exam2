<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<p><b><?=GetMessage("SIMPLECOMP_EXAM2_CAT_TITLE")?></b></p>

<?php if (count($arResult['CLASSIFIER']) > 0) {?>
    <ul>
        <?php foreach ($arResult['CLASSIFIER'] as $arClassificator) {?>
            <li>
                <b>
                    <?=$arClassificator['NAME'];?>
                </b>
                <?php if (count($arClassificator['ELEMENTS']) > 0) {?>
                    <ul>
                        <?php foreach ($arClassificator['ELEMENTS'] as $arItems) {?>
                            <?php
                            //echo '<pre>';
                            //print_r($arItems);
                            //echo '</pre>';?>
                            <li>
                                <?=$arItems['NAME'];?>
                                <?=$arItems['PROPERTY']['PRICE']['VALUE'];?>
                                <?=$arItems['PROPERTY']['MATERIAL']['VALUE'];?>
                                <?=$arItems['PROPERTY']['ARTNUMBER']['VALUE'];?>
                                <a href="<?=$arItems['DETAIL_PAGE_URL'];?>">Детально</a>
                            </li>
                        <?php }?>
                    </ul>
                <?php }?>
            </li>
        <?php }?>
    </ul>
<?php }?>
