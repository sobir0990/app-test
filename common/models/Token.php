<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "token".
 *
 * @property int $id
 * @property int $user_id
 * @property string $content
 * @property int $type
 * @property string $data
 * @property string $token
 * @property int $status
 * @property int $expires
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 */
class Token extends ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 0;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'token';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['token', 'expires'], 'required'],
            [['token'], 'string', 'max' => 32],
            ['status', 'default', 'value' => 1],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'user_id' => Yii::t('backend', 'User ID'),
            'content' => Yii::t('backend', 'Content'),
            'type' => Yii::t('backend', 'Type'),
            'status' => Yii::t('backend', 'Status'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
        ];
    }

    public function fields()
    {
        return array(
            'id',
            'user_id',
            'expires',
            'token',
            'status'
        );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
