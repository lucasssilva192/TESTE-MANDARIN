<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function add_task(Request $request)
    {
        if ($request->name && $request->file_url) {
            $task = Task::create([
                'name' => $request->name,
                'description' =>  $request->description,
                'status' => $request->status ? $request->status : 'backlog',
                'file_url' => $request->file_url
            ]);
            return response()->json(['task' => $task], 200);
        } else {
            return response('Há dados obrigatórios em falta');
        }
    }

    public function edit_task(Task $task, Request $request)
    {
        if ($task) {
            $task->update([
                'name' => $request->name ? $request->name : $task->name,
                'description' =>  $request->description ? $request->description : $task->description,
                'file_url' => $request->file_url ? $request->file_url : $task->file_url
            ]);
        } else {
            return response('Task não encontrada');
        }
    }

    public function update_status(Task $task, Request $request)
    {
        $novo_status = '';
        if ($task) {
            switch ($task->status) {
                case 'backlog':
                    $novo_status = 'in_progress';
                    break;
                case 'in_progress':
                    $novo_status = 'waiting_customer_approval';
                    break;
                case 'waiting_customer_approval':
                    $novo_status = 'approved';
                    break;
                case 'approved':
                    $novo_status = 'approved';
                    break;
            }
            $task->update([
                'status' => $novo_status
            ]);
        } else {
            return response('Task não encontrada');
        }
    }

    public function add_tag(Task $task, Request $request)
    {
        if ($task) {
            if (!Tag::where('tag_name', $request->tag_name)->where('task_id', $task->id)->exists()) {
                $tag = Tag::create([
                    'tag_name' => $request->tag_name,
                    'task_id' => $task->id
                ]);
            } else {
                return response('A Tag informada já existe e está associada à task em questão');
            }
        } else {
            return response('Task não encontrada');
        }
    }

    public function get_tasks()
    {
        $tasks = Task::all();
        if (!$tasks) {
            return response('Nenhuma task encontrada');
        }
        $tasks_with_tags = [];
        foreach ($tasks as $task) {
            $task_tags = Tag::where('task_id', $task->id)->get();
            $tags = [];
            foreach ($task_tags as $tt) {
                $tags[] = ['Nome da tag' => $tt->tag_name];
            }
            $tasks_with_tags[] = [
                'Nome' => $task->name,
                'Status' => $task->status,
                'Descrição' => $task->description,
                'Criado em' => date_format($task->created_at, 'Y-m-d H:i:s'),
                'Editado em' => date_format($task->updated_at, 'Y-m-d H:i:s'),
                'Tags' => $tags
            ];
        }
        $collection = collect($tasks_with_tags);
        return response()->json(['tasks' => $collection]);
    }

    public function get_file_url(Task $task)
    {
        if ($task) {
            if($task->status == 'approved'){
                return response($task->file_url); 
            } else {
                return response('Aguardando aprovação do material');    
            }
        } else {
            return response('Task não encontrada');
        }
    }
}
