<?php

namespace app\security\model;

class User extends \yii\base\Object implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $name;
    public $email;
    public $phone;
    public $active;
    public $roles;
    public $authKey;
    public $accessToken;

    /**
     * @inheritdoc
     */
     public static function findIdentity($id)
     {
        $command = \Yii::$app->db->createCommand("select * from user u " .
          "inner join profile p on p.user_id = u.id " .
          "where u.id = :id")
            ->bindParam(":id", $id);

        $result = $command->queryOne();

        return buildUserFromResult($result);
     }
 
     /**
      * @inheritdoc
      */
     public static function findIdentityByAccessToken($token, $type = null)
     {
         // Not Implemented
         return null;
     }
 
     /**
      * Finds user by username
      *
      * @param string $username
      * @return static|null
      */
     public static function findByUsername($username)
     {
        $command = Yii::$app->db->createCommand("select * from user u " .
        "inner join profile p on p.user_id = u.id " .
        "where u.id = :id")
          ->bindParam(":id", $id);

      $result = $command->queryOne();

      return buildUserFromResult($result);

    }
 
     private static function findRolesByUserName($user)
     {
         $command = Yii::$app->db->createCommand("select r.name " . 
            " from user_role ur inner join role r on ur.role_id = r.id" . 
            "   inner join user u on ur.user_id = u.id" . 
            " where u.user = :user and r.active = 1")
                ->bindParam(":user", $user);

         $result = $command->queryAll();

         return $result;
     }

     private static function buildUserFromResult($result)
     {
         if (isset($result))
         {
            $user = new User();
            $user->id = $result["id"];
            $user->username = $result["user"];
            $user->password = $result["password"];
            $user->name = $result["name"];
            $user->phone = $result["phone"];
            $user->email = $result["email"];
            $user->active = $result["active"];

            $user->roles = findRolesByUserName($user->username);

            return $user;
         } 

         return null;
     }

     /**
      * @inheritdoc
      */
     public function getId()
     {
         return $this->id;
     }
 
     /**
      * @inheritdoc
      */
     public function getAuthKey()
     {
         return $this->authKey;
     }
 
     /**
      * @inheritdoc
      */
     public function validateAuthKey($authKey)
     {
         return $this->authKey === $authKey;
     }
 
     /**
      * Validates password
      *
      * @param string $password password to validate
      * @return bool if password provided is valid for current user
      */
     public function validatePassword($password)
     {
         return $this->password === $password;
     }
     
}

