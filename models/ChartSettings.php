<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "chart_settings".
 *
 * @property int $id
 * @property int $user_id
 * @property string $theme
 * @property string $total_color
 * @property string $count_color
 * @property string $text_color
 *
 * @property Users $user
 */
class ChartSettings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chart_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'theme', 'total_color', 'count_color', 'text_color'], 'required'],
            [['user_id'], 'integer'],
            [['theme', 'total_color', 'count_color', 'text_color'], 'string', 'max' => 15],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'theme' => 'Theme',
            'total_color' => 'Total Color',
            'count_color' => 'Count Color',
            'text_color' => 'Text Color',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
