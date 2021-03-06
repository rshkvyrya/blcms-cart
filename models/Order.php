<?php
namespace bl\cms\cart\models;

use bl\cms\cart\common\components\user\models\Profile;
use bl\cms\cart\common\components\user\models\UserAddress;
use bl\cms\payment\common\entities\PaymentMethod;
use dektrium\user\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "shop_order".
 *
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $status
 * @property integer $address_id
 * @property integer $delivery_id
 * @property integer $uid
 * @property integer $payment_method_id
 * @property string $user_comment
 * @property integer $creation_time
 * @property integer $update_time
 * @property string $delivery_number
 * @property string $delivery_post_office
 * @property string $confirmation_time
 * @property string $invoice
 * @property float $cost
 * @property float $total_cost
 *
 * @property User $user
 * @property UserAddress $address
 * @property DeliveryMethod $deliveryMethod
 * @property PaymentMethod $paymentMethod
 * @property OrderStatus $orderStatus
 * @property OrderProduct[] $orderProducts
 * @property int $orderProductsCount
 */
class Order extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_order';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['creation_time', 'update_time'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_time'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_comment'], 'safe'],
            [['user_id'], 'required'],
            [['user_id', 'status', 'address_id', 'uid', 'payment_method_id'], 'integer'],
            [['cost', 'total_cost'], 'double'],
            [['delivery_post_office', 'delivery_number', 'invoice'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['address_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserAddress::className(), 'targetAttribute' => ['address_id' => 'id']],
            [['status'], 'exist', 'skipOnError' => true, 'targetClass' => OrderStatus::className(), 'targetAttribute' => ['status' => 'id']],
            [['delivery_id'], 'exist', 'skipOnError' => true, 'targetClass' => DeliveryMethod::className(), 'targetAttribute' => ['delivery_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'status' => Yii::t('cart', 'Status'),
            'delivery_post_office' => Yii::t('cart', 'Post office'),
            'uid' => Yii::t('cart', 'Order number'),
            'invoice' => Yii::t('cart', 'Invoice'),
            'delivery_number' => Yii::t('cart', 'Delivery number'),
            'user_comment' => Yii::t('cart', 'User comment'),
            'orderProductsCount' => Yii::t('cart', 'Product count'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasOne(UserAddress::className(), ['id' => 'address_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderStatus()
    {
        return $this->hasOne(OrderStatus::className(), ['id' => 'status']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderProducts()
    {
        return $this->hasMany(OrderProduct::className(), ['order_id' => 'id']);
    }

    /**
     * @return int
     */
    public function getOrderProductsCount()
    {
        return $this->hasMany(OrderProduct::className(), ['order_id' => 'id'])->count();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryMethod()
    {
        return $this->hasOne(DeliveryMethod::className(), ['id' => 'delivery_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethod()
    {
        return $this->hasOne(PaymentMethod::className(), ['id' => 'payment_method_id']);
    }

}