<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%sqlbackstore}}".
 *
 * @property integer $id
 * @property string $sql_name
 * @property string $sql_content
 * @property integer $sql_addtime
 * @property string $sql_size
 */
class Sqlbackstore extends \yii\db\ActiveRecord
{
    public $_path = null;

    protected function getPath()
    {
        if (isset ($this->_path)) {

        } else {
            $this->_path = Yii::$app->basePath . '/_backup/';
        }
        if (!file_exists($this->_path)) {
            mkdir($this->_path);
            chmod($this->_path, '777');
        }
        return $this->_path;
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sqlbackstore}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sql_name', 'sql_content', 'sql_addtime', 'sql_size'], 'required'],
            [['sql_addtime'], 'integer'],
            [['sql_size'], 'number'],
            [['sql_name'], 'string', 'max' => 128],
            [['sql_content'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sql_name' => '数据库备份的名称',
            'sql_content' => '数据库备份的表名称集合',
            'sql_addtime' => '数据库备份的时间',
            'sql_size' => '数据库备份的文件大小',
        ];
    }




    /**
     * @method数据库备份表添加方法
     * @param $columns
     * @return array
     */
    public function create($columns)
    {
        if (!empty($columns)) {
            foreach ($columns as $key => $item) {
                $this->setAttribute($key, $item);
            }
            if ($this->save()) {
                return array(true, 'function create sql success');
            } else {
                return array(false, 'function create sql error');
            }
        } else {
            return array(true, 'function create error params is null');
        }
    }

    /**
     * @method 数据库备份的删除
     * @param $where
     * @return array
     */
    public function delByWhere($data)
    {
        if (!empty($data)) {
            $condition = '';
            $params = array();
            foreach ($data as $key => $item) {
                $condition = $condition . "$key = :$key";
                $params[':' . $key] = $item;
            }
            $sqlbackstore = $this->find()->where($condition, $params)->one();
            if (isset($sqlbackstore->sql_name)) {
                $sqlFile = $this-> path . basename($sqlbackstore->sql_name);
                if (!file_exists($sqlFile)){
                    return array(false, 'function deleteByWhere file is not exit error');
                }
            } else {
                return array(false, 'function deleteByWhere file is not set error');
            }
            $count = $this->deleteAll($condition, $params);
            if ($count > 0) {
                unlink($sqlFile);
                return array(true, 'function deleteByWhere sql success');
            } else {
                return array(false, 'function deleteByWhere sql error');
            }
        } else {
            return array(false, 'function deleteByWhere params is null error');
        }
    }
}
