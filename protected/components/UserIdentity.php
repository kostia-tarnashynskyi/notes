<?php
class UserIdentity extends CUserIdentity {
    // Будем хранить id.
    protected $_id;
 
    // Данный метод вызывается один раз при аутентификации пользователя.
    public function authenticate(){
        // Производим стандартную аутентификацию, описанную в руководстве.
        $author = Author::model()->find('LOWER(login)=?', array(strtolower($this->username)));
        if(($author===null) || ($this->password !== $author->password)) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else {
            // В качестве идентификатора будем использовать id, а не username,
            // как это определено по умолчанию. Обязательно нужно переопределить
            // метод getId(см. ниже).
            $this->_id = $author->id;
 
            // Далее логин нам не понадобится, зато имя может пригодится
            // в самом приложении. Используется как Yii::app()->user->name.
            // realName есть в нашей модели. У вас это может быть name, firstName
            // или что-либо ещё.
            $this->username = $author->login;
 
            $this->errorCode = self::ERROR_NONE;
        }
       return !$this->errorCode;
    }
 
    public function getId(){
        return $this->_id;
    }
}