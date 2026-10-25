<?php
App::uses('AppController', 'Controller');
/**
 * Oi040 Controller
 *
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 * @property SessionComponent $Session
 */
class Oi040sController extends AppController {
	public $layout = 'layout_host';
    public $helpers = array('Html','Form');
    public $uses = array('User','Kaisya','Ordertran','Shippermaster','Productdetailmaster','Loadingmaster','Deliverymaster','Carmaster','Truckkind','Processtran');

    public function beforeFilter(){
    	parent::beforeFilter();
    }

    public function isAuthorized($user){
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
        if ($this->request->is('post')) {
            $tyy1 = $this->data['selectym1']['year'];
            $tmm1 = $this->data['selectym1']['month'];
            $tdd1 = $this->data['selectym1']['day'];
            $shippermasters_id = $this->data['shippermasters_id'];
            $productdetailmasters_id = $this->data['productdetailmasters_id'];
        }else{
            $tyy1 = date('Y');
            $tmm1 = date('m');
            $tdd1 = date('d');
            $shippermasters_id = null;
            $productdetailmasters_id = null;
        }

        $paramsDate = $tyy1.'-'.$tmm1.'-'.$tdd1;
        $week = ['日','月','火','水','木','金','土'];

        if ($shippermasters_id == null && $productdetailmasters_id == null) {
            $this->set('truckkinds', $this->Truckkind->find('all'));
        } elseif ($shippermasters_id == null) {
            $this->set('truckkinds', $this->Truckkind->Select_productdetail($productdetailmasters_id));
        } elseif ($productdetailmasters_id == null) {
            $this->set('truckkinds', $this->Truckkind->Select_shipper($shippermasters_id));
        } else {
            $this->set('truckkinds', $this->Truckkind->Select_and($shippermasters_id,$productdetailmasters_id));
        }

        $shippermasters = $this->Shippermaster->find('list',[
            'fields' => ['id','shippername'],
        ]);
        $productdetailmasters = $this->Productdetailmaster->find('list',[
            'fields' => ['id','pdname'],
        ]);
        $this->set(compact('paramsDate','week','shippermasters','productdetailmasters'));

    }

}
