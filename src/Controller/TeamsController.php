<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Teams Controller
 *
 * @property \App\Model\Table\TeamsTable $Teams
 *
 * @method \App\Model\Entity\Team[] paginate($object = null, array $settings = [])
 */
class TeamsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        //Check if its admin or not
        //In our DB the id for the admin user is 11 ,so we will check that only
        //admin user --  (username= imadmin , password = admin)
        $ExtraInfo=$this->loadComponent('Extra_info');
        $current_user=$ExtraInfo->getCurrentUser($this);
        if($current_user==11){
            $this->paginate = [
                'contain' => ['Leaders', 'Projects','Users']
            ];
            $teams = $this->paginate($this->Teams);
            $this->set(compact('teams'));
            $this->set('_serialize', ['teams']);
        }
        //If its not admin user than redirect him
        else{
            $this->redirect(['controller'=>'Dashboard','action'=>'index']);
        }
    }

    /**
     * View method
     *
     * @param string|null $id Team id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        //Check if its admin or not
        //In our DB the id for the admin user is 11 ,so we will check that only
        //admin user --  (username= imadmin , password = admin)
        $ExtraInfo=$this->loadComponent('Extra_info');
        $current_user=$ExtraInfo->getCurrentUser($this);
        if($current_user==11){
            $team = $this->Teams->get($id, [
                'contain' => ['Leaders', 'Projects', 'Users']
            ]);
            $this->set('team', $team);
            $this->set('_serialize', ['team']);
        }
        //If its not admin user than redirect him
        else{
            $this->redirect(['controller'=>'Dashboard','action'=>'index']);
        }
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        //Check if its admin or not
        //In our DB the id for the admin user is 11 ,so we will check that only
        //admin user --  (username= imadmin , password = admin)
        $ExtraInfo=$this->loadComponent('Extra_info');
        $current_user=$ExtraInfo->getCurrentUser($this);
        if($current_user==11){
            $ExtraInfo=$this->loadComponent('Extra_info');
            $current_user=$ExtraInfo->getCurrentUser($this);
            $currentUserType=$ExtraInfo->getCurrentType($this);
            $team = $this->Teams->newEntity();
            if ($this->request->is('post')) {
                $team = $this->Teams->patchEntity($team, $this->request->data);
                $ExtraInfo->setCreatedBy($team,$current_user);
                $ExtraInfo->setModifiedBy($team,$current_user);
                if ($this->Teams->save($team)) {
                    $this->Flash->success(__('The {0} has been saved.', 'Team'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Team'));
                }
            }
            $leaders = $this->Teams->Leaders->find('all',['fields'=>['id','first_name','last_name']])->where(['id' => $current_user]);
            $projects = $this->Teams->Projects->find('list', ['limit' => 200]);
            $users = $this->Teams->Users->find('all',['fields'=>['id','first_name','last_name']])->where(['type_id >=' => $currentUserType]);
            $this->set(compact('team', 'leaders', 'projects', 'users'));
            $this->set('_serialize', ['team']);
        }
        //If its not admin user than redirect him
        else{
            $this->redirect(['controller'=>'Dashboard','action'=>'index']);
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Team id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        //Check if its admin or not
        //In our DB the id for the admin user is 11 ,so we will check that only
        //admin user --  (username= imadmin , password = admin)
        $ExtraInfo=$this->loadComponent('Extra_info');
        $current_user=$ExtraInfo->getCurrentUser($this);
        if($current_user==11){
            $ExtraInfo=$this->loadComponent('Extra_info');
            $current_user=$ExtraInfo->getCurrentUser($this);
            $currentUserType=$ExtraInfo->getCurrentType($this);
            $team = $this->Teams->get($id, [
                'contain' => ['Users']
            ]);
            if ($this->request->is(['patch', 'post', 'put'])) {
                $team = $this->Teams->patchEntity($team, $this->request->data);
                $ExtraInfo->setModifiedBy($team,$current_user);
                if ($this->Teams->save($team)) {
                    $this->Flash->success(__('The {0} has been saved.', 'Team'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(__('The {0} could not be saved. Please, try again.', 'Team'));
                }
            }
            $leaders = $this->Teams->Leaders->find('all',['fields'=>['id','first_name','last_name']])->where(['id' => $current_user]);
            $projects = $this->Teams->Projects->find('list', ['limit' => 200]);
            $users = $this->Teams->Users->find('all',['fields'=>['id','first_name','last_name']])->where(['type_id >=' => $currentUserType]);
            $this->set(compact('team', 'leaders', 'projects', 'users'));
            $this->set('_serialize', ['team']);
        }
        //If its not admin user than redirect him
        else{
            $this->redirect(['controller'=>'Dashboard','action'=>'index']);
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Team id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        //Check if its admin or not
        //In our DB the id for the admin user is 11 ,so we will check that only
        //admin user --  (username= imadmin , password = admin)
        $ExtraInfo=$this->loadComponent('Extra_info');
        $current_user=$ExtraInfo->getCurrentUser($this);
        if($current_user==11){
            $this->request->allowMethod(['post', 'delete']);
            $team = $this->Teams->get($id);
            if ($this->Teams->delete($team)) {
                $this->Flash->success(__('The {0} has been deleted.', 'Team'));
            } else {
                $this->Flash->error(__('The {0} could not be deleted. Please, try again.', 'Team'));
            }
            return $this->redirect(['action' => 'index']);
        }
        //If its not admin user than redirect him
        else{
            $this->redirect(['controller'=>'Dashboard','action'=>'index']);
        }
    }
}
