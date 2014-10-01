<?php
/***************************************************************************
* A Video is a link to a web video / media player, or a web video API which
*  loads a media player, and belongs to an instance of Post.
****************************************************************************/
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Video extends ActiveRecord
{

    /**
     * @var VideoForm | NULL
     *  holds VideoForm instance for Video create/update
     *  @see VideoForm
     *  @see VideoEmbedParseBehavior
     */
    public $video_code;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%videos}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = [];

        if(isset(Yii::$app->params['isBackend']) && Yii::$app->params['isBackend']) {
            $behaviors['videoParse'] = [
                'class' => 'backend\components\VideoEmbedParseBehavior'
            ];
        }

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_id'], 'required'],
            [['post_id'], 'integer'],
            [['video_id'], 'required'],
            [['video_id'], 'string', 'max' => 128],
            [['title'], 'string', 'length' => [3, 128]]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'post_id'  => 'Post ID',
            'video_id' => 'Video Asset ID',
            'title'    => 'Video Title'
        ];
    }

    /**
     * relation to Post
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id'])->inverseOf('video');
    }

    /**
     * builds URL to Youtube embed
     */
    public function getYoutubeUrl()
    {

    }
}
