<?php
namespace ovidiupop\adminlte\widgets;
use yii\base\ErrorException;

/**
 * Alert widget renders a message from session flash. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 *  Simple message without title
 * ```php
 * \Yii::$app->session->setFlash('error', 'This is the message');
 * \Yii::$app->session->setFlash('success', 'This is the message');
 * \Yii::$app->session->setFlash('info', 'This is the message');
 * ```
 *
 * Message with title:
 *
 * ```php
 * \Yii::$app->session->setFlash('error', ['message'=>'This is the message', 'title'=>'This is title');
 * \Yii::$app->session->setFlash('info', ['message'=>'This is the message', 'title'=>'This is title');
 * \Yii::$app->session->setFlash('success', ['message'=>'This is the message', 'title'=>'This is title');
 *
 */

/**
 * Class Alert
 */
class Alert extends \yii\bootstrap\Widget
{

    public $alertTypes = [
        'danger' => [
            'class' => 'alert-danger',
            'icon' => 'fa-ban'
        ],
        'info' => [
            'class' => 'alert-info',
            'icon' => 'fa-info'
        ],
        'warning' => [
            'class' => 'alert-warning',
            'icon' => 'fa-exclamation-triangle'
        ],
        'success' => [
            'class' => 'alert-success',
            'icon' => 'fa-check'
        ],
        'light' => [
            'class' => 'alert-light',
        ],
        'dark' => [
            'class' => 'alert-dark'
        ]
    ];

    public $type;
    public $title;

    public $simple = false;

    /**
     * @var array the options for rendering the close button tag.
     */
    public $closeButton = [];

    /**
     * @throws \Exception
     */
    public function init()
    {
        parent::init();
        $session = \Yii::$app->session;
        $flashes = $session->getAllFlashes();

        foreach ($flashes as $type => $data) {
            $this->type = $type;
            if (!array_key_exists($type, $this->alertTypes)){
                $this->type = 'info';
            }

            if(is_string($data)){
                $message = $data;
                $this->title = false;
                $this->simple = true;
            }
            if(is_array($data)){
                $message = $data['message'];
                $this->title = $data['title'];
                $this->simple = false;
            }

            $head = '';

            if (!$this->simple) {
                $icon = $this->icon ?? $this->alertTypes[$this->type]['icon'] ?? null;
                $iconHtml = $icon ? '<i class="icon fas '.$icon.'"></i>' : '';
                $head = '<h5>'.$iconHtml.' '.$this->title.'</h5>';
            }

            echo \yii\bootstrap4\Alert::widget([
                'body' => $head.$message,
                'closeButton' => $this->closeButton,
                'options' => [
                    'id' => $this->getId().'-'.$this->type,
                    'class' => $this->alertTypes[$this->type]['class']
                ]
            ]);
            $session->removeFlash($type);
        }
    }
}
