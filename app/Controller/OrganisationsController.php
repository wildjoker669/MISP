<?php
App::uses('AppController', 'Controller');
/**
 * Organisations Controller
 *
 * @property Organisation $Organisation
 */
class OrganisationsController extends AppController {

    public $paginate = array();

/**
 * index method
 *
 * @return void
 */
    public function index() {
        if(!empty($this->request->data)){
            $redirect = array('action' => 'index');
            foreach($this->request->data['Organisation'] as $k => $v){
                if(!empty($v)){
                    $redirect[$k] = $v
;                }
            }
            $this->redirect($redirect);
        }
        if(!empty($this->passedArgs['key'])){
            $this->paginate['conditions'] = array('Organisation.name LIKE' => '%'.$this->passedArgs['key'].'%');
        }

        $this->paginate = array('contain' => array(
            'OrganisationType', 'OrganisationCategory', 'Country'
        ));
        $this->set('organisations', $this->paginate());
    }

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function view($id = null) {
        if (!$this->Organisation->exists($id)) {
            throw new NotFoundException(__('Invalid organisation'));
        }
        $options = array(
            'conditions' => array('Organisation.' . $this->Organisation->primaryKey => $id),
            'contain' => array('OrganisationType', 'OrganisationCategory', 'Country')
        );
        $this->set('organisation', $this->Organisation->find('first', $options));
    }

/**
 * add method
 *
 * @return void
 */
    public function add() {
        if ($this->request->is('post')) {
            $this->Organisation->create();
            if ($this->Organisation->save($this->request->data)) {
                $this->Session->setFlash(__('The organisation has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The organisation could not be saved. Please, try again.'));
            }
        }
        $organisationCategories = $this->Organisation->OrganisationCategory->find('list');
        $organisationTypes = $this->Organisation->OrganisationType->find('list');
        $countries = $this->Organisation->Country->find('list');
        $this->set(compact('organisationCategories', 'organisationTypes', 'countries'));
    }

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function edit($id = null) {
        if (!$this->Organisation->exists($id)) {
            throw new NotFoundException(__('Invalid organisation'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Organisation->save($this->request->data)) {
                $this->Session->setFlash(__('The organisation has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The organisation could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Organisation.' . $this->Organisation->primaryKey => $id));
            $this->request->data = $this->Organisation->find('first', $options);
        }
        $organisationCategories = $this->Organisation->OrganisationCategory->find('list');
        $organisationTypes = $this->Organisation->OrganisationType->find('list');
        $countries = $this->Organisation->Country->find('list');
        $this->set(compact('organisationCategories', 'organisationTypes', 'countries'));
    }

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function delete($id = null) {
        $this->Organisation->id = $id;
        if (!$this->Organisation->exists()) {
            throw new NotFoundException(__('Invalid organisation'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->Organisation->delete()) {
            $this->Session->setFlash(__('Organisation deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Organisation was not deleted'));
        $this->redirect(array('action' => 'index'));
    }
}
