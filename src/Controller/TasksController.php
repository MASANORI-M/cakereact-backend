<?php
declare(strict_types=1);

namespace App\Controller;

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
        $this->request->allowMethod(['post', 'put']);
        $json = $this->request->getData();
        // $json = json_decode($this->request->getData(), true);
        $this->log(var_export($json, true));

        $add_task = $this->Tasks->newEntity($json);
        if($this->Tasks->save($add_task)) {
            $msg = 'SAVED';
            $this->log(var_export("成功", true));
        } else {
            $msg = "ERROR";
            $this->log(var_export("失敗", true));
        }

        $this->set([
            'message' => $msg,
            'add_task' => $add_task,
            '_serialize' => ['add_task']
        ]);
    }

    public function edit($id)
    {
        $this->request->allowMethod(['patch', 'post', 'put']);
        $task = $this->Tasks->get($id);
        $this->log(var_export($task, true));
        $edit_task = $this->Tasks->patchEntity($task, $this->request->getData());
        if($this->Urls->save($edit_task)) {
            $msg = 'SAVED';
        } else {
            $msg = "ERROR";
        }

        $this->set([
            'message' => $msg,
            'edit_task' => $edit_task,
            '_serialize' => ['edit_task']
        ]);
    }

    public function delete($id)
    {
        $this->request->allowMethod(['patch', 'post', 'put']);
        $task = $this->Urls->get($id);
        $delete_task = $this->Tasks->patchEntity($task, ['deleted' => 1]);
        if($this->Tasks->save($delete_task)) {
            $msg = 'SAVED';
        } else {
            $msg = "ERROR";
        }

        $this->set([
            'message' => $msg,
            'delete_task' => $delete_task,
            '_serialize' => ['delete_task']
        ]);
    }
}
