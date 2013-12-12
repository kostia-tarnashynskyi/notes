<?php

class NoteController extends Controller
{

	public function actionIndex()
	{
		$criteria = new CDbCriteria;

		$dataProvider = new CActiveDataProvider('Note', array(
			'criteria'=>$criteria,
			'pagination'=>array('pageSize'=>10),
			
		));

		$this->render('index', array('dataProvider'=>$dataProvider));
	}

	public function actionView($id)
	{
		$this->render('view', array('model'=>$this->loadModel($id)));
	}

	public function loadModel($id)
	{
		$model = Note::model()->findByPk($id);

		if($model == null)
		{
			
			throw new CHttpException(404, 'no page');
		}

		return $model;
	}

	public function actionCreate()
	{
		$model = new Note('create');
		//$model->scenario='create';

		if(isset($_POST['Note']))
		{
			$model->attributes = $_POST['Note'];
			if($model->save())
			{
				$this->redirect(array('view', 'id'=>$model->id));
			}
		}
		
		$this->render('create', array('model'=>$model));
	}

	public function actionUpdate($id)
	{

		$model = Note::model()->findByPk($id);

		if(isset($_POST['Note']))
		{
			$model->attributes = $_POST['Note'];
			if($model->save())
			{
				Yii::app()->user->setFlash('update','Страница обновлена!');
				$this->redirect(array('view', 'id'=>$model->id));
			}
		}
		
		$this->render('create', array('model'=>$this->loadModel($id)));
	}

	public function actionDelete($id)
	{
		$model = $this->loadModel($id);

		$result = $model->delete();
		
		if($result > 0)
		{
			Yii::app()->user->setFlash('delete','Страница <b>'.$model->title.'</b> удалена!');
		}

		$criteria = new CDbCriteria;

		$dataProvider = new CActiveDataProvider('Note', array(
			'criteria'=>$criteria,
			'pagination'=>array('pageSize'=>10),
			
		));

		$this->render('index', array('dataProvider'=>$dataProvider));	
	}

	public function actionSearch()
	{
		if(isset($_POST['Note']))
		{
			//$model = new Note('mySearch');
			//$model->attributes = $_POST['Note'];
			//print_r($model->attributes);
			//$model->id = $_POST['Note']['id'];
			//$model->title = $_POST['Note']['title'];


			$criteria = new CDbCriteria;
			$criteria->compare('id', $_POST['Note']['id']);
			$criteria->compare('title', $_POST['Note']['title']);
			$criteria->compare('author_id', $_POST['Note']['author_id']);

		    $dataProvider = new CActiveDataProvider('Note', array('criteria'=>$criteria));
			//$dataProvider->model->scenario = 'mySearch';
			$dataProvider->model->attributes = $_POST['Note'];

		    $this->render('index', array('dataProvider'=>$dataProvider));
		}
		else if(isset($_GET['author']))
		{
			$criteria=new CDbCriteria;
			$criteria->condition='author_id = :author';
			$criteria->params=array(':author'=>(int)$_GET['author']);
			$dataProvider = new CActiveDataProvider('Note', array('criteria'=>$criteria));

			$this->render('index', array('dataProvider'=>$dataProvider));
		}
		
	}


}