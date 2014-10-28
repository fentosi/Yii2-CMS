<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;

use yii\behaviors\TimestampBehavior;

use yii\db\ActiveRecord;
use yii\db\Expression;

use yii\web\IdentityInterface;



/**
 * User model
 *
 * @property integer $id
 * @property string $name
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{

	public $change_password;
	public $password_new;
	public $password_confirm;
	
	private static $_user_types = [
		1 => 'User',
		2 => 'Admin'
	];
	
	public static function getUserType() {
		return self::$_user_types;
	}
	
    /**
     * @inheritdoc
     */
	public function behaviors() {
			
 		return [
				[
				'class' => TimestampBehavior::className(),
				'updatedAtAttribute' => 'updated_at',
				'value' => new Expression('NOW()'),
				],
			];			
	}	

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'name', 'type'], 'required'],
            ['email', 'unique'],
            ['email', 'email'],
           	['email', 'string', 'max' => 255],
           	
           	['type', 'in', 'range' => array_keys($this->getUserType())],
			
			[['change_password'], 'required', 'on' => 'update'],
			[['change_password'], 'in', 'range' => [0, 1] , 'on' => 'update'],
			
            [['password_new', 'password_confirm'], 'required', 'on' => 'create'],
            [['password_new'], 'string', 'min' => 6],
            
			[['password_new', 'password_confirm'], 'required', 'when' => function($model) {
				return $model->change_password == 1;
			}, 'on' => 'update'],
			
			['password_confirm', 'compare', 'compareAttribute' => 'password_new', 'when' => function($model) {
				return $model->change_password == 1;
			}, 'on' => ['update']],
			
			['password_confirm', 'compare', 'compareAttribute' => 'password_new', 'on' => ['create']],			
			
            [['name','email', 'password_new', 'password_confirm'], 'safe'],
        ];
    	/*
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],

            ['role', 'default', 'value' => self::ROLE_USER],
            ['role', 'in', 'range' => [self::ROLE_USER]],
        ];
        */
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app/user', 'Name'),
            'email' => Yii::t('app/user', 'Email'),
            'type' => Yii::t('app/user', 'User type'),
            'change_password' => Yii::t('app/user', 'Change password'),
            'password_new' => Yii::t('app/user', 'New password'),
            'password_confirm' => Yii::t('app/user', 'Confirm the password'),
            'created_at' => Yii::t('app', 'Created at'),
            'updated_at' => Yii::t('app', 'Updated at'),
        ];
    }
    

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id , 'deleted_at' => null]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by name
     *
     * @param string $name
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'deleted_at' => null]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'deleted_at' => null
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotes()
    {
        return $this->hasMany(Votes::className(), ['user_id' => 'id']);
    }   
    
    public function afterFind() {
    	$this->type = User::getUserType()[$this->type];
    } 
}
