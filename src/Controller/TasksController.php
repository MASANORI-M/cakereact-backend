<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\TableRegistry;

/**
 * Tasks Controller
 *
 * @method \App\Model\Entity\Task[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TasksController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function index()
    {
        $tasks = $this->Tasks->find()
        ->where(['Tasks.deleted' => 0])
        ->toArray();

        $this->set([
            'tasks' => $tasks,
            '_serialize' => ['tasks']
        ]);
    }

    public function view($id = null)
    {
        $task = $this->Tasks->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('task'));
    }

    public function add()
    {
        $this->fetchTable('Tasks');
        $task = $this->request->getData();
        $tasks = TableRegistry::getTableLocator()->get('Tasks');
        $new_task = $tasks->newEntity($task);
        $tasks->save($new_task);

        $this->set('tasks', ['new_task' => ['title' => $new_task->title, 'deleted' => $new_task->deleted]]);
        $this->viewBuilder()
            ->setClassName('Json')
            ->setOption('serialize', ['tasks'])
            ->setOption('jsonOptions', JSON_FORCE_OBJECT);
    }

    public function edit($id)
    {
        $this->fetchTable('Tasks');
        $id = $this->request->getParam('id');
        $data = $this->request->getData();
        $current_task = $this->Tasks->get($id);
        $edit_task = $this->Tasks->patchEntity($current_task, $data);
        $this->Tasks->save($edit_task);

        $this->set('data', ['id' => $edit_task->id ,'title' => $edit_task->title]);
        $this->viewBuilder()
            ->setClassName('Json')
            ->setOption('serialize', ['data'])
            ->setOption('jsonOptions', JSON_FORCE_OBJECT);
    }

    public function delete($id)
    {
        $this->fetchTable('Tasks');
        $id = $this->request->getParam('id');
        $current_task = $this->Tasks->get($id);
        $delete_task = $this->Tasks->patchEntity($current_task, ['deleted' => 1]);
        $this->Tasks->save($delete_task);

        $this->set('current_task', ['id' => $id]);
        $this->viewBuilder()
            ->setClassName('Json')
            ->setOption('serialize', ['current_task'])
            ->setOption('jsonOptions', JSON_FORCE_OBJECT);
    }
}
