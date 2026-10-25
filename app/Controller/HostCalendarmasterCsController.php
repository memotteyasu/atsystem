<?php
App::uses('AppController', 'Controller');
/**
 * HostCalendarmasters Controller
 *
 */
class HostCalendarmasterCsController extends AppController {
    public $layout = 'layout_host';
    public $helpers = array('Html','Form');
    public $uses = ['Calendarmaster','Calendarusermaster','User'];
    public $components = array('Search.Prg');
    public $presetVars = array();

    public function beforeFilter(){
        parent::beforeFilter();
        //$this->set('auth',$this->Auth);
    }

    public function isAuthorized($user){
        if(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= '10' && $user['rolemasters_id'] <= '19'){
            $this->set('user',$user);
            return true;
        }
        return false;
        break;
    }


    public function calendaruser($id = null){
        $this->layout = 'layout_host';

        if ($this->request->is('post')) {
            if ($this->request->data('selectym1')) {
                $tyy1 = $this->request->data['selectym1']['year'];
                $tmm1 = $this->request->data['selectym1']['month'];
            } else {
                $tyy1 = date('Y');
                $tmm1 = date('m');
            }
        } else {
            if ($this->Session->read('paramsDate')) {
                $sessiondate = new DateTime($this->Session->read('paramsDate'));
                $tyy1 = $sessiondate->format('Y');
                $tmm1 = $sessiondate->format('m');
                $this->Session->delete('paramsDate');
            } else {
                $tyy1 = date('Y');
                $tmm1 = date('m');
            }
        }
        $paramsDate = $tyy1.'-'.$tmm1.'-01';

        $array = [
            date("Y/m/d", mktime(0, 0, 0, $tmm1+0, 1, $tyy1)),
            date("Y/m/d", mktime(0, 0, 0, $tmm1+1, 0, $tyy1))
        ];

        /*
         * カレンダーマスタ読み込み
         */
        $calendarusermasters = $this->Calendarusermaster->find('first', [
            'conditions' => [
                'Calendarusermaster.calendartype' => 3,
                'Calendarusermaster.month >=' => $array[0],
                'Calendarusermaster.month <=' => $array[1],
                'Calendarusermaster.kaisyas_id' => $this->kaisyas_id
            ],
            'recursive' => -1,
        ]);

        $tsta = $array[0];
        $tend = $array[1];

        while (strcmp(date('N', strtotime($tsta)), "7")) {
            $tsta = date("Y/m/d", strtotime($tsta." -1 day"));
        }
        while (strcmp(date('N', strtotime($tend)), "6")) {
            $tend = date("Y/m/d", strtotime($tend." +1 day"));
        }

        $calendar = array();
        $week = array();
        $header = array();
        $temp = $tsta;
        $weekja = [
            '日', //0
            '月', //1
            '火', //2
            '水', //3
            '木', //4
            '金', //5
            '土', //6
        ];
        while (1 == 1) {
            if (count($week) == 7) {
                array_push($calendar, $week);
                if (count($header) != 7) {
                    foreach ($week as $key=>$value) {
                        array_push($header, $weekja[date("w", strtotime($value))]);
                    }
                }
                $week = array();
            }
            array_push($week, $temp);
            if (strtotime($tend) == strtotime($temp)) {
                array_push($calendar, $week);
                break;
            }
            $temp = date("Y/m/d", strtotime($temp." +1 day"));
        };

        $url = Router::url(['controller' => 'HostCalendarmasterCs', 'action' => 'calendaruseradd']);

        if ($calendarusermasters) {
            $baseday = $calendarusermasters['Calendarusermaster']['baseday'];

            foreach ($calendar as $key=>$value) {
                foreach ($value as $key2=>$value2) {
                    $conditionDate = new DateTime($value2);
                    $j = $conditionDate->format('d');

                    $restflg = intval($calendarusermasters['Calendarusermaster']['day'.$j]);

                    $selected0 = $selected1 = $selected2 = null;
                    if ($restflg == 0) {
                        $selected0 = ' selected';
                    } elseif ($restflg == 1) {
                        $selected1 = ' selected';
                    } elseif ($restflg == 2) {
                        $selected2 = ' selected';
                    }

                    $calendar[$key][$key2]=intval(date("m", strtotime($value2)))!=intval($tmm1)?"&nbsp;":date("d", strtotime($value2)).
                    '<form action="'.$url.'" method="post" onchange="submit(this.form)">'.
                    '<input type="hidden" name="id" value="'.$calendarusermasters['Calendarusermaster']['id'].'">'.
                    '<input type="hidden" name="users_id" value="'.$id.'">'.
                    '<input type="hidden" name="restdate" value="'.$conditionDate->format('Y-m-d').'">'.
                    '<select name="day'.$j.'" data-rel="chosen"><option value=0'.$selected0.'>通常</option><option value=1'.$selected1.'>法定外休日</option><option value=2'.$selected2.'>法定休日</option></select>'.
                    '</form>';
                }
            }
            $flg = true;
        } else {
            $this->Flash->error(__('個人のカレンダーがまだ作成されていません。'));

            $baseday = null;
            foreach ($calendar as $key=>$value) {
                foreach ($value as $key2=>$value2) {
                    $calendar[$key][$key2]=intval(date("m", strtotime($value2)))!=intval($tmm1)?"&nbsp;":date("d", strtotime($value2));
                }
            }
            $flg = false;
        }

        $this->set(compact('calendar','header','paramsDate','baseday','flg'));
    }

    public function calendaruseradd($id = null){
        if ($this->request->is('post')) {
            $date = new DateTime($this->request->data('restdate'));
            $i = $date->format('d');
            $users_id = $this->request->data('users_id');
            $this->request->data['Calendarusermaster']['id'] = $this->request->data('id');
            $this->request->data['Calendarusermaster']['day'.$i] = $this->request->data('day'.$i);

            $calendarusermaster = $this->Calendarusermaster->find('first', [
                'fields' => [
                    'Calendarusermaster.day'.$i,
                    'Calendarusermaster.baseday',
                ],
                'conditions' => [
                    'Calendarusermaster.id' => $this->request->data('id'),
                    'Calendarusermaster.kaisyas_id'=>$this->kaisyas_id
                ],
                'recursive' => -1,
            ]);

            $restflg_before = $calendarusermaster['Calendarusermaster']['day'.$i];
            $restflg_after = $this->request->data('Calendarusermaster.day'.$i);
            $baseday = $calendarusermaster['Calendarusermaster']['baseday'];

            if ($restflg_before != $restflg_after) {
                switch ($restflg_before) {
                    case 0:
                        if ($restflg_after == 1 || $restflg_after == 2) {
                            $baseday = intval($baseday - 1);
                        }
                        break;
                    case 1:
                    case 2:
                        if ($restflg_after == 0) {
                            $baseday = intval($baseday + 1);
                        }
                        break;
                }
            }
            $this->request->data['Calendarusermaster']['baseday'] = $baseday;

            if ($this->Calendarusermaster->save($this->request->data['Calendarusermaster'])) {
                $this->Session->write('paramsDate',$this->request->data('restdate'));
                $this->Flash->success('変更を保存しました。');
            } else {
                $this->Flash->error('変更の保存に失敗しました。');
            }
            return $this->redirect(['action' => 'calendaruser']);
        }
    }


}
