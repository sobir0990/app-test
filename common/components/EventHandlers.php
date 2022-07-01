<?php

namespace common\components;

use Yii;
use yii\base\Exception;
use yii\caching\TagDependency;
use common\models\SystemMessage;
use yii\i18n\MissingTranslationEvent;

class EventHandlers
{
    protected static $messages = null;

    const GLOBAL_TRANSLATION = 'GLOBAL_TRANSLATION';

    public static function handleMissingTranslation(MissingTranslationEvent $event)
    {
        $event->translatedMessage = $event->message;

        if (self::$messages == null)
        {
            $result = Yii::$app->cache->get(self::GLOBAL_TRANSLATION);

            if ($result === false)
            {
                $result = array();
                $data   = SystemMessage::find()->all();

                foreach ($data as $message)
                {
                    if (!isset($result[$message->category]))
                    {
                        $result[$message->category] = [];
                    }

                    $result[$message->category][$message->message] = 1;
                }

                Yii::$app->cache->set(self::GLOBAL_TRANSLATION, $result, null, new TagDependency(['tags' => SystemMessage::CACHE_TAG]));
            }

            self::$messages = $result;
        }

        if (!isset(self::$messages[$event->category]) || !isset(self::$messages[$event->category][$event->message]))
        {
            $source           = new SystemMessage();
            $source->message  = $event->message;
            $source->category = $event->category;

            try {
                $source->save(false);
                self::$messages[$event->category][$event->message] = 1;
            } catch (Exception $e) {
                Yii::warning($e->getMessage());
            }
        }
    }
}