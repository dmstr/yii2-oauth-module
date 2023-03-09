<?php

namespace dmstr\oauth\modules\admin\models\search;

use dmstr\oauth\modules\admin\models\Client as ClientModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Client represents the model behind the search form about `dmstr\oauth\modules\admin\models\Client`.
 */
class Client extends ClientModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'id',
                    'name',
                    'access_token_user_id'
                ],
                'safe'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ClientModel::find();
        $query->joinWith('accessTokenUser');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query
            ->andFilterWhere([
                'OR',
                ['ILIKE', 'username', $this->access_token_user_id],
                ['ILIKE', 'email', $this->access_token_user_id]
            ])
            ->andFilterWhere(['ILIKE', 'id', $this->id])
            ->andFilterWhere(['ILIKE', 'name', $this->name]);

        return $dataProvider;
    }
}
