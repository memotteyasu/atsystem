<?php
App::uses('AppController', 'Controller');
/**
 * Oi050 Controller
 *
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class Oi060sController extends AppController {
	public $layout = 'layout_host';
    public $helpers = array('Html','Form');
    public $uses = array('User','Kaisya','Ordertran','Shippermaster','Productdetailmaster','Loadingmaster','Deliverymaster','Carmaster','Truckkind','Processtran','Workdetailmaster');

    public function beforeFilter(){
    	parent::beforeFilter();
    }

    public function isAuthorized($user){
        // 管理者(10-)事務職(20-)
        if(isset($user['rolemasters_id']) && $user['rolemasters_id'] >= '10' && $user['rolemasters_id'] <= '29'){
    		$this->set('user',$user);
    		return true;
    	}
    	return false;
    	break;
    }
/**
 * index method
 *
 * @return void
 */
    public function index() {
    	$shippermasters = $this->Shippermaster->find('list',[
    		'fields' => ['id','shippershortname'],
    		'conditions' => ['departmentmasters_id' => '1']
    	]);
    	$correctflgs = [0 => '未登録', 1 => '登録済'];
    	$this->set(compact('shippermasters','correctflgs'));

    	//debug($this->data);

    	if ($this->request->is('post')) {
    		$tyy1 = $this->data['selectym1']['year'];
    		$tmm1 = $this->data['selectym1']['month'];
    		$tdd1 = $this->data['selectym1']['day'];
    		$shippermasters_id = $this->data['shippermasters_id'];
    		$correctflg = $this->data['correctflg'];
    	}else{
    		$tyy1 = date('Y');
    		$tmm1 = date('m');
    		$tdd1 = date('d');
    		if (!$this->params['pass']) {
    			$shippermasters_id = null;
    			$correctflg = null;
    		} else {
    			$shippermasters_id = $this->params['pass'][0];
    			$correctflg = $this->params['pass'][1];
    		}
    	}

    	$date = new DateTime($tyy1.'-'.$tmm1.'-'.$tdd1);
    	$paramsDate = $date->format('Y-m-d');

    	if ($shippermasters_id == null && $correctflg == null) {
    		//$this->set('ordertrans', $this->Ordertran->SelectOrdertran($paramsDate));
    		$this->set('ordertrans', []);
    	} elseif ($shippermasters_id == null) {
    		$this->set('ordertrans', $this->Ordertran->SelectOrdertranFlg($paramsDate,$correctflg));
    	} elseif ($correctflg == null) {
    		$this->set('ordertrans', $this->Ordertran->SelectOrdertranShipper($paramsDate,$shippermasters_id));
    	} else {
    		$this->set('ordertrans', $this->Ordertran->SelectOrdertranAll($paramsDate,$correctflg,$shippermasters_id));
    	}
    }

    public function edit($id = null) {
    	$post = $this->Ordertran->findById($id);
    	if (!$post) {
    		throw new NotFoundException(__('Invalid post'));
    	}
    	$sid = $post['Ordertran']['shippermasters_id'];
    	$pid = $post['Ordertran']['productdetailmasters_id'];
    	$flg = $post['Ordertran']['correctflg'];
    	//debug($post);

    	$shippermasters = $this->Shippermaster->find('list', [
   			'fields' => ['Shippermaster.id','Shippermaster.shippername'],
    	]);
    	$productdetailmasters = $this->Productdetailmaster->find('list', [
   			'fields' => ['Productdetailmaster.id','Productdetailmaster.pdname'],
   			'conditions' => ['Productdetailmaster.shippermasters_id' => $post['Ordertran']['shippermasters_id']]
    	]);
    	$loadingmasters = $this->Loadingmaster->find('list',[
   			'fields' => ['Loadingmaster.id','Loadingmaster.loadingname'],
   			'order' => ['Loadingmaster.loadingnamekana' => 'asc']
    	]);
    	$deliverymasters = $this->Deliverymaster->find('list',[
   			'fields' => ['Deliverymaster.id','Deliverymaster.deliveryname'],
   			'order' => ['Deliverymaster.deliverynamekana' => 'asc']
    	]);
    	$truckkind2s = $this->Truckkind->find('list', [
   			'fields' => ['Truckkind.id', 'Truckkind.carmasters_id']
    	]);
    	$this->set(compact('shippermasters','productdetailmasters','loadingmasters','deliverymasters','truckkind2s'));

    	if ($this->request->is(array('post', 'put'))) {
    		$this->Ordertran->id = $id;
    		$this->request->data['Ordertran']['kaisyas_id'] = $this->Auth->user('kaisyas_id');
    		$this->request->data['Ordertran']['carmasters_id'] = $truckkind2s[$this->request->data['Ordertran']['truckkinds_id']];
    		$this->request->data['Ordertran']['loadingdate'] = $this->request->data['Ordertran']['deliverydate'];
    		$this->request->data['Ordertran']['loadingtime'] = $this->request->data['Ordertran']['deliverytime'];
    		$this->request->data['Ordertran']['correctdate'] = $this->request->data['Ordertran']['cdate'].' '.$this->request->data['Ordertran']['ctime'];
    		if ($this->request->data['Ordertran']['correctdate']) {
    			$this->request->data['Ordertran']['correctflg'] = 1;
    		} else {
    			$this->request->data['Ordertran']['correctflg'] = 0;
    		}
    		if ($this->Ordertran->save($this->request->data)) {
    			$this->Flash->success('保存しました。');
    			return $this->redirect(['controller' => 'Oi060s', 'action' => 'index', $sid, $flg]);
    		}
    		//$this->Flash->error('失敗しました。');
    	}

    	if (!$this->request->data) {
    		$this->request->data = $post;
    	}

    	$this->Truckkind->virtualFields = ['name' => 'concat(Carmaster.plateno ," " ,Productdetailmaster.pdname, "/", Shippermaster.shippername)'];
    	$truckkinds = $this->Truckkind->find('list', [
    		'fields' => ['Truckkind.id', 'name'],
    		'conditions' => ['Truckkind.productdetailmasters_id' => $post['Ordertran']['productdetailmasters_id']],
    		'recursive' => 0,
    	]);
    	$this->set(compact('truckkinds'));
    }

    public function delete($id = null, $oid = null) {
        $this->Processtran->id = $id;
        if ($this->request->is('get')){
            throw new MethodNotAllowedException();
        }else{
            $this->request->data['Processtran']['delflg'] = 1;
            $this->Processtran->id = $id;
            $this->request->data['Processtran']['ordertrans_id'] = $oid;
            if ($this->Processtran->save($this->request->data,false,array('delflg'))){
                $this->Flash->success(__('削除しました'));
            }else{
                $this->Flash->error(__('失敗しました'));
            }
        }
        return $this->redirect(['controller' => 'Oi050s','action' => 'add',$oid]);
    }

}
