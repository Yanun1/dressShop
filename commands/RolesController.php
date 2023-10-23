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

        $permit = Yii::$app->authManager->createPermission('Update elements');
        $permit->description = 'Право менять содержание продуктов и заказов';
        Yii::$app->authManager->add($permit);

        $permit = Yii::$app->authManager->createPermission('Add product');
        $permit->description = 'Право добавлять новые продукты';
        Yii::$app->authManager->add($permit);

        $role_admin = Yii::$app->authManager->getRole('admin');
        $role_employee = Yii::$app->authManager->getRole('employee');
        $role_user = Yii::$app->authManager->getRole('user');
        $permit_update = Yii::$app->authManager->getPermission('Update elements');
        $permit_add = Yii::$app->authManager->getPermission('Add product');
        Yii::$app->authManager->addChild($role_admin, $permit_update);
        Yii::$app->authManager->addChild($role_admin, $permit_add);
        Yii::$app->authManager->addChild($role_employee, $permit_update);
        Yii::$app->authManager->addChild($role_employee, $permit_add);
        Yii::$app->authManager->assign($role_admin, 1);
        Yii::$app->authManager->assign($role_employee, 2);
        Yii::$app->authManager->assign($role_user, 3);

    }
}