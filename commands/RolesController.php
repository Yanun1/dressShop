<?php

namespace app\commands;
use yii\base\Controller;
use Yii;

class RolesController extends Controller
{
    public static function defaultRoles() {
        $admin = Yii::$app->authManager->createRole('admin');
        $admin->description = "administrator";
        Yii::$app->authManager->add($admin);

        $employee = Yii::$app->authManager->createRole('employee');
        $employee->description = "employee";
        Yii::$app->authManager->add($employee);

        $user = Yii::$app->authManager->createRole('user');
        $user->description = "user";
        Yii::$app->authManager->add($user);
    }
}