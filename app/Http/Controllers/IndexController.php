<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\JsonRpcClient;

class IndexController extends Controller
{
    protected $client;

    public function __construct(JsonRpcClient $client)
    {
        $this->client = $client;
    }

    /**
     * Display last 30 histories and get history by date.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $historyByDate = [];
        $errorMessage = '';

        $data = $this->client->getLasts('weatherGetHistory', ['lastDays' => '30']);

        if (empty($data['result']) && !empty($data['error'])) {
            $errorMessage = $data['error']['message'];
            return response($errorMessage, 401);
        }
        $lastHistories = $data['result'];

        if ($request->method() == 'POST') {
            $this->validate(
                $request,
                ['date_at' => 'required|date_format:"Y-m-d"'],
                [
                    'date_at.required' => 'Поле Дата обязательно для заполнения.',
                    'date_at.date_format' => 'Неверный формат даты!'
                ]
            );
            $data = $this->client->getByDate('weatherGetByDate', ['date' => $request->date_at]);

            if (empty($data['result'])) {
                $errorMessage = 'В эту дату у нас нет температуры';
                return redirect()->back()->withInput()->withErrors($errorMessage);

            }
            $historyByDate = $data['result'];
        }

        return view('history', ['lastHistories' => $lastHistories, 'historyByDate' => $historyByDate, 'errorMessage' => $errorMessage]);
    }
}
