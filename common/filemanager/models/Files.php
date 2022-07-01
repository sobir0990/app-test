<?php

namespace common\filemanager\models;

use common\models\User;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\imagine\Image;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\FrameRate;
use FFMpeg\Coordinate\TimeCode;
use \FFMpeg\FFMpeg;
use yii\web\UploadedFile;

/**
 * .:[|]:.
 * This is the model class for table "files".
 *
 * @property int $id
 * @property string $title Название
 * @property string $description Описание
 * @property string $type Тип
 * @property string $file Файл
 * @property int $date_create Дата добавление
 * @property int $user_id Пользователь
 * @property User $user
 */
class Files extends \yii\db\ActiveRecord
{
    /**
     * @var string
     * Set param upload_dir_file with alias
     * Set param upload_dir_file_src with absolute
     */
    //public $upload_dir = "@frontend/web/uploads/";
    //public $upload_dir_src = "http://yoshlar.tv/uploads/";
    /**
     * @var array
     */
    public $file_data = [];
    /**
     * @var array
     */
    private $imgs_formats = [
        'jpg',
        'jpeg',
        'png',
        'bmp',
        'webp',
        'gif',
        'jpf'
    ];

    /**
     * @var array
     */
    private $videos_formats = ['mp4', 'mov', 'mkv', 'ogm', 'webm', 'wmv', 'flv'];

    public $runUpload = true;

    /**
     * @var array
     */
    public static $q = [
        /**
         * 424x240
         */
        240 => [
            'video_kbps' => '576',
            'audio_kbps' => '64',
            'height' => '424',
            'width' => '240',
            'q' => 240
        ],
        /**
         * 640x360
         */
        360 => [
            'video_kbps' => '896',
            'audio_kbps' => '64',
            'height' => '640',
            'width' => '360',
            'q' => 360
        ],
        /**
         * 848x480
         */
        480 => [
            'video_kbps' => '1216',
            'audio_kbps' => '64',
            'height' => '848',
            'width' => '480',
            'q' => 480
        ],
        /**
         * 1280x720
         */
        720 => [
            'video_kbps' => '2496',
            'audio_kbps' => '64',
            'height' => '1280',
            'width' => '720',
            'q' => 720
        ]
    ];

    /**
     * @var string use in ext file priority hight when usually
     */
    public $alternative_ext = null;
    /**
     * @var boolean
     */
    public $alternative_move_file = false;

