<?php

use app\models\Products;
return $arrayProduct = json_encode(Products::find()->with('user')->asArray()->all());