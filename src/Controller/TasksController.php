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
        ->where(['Tasks.deleted' => 0]);

        $this->set(compact('tasks'));

        $this->viewBuilder()
        ->setClassName('Json')
        ->setOption('serialize', ['tasks']);
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

        $this->set(
            'tasks',
            [
                'id' => $new_task->id,
                'title' => $new_task->title,
                'created' => $new_task->created,
                'modified' => $new_task->modified,
            ]
        );
        $this->viewBuilder()
        ->setClassName('Json')
        ->setOption('serialize', ['tasks']);
    }

    public function edit($id)
    {
        $this->fetchTable('Tasks');
        $id = $this->request->getParam('id');
        $data = $this->request->getData();
        $current_task = $this->Tasks->get($id);
        $edit_task = $this->Tasks->patchEntity($current_task, $data);
        $this->Tasks->save($edit_task);

        $this->set(
            'tasks',
            [
                'id' => $edit_task->id,
                'title' => $edit_task->title,
                'created' => $edit_task->created,
                'modified' => $edit_task->modified,
            ]
        );
        $this->viewBuilder()
        ->setClassName('Json')
        ->setOption('serialize', ['tasks']);
    }

    public function delete($id)
    {
        $this->fetchTable('Tasks');
        $id = $this->request->getParam('id');
        $current_task = $this->Tasks->get($id);
        $delete_task = $this->Tasks->patchEntity($current_task, ['deleted' => 1]);
        $this->Tasks->save($delete_task);

        $this->set(
            'tasks',
            [
                'id' => $delete_task->id,
                'deleted' => $delete_task->deleted
            ]
        );
        $this->viewBuilder()
        ->setClassName('Json')
        ->setOption('serialize', ['tasks']);
    }
}
