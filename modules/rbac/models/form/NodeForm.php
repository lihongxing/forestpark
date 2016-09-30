<?php

namespace app\modules\rbac\models\form;

use Yii;
use yii\base\Model;
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\Html;
use yii\rbac\Item;
use yii\rbac\Permission;
use yii\rbac\Role;

class NodeForm extends Model
{
    public $name;
    public $description;
    public $parent;
    public $itemTable = '{{%auth_item}}';
    public $url;

    public function rules(){
        return [
            [['name','parent'],'string','max'=>20],
            [['name','description','url'],'required','message'=>'name属性不能为空'],
            ['name','match','pattern'=>'/^[a-z][a-z-_]{2,20}$/','message'=>'name属性不合法'],
            ['parent','match','pattern'=>'/^[a-z-_][a-z-_]{2,20}$/','message'=>'parent属性不合法'],
            ['parent','validateParent'],
            ['description','filter','filter'=>function($value){
                return Html::encode($value);
            }],
        ];
    }

    public function validateParent($attribute,$params){
        if(!$this->hasErrors()){
            $authManager = Yii::$app->authManager;
            $node = $authManager->getPermission($this->parent);
            if(empty($node)){
                $this->addError($attribute,'上级节点不存在');
            }
        }
    }

    public function attributeLabels(){
        return [
            'name'=>'节点名称',
            'description'=>'节点描述',
            'parent'=>'父级节点',
            'url' => '菜单url'
        ];
    }

    public function save(){
        $validate = $this->validate();
        if($validate){
            $authManager = Yii::$app->authManager;
            $node = $authManager->createPermission($this->name);
            $node->description = $this->description;
            $node->data = array('url' => $this->url, 'leveal' => 1);
            $authManager->add($node);
            if(!empty($this->parent)){
                $parent = $authManager->getPermission($this->parent);
                $authManager->addChild($parent,$node);
            }
            return true;
        }else{
            print_r($this->getErrors());
            return false;
        }
    }

    public function update($name){
        if($this->validate()){
            $authManager = Yii::$app->authManager;
            $node = $authManager->getPermission($name);
            if(!$node) return false;
            $authManager->remove($node);
            $node = $authManager->createPermission($this->name);
            $node->description = $this->description;
            $authManager->add($node);
            if(!empty($this->parent)){
                $parent = $authManager->getPermission($this->parent);
                $authManager->addChild($parent,$node);
            }
            return true;
        }
        return false;
    }

    /**
     * 获取所有角色id
     * @param $type 类型
     * @return array
     */
    public function getPermissionsids($type)
    {
        $where = ['type' => $type];
        $search= '/';
        $ids  = (new Query())
            ->from($this->itemTable)
            ->select(['name'])
            ->where($where)
            ->andWhere(['not like', 'name', $search])
            ->all();
        return $ids;
    }


    public function getPermissions($type, $name = '', $time = '')
    {
        $where = ['type' => $type];
        if(!empty($name)){
           $where['name'] = $name;
        }
        $search= '/';
        $query = (new Query())
            ->from($this->itemTable)
            ->where($where)
            ->andWhere(['not like', 'name', $search]);
        if(!empty($time)){
            $query = $query->andWhere(['between','updated_at', strtotime($time['start']),strtotime($time['end'])]);
        }
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => '10']);
        $rolestmp = $query
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        foreach ($rolestmp as $row) {
            $roles[$row['name']] = $this->populateItem($row);
        }

        return array(
            'roles' => $roles,
            'pages' => $pages
        );
    }

    public function getPermissionsByLeveal($type)
    {
        $permissions = (new Query())
            ->from($this->itemTable)
            ->where(['type' => $type])
            ->all();
        $menuone = array();
        foreach($permissions as $key => $item){
            $data = unserialize($item['data']);
            if($data['leveal']==1){
                array_push($menuone, $item);
            }
        }
    }
    /**
     * Populates an auth item with the data fetched from database
     * @param array $row the data from the auth item table
     * @return Item the populated auth item instance (either Role or Permission)
     */
    protected function populateItem($row)
    {
        $class = $row['type'] == Item::TYPE_PERMISSION ? Permission::className() : Role::className();

        if (!isset($row['data']) || ($data = @unserialize($row['data'])) === false) {
            $data = null;
        }
        return new $class([
            'name' => $row['name'],
            'type' => $row['type'],
            'description' => $row['description'],
            'ruleName' => $row['rule_name'],
            'data' => $data,
            'createdAt' => $row['created_at'],
            'updatedAt' => $row['updated_at'],
        ]);
    }
}