    /**
     * @var int
     */
    public static $cron_reload_interval = 604800;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'files';
    }

    /**
     * @return mixed
     */
    public function getUpload_dir()
    {
        return Yii::$app->params['upload_dir_file'];
    }

    /**
     * @return mixed
     */
    public function getUpload_dir_src()
    {
        return Yii::$app->params['upload_dir_file_src'];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'date_filter' => [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['date_create'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description', 'file'], 'string'],
            [['title'], 'string', 'max' => 500],
            [['type'], 'string', 'max' => 255],
            [['file_data'], 'safe'],
            [
                ['user_id'],
                'default',
                'value' => Yii::$app->hasProperty("user") ? Yii::$app->user->identity->id : 1,
                'skipOnError' => true
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'File ID',
            'title' => 'Title',
            'description' => 'Description',
            'type' => 'Type',
            'file' => 'File',
            'date_create' => 'Date Create',
            'user_id' => 'User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCastingsfiles()
    {
        return $this->hasMany(Castingsfiles::className(), ['file_id' => 'file_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCastings()
    {
        return $this->hasMany(Castings::className(), ['casting_id' => 'casting_id'])->viaTable('castingsfiles',
            ['file_id' => 'file_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return FilesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FilesQuery(get_called_class());
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        parent::beforeSave($insert); // TODO: Change the autogenerated stub
        if ($this->runUpload) {
            $this->upload();
        }
        return true;
    }

    /**
     * @return bool
     */
    public function upload()
    {

        $file_hash = static::generateRandomName();

        $data_file = self::parse($file_hash);
        $folder = $data_file['folder'];
        $file_name = $data_file['file'];

        $this->title = $this->file_data->baseName;
        $this->description = $this->file_data->baseName;
        $this->type = $this->file_data->extension;
        $this->file = $file_hash;


        if ($this->validate()) {
            $dist = self::dist($this->upload_dir, $file_hash);
            $ext = $this->file_data->extension;
            $folder_dist = Yii::getAlias($this->getUpload_dir() . $folder);
            if (!is_dir($folder_dist)) {
                mkdir($folder_dist);
            }
            /**
             * Alternative ext
             */
            if ($this->alternative_ext !== null) {
                $ext = $this->alternative_ext;
            }
            /**
             * Alternative ext end
             */
            $origin = $dist . '.' . $ext;
            /**
             * Move file
             */

            if ($this->alternative_move_file) {
                copy($this->file_data->tempName, $origin);
            } else {
                $this->file_data->saveAs($origin);
            }
            /**
             * Move file end
             */
            //thumbs
            if (in_array($this->file_data->extension, $this->imgs_formats)) {
                $this->createThumbs($origin, $dist, $ext);
            }
            //#thumbs

            //video qualities
            /*if(in_array($this->file_data->extension,$this->videos_formats)){
                self::createQualityVideos($origin,$dist,$ext);
            }*/
            //#video qualities

            return true;
        } else {
            return false;
        }

    }

    /**
     * @return string
     */
    public function getSrc()
    {
        $dist = self::dist($this->upload_dir_src, $this->file);
        $origin = $dist . "." . $this->type;
        return $origin;
    }

    /**
     * @param null $thumb
     * @return string
     */
    public function src($thumb = null, $quality = 50)
    {
        $dist = self::dist($this->upload_dir, $this->file);
        $dist_src = self::dist($this->upload_dir_src, $this->file);
        $origin = $dist . "." . $this->type;
        $origin_src = $dist_src . "." . $this->type;
        if ($this->isImage) {
            $thumbs = $this->thumbs($dist, $this->type, $dist_src, $quality);
            if (array_key_exists($thumb, $thumbs)) {
                $link_thumb = $thumbs[$thumb]['src'];
                if (!file_exists($link_thumb)) {
                    $this->createThumbs($origin, $dist, $this->type);
                }
                return $link_thumb;
            }
        }
        if ($this->isVideo) {
            if ($thumb == null) {
                return $dist_src . ".mp4";
            }
            return $dist_src . "_" . $thumb . ".mp4";

        }
        return $origin_src;
    }

    /**
     * @return string
     */
    public function getFileSrc()
    {
        return $this->getSrc();
    }

    /**
     * @return string
     */
    public function getImageSrc()
    {
        return $this->getSrc();
    }

    /**
     * @return  void
     */
    public function afterDelete()
    {

        $dist = self::dist($this->upload_dir, $this->file);
        $ext = $this->type;
        $origin = $dist . "." . $ext;
        unlink($origin);
        //thumbs
        if (in_array($ext, $this->imgs_formats)) {
            $thumbs = $this->thumbs($dist, $ext);
            foreach ($thumbs as $thumb) {
                $d = $thumb['d'];
                @unlink($d);
            }
        }
        //#thumbs

        //videos
        if (in_array($ext, $this->videos_formats)) {
            $video_file = $dist . "_240.mp4";
            if (file_exists($video_file)) {
                @unlink($video_file);
            }
            $video_file = $dist . "_360.mp4";
            if (file_exists($video_file)) {
                @unlink($video_file);
            }
            $video_file = $dist . "_480.mp4";
            if (file_exists($video_file)) {
                @unlink($video_file);
            }
            $video_file = $dist . "_720.mp4";
            if (file_exists($video_file)) {
                @unlink($video_file);
            }
        }
        //#videos

    }

    /**
     * @param null $dist
     * @param null $extension
     * @param null $dist_src
     * @return mixed
     */
    public function thumbs($dist = null, $extension = null, $dist_src = null, $quality = 50)
    {
        $q['icon'] = [
            'w' => 50,
            'h' => 50,
            'd' => $dist . '_icon.' . $extension,
            'q' => $quality,
            'src' => $dist_src . '_icon.' . $extension
        ];
        $q['small'] = [
            'w' => 320,
            'h' => 320,
            'd' => $dist . '_small.' . $extension,
            'q' => $quality,
            'src' => $dist_src . '_small.' . $extension
        ];
        $q['low'] = [
            'w' => 640,
            'h' => 640,
            'd' => $dist . '_low.' . $extension,
            'q' => $quality,
            'src' => $dist_src . '_low.' . $extension
        ];
        $q['normal'] = [
            'w' => 1024,
            'h' => 1024,
            'd' => $dist . '_normal.' . $extension,
            'q' => $quality,
            'src' => $dist_src . '_normal.' . $extension
        ];
        return $q;
    }

    public function thumbnails($dist = null, $extension = null, $dist_src = null, $quality = 50)
    {
        $q['icon'] = [
            'src' => $dist_src . '_icon.' . $extension,
        ];
        $q['small'] = [
            'src' => $dist_src . '_small.' . $extension
        ];
        $q['low'] = [
            'src' => $dist_src . '_low.' . $extension
        ];
        $q['normal'] = [
            'src' => $dist_src . '_normal.' . $extension
        ];
        return $q;
    }

    public function getThumbnails() {
        if ($this->isImage) {
            $dist = self::dist($this->upload_dir, $this->file);
            $dist_src = self::dist($this->upload_dir_src, $this->file);
            $origin = $dist . "." . $this->type;
            $origin_src = $dist_src . "." . $this->type;
            $thumbs = $this->thumbnails($dist, $this->type, $dist_src);
            $this->createThumbs($origin, $dist, $this->type);
            return (object)$thumbs;

        }
    }

    /**
     * @return object
     */
    public function getThumbs()
    {
        if ($this->isImage) {
            $dist = self::dist($this->upload_dir, $this->file);
            $dist_src = self::dist($this->upload_dir_src, $this->file);
            $origin = $dist . "." . $this->type;
            $origin_src = $dist_src . "." . $this->type;
            $thumbs = $this->thumbs($dist, $this->type, $dist_src);
            $this->createThumbs($origin, $dist, $this->type);
            return (object)$thumbs;

        }
    }

    /**
     * @return bool
     */
    public function getIsImage()
    {
        if (in_array($this->type, $this->imgs_formats)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function getIsVideo()
    {
        if (in_array($this->type, $this->videos_formats)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $origin
     * @param $dist
     * @param $ext
     */
    public function createThumbs($origin, $dist, $ext)
    {
        $thumbs = $this->thumbs($dist, $ext);

        foreach ($thumbs as $thumb) {

            $w = $thumb['w'];
            $h = $thumb['h'];
            $d = $thumb['d'];
            $q = $thumb['q'];

            if (file_exists($d)) {
                continue;
            }
            if (file_exists(Yii::getAlias($origin)) && filesize(Yii::getAlias($origin)) > 0) {
                $img = Image::getImagine()->open(Yii::getAlias($origin));
                $size = $img->getSize();
                $ratio = $size->getWidth() / $size->getHeight();
                $width = $w;
                $height = round($width / $ratio);

                Image::thumbnail($origin, $width, $height)->save(Yii::getAlias($d), ['quality' => $q]);
            }
        }
    }

    /**
     * @param null $dist
     * @param null $output
     * @param int $q
     */
    public static function convert($dist = null, $output = null, $q = 480, $ext = null, $file_hash = "")
    {
        $qd = self::$q[$q];
        /**
         *
         */
        $wk = "frontend/web/uploads/logo.png";

        echo "start encoding: " . $q;
        if (in_array($ext, ['mp4', 'mov', 'mkv', 'ogm', 'webm', 'wmv', 'flv'])) {
            $video_240 = new \FFMpeg\Format\Video\X264('libmp3lame');
            $video_240->setAudioCodec('libmp3lame');
        } else {
            return false;
        }

        /**
         * Ffmpeg create
         */
        echo "Create ffmpeg";

        $ffmpeg = FFMpeg::create();
        //open
        $video = $ffmpeg->open($dist);
        //set: watermake,resize and filters
        $video->filters()->watermark($wk)->resize(new Dimension($qd['height'], $qd['width']))->synchronize();
        //create thumb image from 2 sec video
        //$video->frame(TimeCode::fromSeconds(2))->save(self::getUpload_dir().$file_hash.".jpg");
        //set kbsp
        $video_240->setKiloBitrate($qd['video_kbps']);
        //set audio kbsp
        $video_240->setAudioKiloBitrate($qd['audio_kbps']);

        $video_240->on('progress', function ($video, $format, $percentage) use ($file_hash, $q) {

            $file = Files::find()->where(['file' => $file_hash])->one();
            $params = $file->params;
            if (strlen($params) > 3) {
                $params = unserialize($params);
                $params[$q] = $percentage;
            } else {
                $params = [];
                $params[$q] = $percentage;
            }
            $file->updateAttributes(['params' => serialize($params)]);
            if (intval($percentage) == 99 || intval($percentage) == 100) {
                echo "converted end \n";
                $qq = self::$q;
                if ($q == end($qq)['q']) {
                    $file->updateAttributes(['converted' => 100]);
                }
            }
            echo $percentage;
            echo "\n";
        });

        $video->save($video_240, $output);
    }

    /**
     * @param $origin
     * @param $dist
     * @param $ext
     */
    public static function createQualityVideos($origin, $dist, $ext, $file_hash)
    {
        self::convert($origin, $dist . "_240.mp4", 240, $ext, $file_hash);
        self::convert($origin, $dist . "_360.mp4", 360, $ext, $file_hash);
        self::convert($origin, $dist . "_480.mp4", 480, $ext, $file_hash);
        self::convert($origin, $dist . "_720.mp4", 720, $ext, $file_hash);
    }

    /**
     * @param null $file_id
     * @return bool
     */
    public static function convertFileByID($file_id = null)
    {
        if (Files::find()->where(['file_id' => $file_id])->count() == 0) {
            return false;
        }
        $file = Files::findOne($file_id);
        $dist = self::dist(Files::getUpload_dir(), $file->file);
        $ext = $file->type;
        $origin = $dist . '.' . $ext;
        self::createQualityVideos($origin, $dist, $ext, $file->file);
    }

    public function convertFile()
    {
        self::convertFileByID($this->file_id);
    }

    /**
     * @return bool void
     */
    public static function cron()
    {
        $time = time();
        while (true) {
            //604800
            $now_forward = time() - $time;
            if ($now_forward > self::$cron_reload_interval) {
                return;
            }
            $query = Files::find()->andWhere([
                'in',
                'type',
                ['mp4', 'mov', 'mkv', 'ogm', 'webm', 'wmv', 'flv']
            ])->andWhere(['<>', 'converted', 100]);
            if ($query->count() == 0) {
                continue;
            }
            $files = $query->limit(10)->asArray()->all();
            foreach ($files as $file) {
                self::convertFileByID($file['file_id']);
            }
        }
    }

    /**
     * @param int $w
     * @param int $h
     * @return Files
     * Generate faker image
     */
    public static function generateFakeImage($w = 320, $h = 320, $category = null)
    {
        /**
         * Init
         */
        $model = new Files();
        $faker = \Faker\Factory::create();
        $file_name = static::generateRandomName();
        $file_name_r = $faker->text(50);

        /**
         * Create temp dir if not exsist
         */
        $tmp_dir = Yii::getAlias($model->upload_dir . "tmp/");
        if (!is_dir($tmp_dir)) {
            mkdir($tmp_dir);
        }

        /**
         *  get file from faker
         */
        $file_url = $faker->imageUrl($w, $h, $category);
        $file = file_get_contents($file_url);

        /**
         * put file in temp dir
         */
        $tmp_file = $tmp_dir . $file_name . ".jpg";
        file_put_contents($tmp_file, $file);


        /**
         * Upload file on server
         */

        $file_ins = new \yii\web\UploadedFile([
            'tempName' => $tmp_file,
            'name' => $file_name_r . ".jpg",
            'type' => 'image/jpeg',
            'size' => 20480,
            'error' => 0,
        ]);


        $model->alternative_ext = "jpg";
        $model->alternative_move_file = true;
        $model->file_data = $file_ins;
        $model->save();

        /**
         * delete tmp file
         */
        if (file_exists($tmp_file)) {
            unlink($tmp_file);
        }

        return $model;
    }

    /**
     * @return array
     */
    public function getConverterStatus()
    {
        $data = [];
        if (strlen($this->params) == 0) {
            foreach (self::$q as $q) {
                $data[$q['q']] = 0;
            }
            return $data;
        }
        $status = unserialize($this->params);
        if (!count($status)) {
            $data = [];
            if (strlen($this->params) == 0) {
                foreach (self::$q as $q) {
                    $data[$q['q']] = 0;
                }
                return $data;
            }
        }
        foreach ($status as $k => $s) {
            if ($s == 99 || $s == 100) {
                $data[$k] = 100;
            } else {
                $data[$k] = $s;
            }
        }
        return $data;
    }

    public static function no_photo()
    {
        return Files::findOne(Yii::$app->params['no_photo']);
    }

    /**
     * @return mixed
     */
    public static function no_photo_src()
    {
        return self::no_photo()->src;
    }

    /**
     * @param $dir
     * @param $filename
     * @return bool|string
     */
    public static function dist($dir, $filename)
    {
        $data = self::parse($filename);
        $file = $data['file'];
        $folder = $data['folder'];
        return Yii::getAlias($dir . $folder . "/" . $file);
    }

    /**
     * @param null $filename
     * @return array
     */
    public static function parse($filename = null)
    {
        $folder = mb_substr($filename, 0, 2);
        $file = mb_substr($filename, 2);
        return ['file' => $file, 'folder' => $folder];
    }

    public function fileSize()
    {
        $dist = self::dist($this->upload_dir, $this->file) . "." . $this->type;
        return filesize($dist);
    }

    public static function generateRandomName($length = 32)
    {
        $availableCharacters = array(
            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
            'G',
            'H',
            'I',
            'J',
            'K',
            'L',
            'M',
            'N',
            'O',
            'P',
            'Q',
            'R',
            'S',
            'T',
            'U',
            'V',
            'W',
            'X',
            'Y',
            'Z',
            'a',
            'b',
            'c',
            'd',
            'e',
            'f',
            'g',
            'h',
            'i',
            'j',
            'k',
            'l',
            'm',
            'n',
            'o',
            'p',
            'q',
            'r',
            's',
            't',
            'u',
            'v',
            'w',
            'x',
            'y',
            'z',
            '0',
            '1',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7',
            '8',
            '9',
        );

        $random_name = "";

        for ($i = 0; $i < $length; $i++) {
            $random_name .= $availableCharacters[rand(0, (sizeof($availableCharacters) - 1))];
        }

        return $random_name;
    }

    public function fields()
    {
        return array(
            'file_id',
            'title',
            'description' => function($model) {
                return "";
            },
            'type',
            'params',
            'converted',
            'path' => 'src',
            'thumbnails'
        );
    }

}
