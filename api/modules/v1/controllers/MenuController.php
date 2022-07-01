<?php

namespace epa\v1\controllers;

use api\components\ApiController;
use common\modules\menu\models\MainMenu;
use common\modules\menu\models\Menu;
use common\modules\menu\models\MenuSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\rest\IndexAction;
use oks\langs\components\Lang;

class MenuController extends ApiController
{
    public $modelClass = Menu::class;
    public $searchModelClass = MenuSearch::class;

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['index']);
        return ArrayHelper::merge($actions, [
            'index' => [
                'class' => IndexAction::class,
                'modelClass' => MainMenu::class,
                'prepareDataProvider' => [$this, 'prepareEventDataProvider']
            ],
        ]);
    }

    /**
     * @return ActiveDataProvider
     */
    public function prepareEventDataProvider()
    {
        $slug = \Yii::$app->request->getQueryParams()['slug'];

        $main_menu = MainMenu::find()
            ->andWhere([
                'slug' => $slug,
                'lang' => Lang::getLangId()
            ])->one();
        $menu = Menu::find()->andWhere(['main_menu' => $main_menu->id])->andWhere(['parent_id' => null]);

        $menu->andWhere(['lang' => \oks\langs\components\Lang::getLangId()]);
        return new ActiveDataProvider([
            'query' => $menu
        ]);
    }

    /**
     * @return array|null|\yii\db\ActiveRecord
     */
    public function actionBySlug()
    {
        $slug = \Yii::$app->request->getQueryParams('slug');
        if (empty($slug)) {
            $slug = \Yii::$app->request->getQueryParams('filter')['slug'];
        }
        $menu = MainMenu::find()->andWhere(['slug' => $slug])->andWhere(['lang' => Lang::getLangId()])->one();

        return $menu;
    }
}
