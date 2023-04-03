<?php

namespace App\Http\Controllers\publishers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class ClientsController extends Controller
{
    public function index()
    {
        $clients = auth()->user()->publisherClients()->paginate(20);
        return view('PublisherPanel.clients.index', [
            'title' => trans('common.Clients'),
            'active' => 'clients',
            'clients' => $clients,
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.Clients')
                ]
            ]
        ]);
    }
    public function details($id)
    {

        $user = User::find($id);
        return view('PublisherPanel.clients.details', [
            'title' => trans('common.Clients'),
            'active' => 'clients',
            'user' => $user,
            'breadcrumbs' => [
                [
                    'url' => route('publisher.clients'),
                    'text' => trans('common.Clients')
                ],
                [
                    'url' => '',
                    'text' => trans('common.client').': #'.$id
                ]
            ]
        ]);
    }
}
