<?php
include "filter/functions.php";

$filter = &$GLOBALS["documentsFilter"];
$arDateStartList = [1 => 'В течение месяца', 2 => 'В течение трех месяцев', 3 => 'В течение года'];
$arDateFinishList = [1 => 'В течение месяца', 2 => 'В течение трех месяцев', 3 => 'В течение года'];
$arMethodList = [
    5 => 'Открытый одноэтапный конкурс',
    98 => 'Открытый двухэтапный конкурс',
    99 => 'Открытый одноэтапный конкурс с предварительным квалификационным отбором',
    100 => 'Конкурс с переторжкой',
    101 => 'Открытый аукцион, в том числе в электронной форме',
    102 => 'Аукцион с предварительным квалификационным отбором',
    103 => 'Открытый запрос котировок',
    104 => 'Открытый запрос предложений с переторжкой',
    105 => 'Конкурентные переговоры',
    106 => 'Закрытый одноэтапный конкурс',
    107 => 'Открытый запрос предложений в электронной форме с переторжкой',
    108 => 'Открытый одноэтапный конкурс с переторжкой',
    109 => 'Закупка у единственного поставщика (исполнителя, подрядчика)',
    110 => 'Открытый запрос предложений',
    111 => 'Открытый аукцион',
    112 => 'Открытый запрос котировок с переторжкой'
];
$arStatusList = [
    113 => 'Прием заявок',
    114 => 'Рассмотрение заявок',
    115 => 'Завершен',
    116 => 'Продление приема заявок'
];
$arPriceList = [
    '-500000' => 'До 500 000 ₽',
    '-1000000' => 'До 1 000 000 ₽',
    '-1500000' => 'До 1 500 000 ₽',
    '-3000000' => 'До 3 000 000 ₽',
    '+3000000' => 'От 3 000 000 ₽'
];

$currentValues = [
    'name' => trim($_REQUEST['name']),
    'dateStart' => trim($_REQUEST['dateStart']), 
    'dateFinish' => trim($_REQUEST['dateFinish']), 
    'method' => trim($_REQUEST['method']),
    'status' => trim($_REQUEST['status']),
    'price' => trim($_REQUEST['price'])
];

foreach ($currentValues as &$value) {
    $value = htmlspecialcharsbx($value);
}

if( $currentValues['name'] ) {
    $filter[] = [
        'LOGIC' => 'OR',
        ['NAME' => "%" . $currentValues['name'] . "%"],
        ['PROPERTY_ORDER_ID' => "%" . $currentValues['name'] . "%"]
    ];
}
$currentDate = date('d.m.Y');
$currentDate = explode(".");
$currentDate = [
    'd' => $currentDate[0],
    'm' => $currentDate[1],
    'y' => $currentDate[2]
];

if( $currentValues['dateStart'] ) {
    $filter['>PROPERTY_DATE_START'] = filter\buildDate($currentValues['dateStart']);
}

if( $currentValues['dateFinish'] ) {
    $filter['>PROPERTY_DATE_FINISH'] = filter\buildDate($currentValues['dateFinish']);
}

if( $currentValues['method'] ) {
    $filter['PROPERTY_METHOD'] = $currentValues['method'];
}

if( $currentValues['status'] ) {
    $filter['PROPERTY_STATUS'] = $currentValues['status'];
}

if( $currentValues['price'] ) {
    $type = substr($currentValues['price'], 0, 1);
    $value = substr($currentValues['price'], 1);

    if ($type == "+") {
        $type = ">";
    } elseif ($type == "-") {
        $type = '<';
    }

    if( $type != ">" && $type != "<" ) {
        $type = "";
    }

    $filter[$type . 'PROPERTY_PRICE'] = $value;
}
?>
<form class="filter-form" id="filter-form" action="/postavshchikam/tekushchie-zakupki/" method="get">
    <div>
        <input type="text" name="name" size="20" value="<?=$currentValues['name']?>" placeholder="Номер или название заказа">
    </div>

    <div>
        <select name="dateStart">
            <option value="" disabled selected hidden>Дата начала</option>
            <?php foreach($arDateStartList as $value => $name): ?>
                <option value="<?=$value?>" <?php if($currentValues['dateStart'] == $value) echo "selected" ?>><?=$name?></option>
            <?php endforeach ?>
        </select>
    </div>
<input type="reset" class="reset_btn" name="del_filter" value="Сбросить">
	<div>	
        <select name="dateFinish">
            <option value="" disabled selected hidden>Дата окончания</option>
            <?php foreach($arDateFinishList as $value => $name): ?>
                <option value="<?=$value?>" <?php if($currentValues['dateFinish'] == $value) echo "selected" ?>><?=$name?></option>
            <?php endforeach ?>
        </select>		
    </div>
	<div>
		<select name="method">
            <option value="" disabled selected hidden>Способ размещения</option>
            <?php foreach($arMethodList as $value => $name): ?>
                <option value="<?=$value?>" <?php if($currentValues['method'] == $value) echo "selected" ?>><?=$name?></option>
            <?php endforeach ?>
        </select>
    </div>
	<div>
		<select name="status">
            <option value="" disabled selected hidden>Статус</option>
            <?php foreach($arStatusList as $value => $name): ?>
                <option value="<?=$value?>" <?php if($currentValues['status'] == $value) echo "selected" ?>><?=$name?></option>
            <?php endforeach ?>
        </select>
    </div>
	<div>
		<select name="price">
            <option value="" disabled selected hidden>Цена</option>
            <?php foreach($arPriceList as $value => $name): ?>
                <option value="<?=$value?>" <?php if($currentValues['price'] == $value) echo "selected" ?>><?=$name?></option>
            <?php endforeach ?>
        </select>
    </div>

    <input type="submit" value="Отправить" style="display:none;">
</form>